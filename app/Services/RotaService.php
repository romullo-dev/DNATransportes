<?php

namespace App\Services;

use App\DTOs\AdminRotaUpdateData;
use App\DTOs\RotaData;
use App\Enums\StatusHistoricoPedido;
use App\Enums\TipoRota;
use App\Models\Rota;
use App\Models\RotaAlteracao;
use App\Repositories\HistoricoRepository;
use App\Repositories\PedidoRepository;
use App\Repositories\RotaRepository;
use DomainException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RotaService
{
    public function __construct(
        private readonly RotaRepository $rotas,
        private readonly PedidoRepository $pedidos,
        private readonly HistoricoRepository $historicos,
    ) {}

    public function listar()
    {
        return $this->rotas->listarComRelacionamentos();
    }

    public function dadosFormulario(): array
    {
        return $this->rotas->dadosFormulario();
    }

    public function criar(RotaData $data): Rota
    {
        return DB::transaction(function () use ($data) {
            $pedidos = $this->pedidosParaVinculo($data);
            $rota = $this->rotas->criar($data);
            $this->vincularPedidos($rota, $data, $pedidos);

            return $rota;
        });
    }

    public function atualizar(Rota $rota, RotaData $data): Rota
    {
        return DB::transaction(function () use ($rota, $data) {
            $pedidos = $this->pedidosParaVinculo($data);
            $rota = $this->rotas->atualizar($rota, $data);
            $this->vincularPedidos($rota, $data, $pedidos);

            return $rota;
        });
    }

    public function dadosEdicaoAdmin(Rota $rota): array
    {
        $rota = $this->rotas->carregarParaEdicaoAdmin($rota);
        $idsVinculados = $this->historicos->idsPedidosDaRota($rota->id_rotas);
        $idsEntregues = $this->pedidos->idsEntregues($idsVinculados);
        $dadosFormulario = $this->rotas->dadosFormulario();

        return [
            ...$dadosFormulario,
            'rota' => $rota,
            'pedidosVinculados' => $rota->pedidos->unique('id_pedido')->values(),
            'pedidosDisponiveis' => $this->pedidos->listarDisponiveisParaRota()
                ->reject(fn ($pedido) => $idsVinculados->contains($pedido->id_pedido))
                ->values(),
            'pedidoIdsSelecionados' => $idsVinculados->all(),
            'pedidoIdsEntregues' => $idsEntregues->all(),
            'statusRotas' => ['Planejada', 'Em andamento', 'Finalizada', 'Cancelada'],
        ];
    }

    public function atualizarAdmin(Rota $rota, AdminRotaUpdateData $data, ?int $usuarioId): Rota
    {
        return DB::transaction(function () use ($rota, $data, $usuarioId) {
            $idsAtuais = $this->historicos->idsPedidosDaRota($rota->id_rotas);
            $idsSolicitados = collect($data->pedidoIds)->map(fn ($id) => (int) $id)->filter()->unique()->values();
            $idsEntreguesAtuais = $this->pedidos->idsEntregues($idsAtuais);
            $idsEntreguesSolicitados = $this->pedidos->idsEntregues($idsSolicitados);

            $idsEntreguesNovos = $idsEntreguesSolicitados->diff($idsAtuais);
            if ($idsEntreguesNovos->isNotEmpty()) {
                $this->bloquearPedidoEntregue($idsEntreguesNovos);
            }

            $idsEntreguesRemovidos = $idsEntreguesAtuais->diff($idsSolicitados);
            if ($idsEntreguesRemovidos->isNotEmpty()) {
                throw new DomainException('Este pedido já foi entregue e não pode ter seu vínculo removido da rota.');
            }

            $antes = $this->snapshotRota($rota, $idsAtuais);
            $rota = $this->rotas->atualizarAdmin($rota, $data);

            $idsParaRemover = $idsAtuais->diff($idsSolicitados);
            $idsParaAdicionar = $idsSolicitados->diff($idsAtuais);

            $this->historicos->removerVinculosDaRota($rota->id_rotas, $idsParaRemover);
            $this->adicionarPedidosAdmin($rota, $idsParaAdicionar, $data);

            $depois = $this->snapshotRota($rota->refresh(), $this->historicos->idsPedidosDaRota($rota->id_rotas));

            RotaAlteracao::create([
                'rotas_id_rotas' => $rota->id_rotas,
                'id_usuario' => $usuarioId,
                'motivo' => $data->motivoAlteracao,
                'dados_anteriores' => $antes,
                'dados_novos' => $depois,
            ]);

            return $rota->loadMissing(['pedidos', 'motorista.usuario', 'veiculo', 'origem', 'destino', 'historicos']);
        });
    }

    private function pedidosParaVinculo(RotaData $data): Collection
    {
        if ($data->chavesNota === []) {
            return collect();
        }

        $pedidos = $this->pedidos->buscarPorChavesNfe($data->chavesNota);
        $idsEntregues = $this->pedidos->idsEntregues($pedidos->pluck('id_pedido'));

        if ($idsEntregues->isNotEmpty()) {
            $this->bloquearPedidoEntregue($idsEntregues);
        }

        return $pedidos;
    }

    private function vincularPedidos(Rota $rota, RotaData $data, Collection $pedidos): void
    {
        if ($pedidos->isEmpty()) {
            return;
        }

        $statusInicial = StatusHistoricoPedido::inicialDaRota($data->tipo);

        foreach ($pedidos as $pedido) {
            if ($this->historicos->pedidoJaVinculadoNaRota($rota->id_rotas, $pedido->id_pedido)) {
                continue;
            }

            $this->historicos->registrar(
                rotaId: $rota->id_rotas,
                pedidoId: $pedido->id_pedido,
                data: now(),
                status: $statusInicial->value,
                foto: '',
                observacao: $data->observacoes,
            );
        }
    }

    private function adicionarPedidosAdmin(Rota $rota, Collection $pedidoIds, AdminRotaUpdateData $data): void
    {
        if ($pedidoIds->isEmpty()) {
            return;
        }

        $this->bloquearPedidoEntregue($this->pedidos->idsEntregues($pedidoIds));

        $statusInicial = StatusHistoricoPedido::inicialDaRota(TipoRota::fromRequest($rota->tipo));

        foreach ($pedidoIds as $pedidoId) {
            $this->historicos->registrar(
                rotaId: $rota->id_rotas,
                pedidoId: (int) $pedidoId,
                data: now(),
                status: $statusInicial->value,
                foto: '',
                observacao: 'Adicionado na edição ADM: '.$data->motivoAlteracao,
            );
        }
    }

    private function bloquearPedidoEntregue(Collection $pedidoIds): void
    {
        if ($pedidoIds->isEmpty()) {
            return;
        }

        if ($pedidoIds->count() === 1) {
            throw new DomainException('Este pedido já foi entregue e não pode entrar em uma nova rota.');
        }

        throw new DomainException('Estes pedidos já foram entregues e não podem entrar em uma nova rota: #'.$pedidoIds->implode(', #').'.');
    }

    private function snapshotRota(Rota $rota, Collection $pedidoIds): array
    {
        return [
            'id_motorista' => $rota->id_motorista,
            'id_veiculo' => $rota->id_veiculo,
            'id_origem' => $rota->id_origem,
            'id_destino' => $rota->id_destino,
            'status' => $rota->status,
            'observacoes' => $rota->observacoes,
            'pedido_ids' => $pedidoIds->values()->all(),
        ];
    }
}
