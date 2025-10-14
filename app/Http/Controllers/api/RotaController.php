<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Historico;
use App\Models\Pedido;
use App\Models\Rota;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RotaController extends Controller
{
    private const TIPOS_SUPORTADOS = ['Coleta', 'Transferencia', 'Entrega'];

    public function index(Request $request)
    {
        $query = Rota::with(['pedidos.notaFiscal', 'motorista.usuario', 'veiculo', 'origem', 'destino', 'historicos.pedido']);

        if ($request->filled('tipo')) {
            $query->where('tipo', $this->normalizaTipo($request->input('tipo')));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('data_rota', '>=', Carbon::parse($request->input('data_inicio'))->toDateString());
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('data_rota', '<=', Carbon::parse($request->input('data_fim'))->toDateString());
        }

        $perPage = (int) $request->input('per_page', 15);
        $perPage = $perPage > 0 ? min($perPage, 100) : 15;

        return response()->json($query->paginate($perPage));
    }

    public function show(Rota $rota)
    {
        $rota->load(['pedidos.notaFiscal', 'motorista.usuario', 'veiculo', 'origem', 'destino', 'historicos.pedido']);

        return response()->json($rota);
    }

    public function store(Request $request)
    {
        $dados = $this->validaRota($request);

        $rota = DB::transaction(function () use ($dados) {
            $rota = new Rota();
            $rota->tipo = $this->normalizaTipo($dados['tipo']);
            $rota->id_origem = $dados['id_origem'];
            $rota->id_destino = $dados['id_destino'] ?? $dados['id_origem'];
            $rota->distancia = $dados['distancia'];
            $rota->previsao = Carbon::parse($dados['previsao']);
            $rota->data_rota = Carbon::parse($dados['data_inicio'])->toDateString();
            $rota->data_inicio = Carbon::parse($dados['data_inicio']);
            $rota->data_criacao = Carbon::now();
            $rota->id_motorista = $dados['id_motorista'];
            $rota->id_veiculo = $dados['id_veiculo'];
            $rota->status = $dados['status'] ?? 'Planejada';
            $rota->observacoes = $dados['observacoes'] ?? null;
            $rota->save();

            $pedidoIds = $this->resolvePedidos($dados);
            if (!empty($pedidoIds)) {
                $this->criaHistoricosIniciais($rota, $pedidoIds, $dados['observacoes'] ?? null);
            }

            return $rota;
        });

        $rota->load(['pedidos.notaFiscal', 'motorista.usuario', 'veiculo', 'origem', 'destino', 'historicos.pedido']);

        return response()->json($rota, 201);
    }

    public function update(Request $request, Rota $rota)
    {
        $dados = $this->validaRota($request, true);

        DB::transaction(function () use ($dados, $rota) {
            if (isset($dados['tipo'])) {
                $rota->tipo = $this->normalizaTipo($dados['tipo']);
            }

            foreach (['id_origem', 'id_destino', 'distancia', 'id_motorista', 'id_veiculo', 'status'] as $campo) {
                if (array_key_exists($campo, $dados)) {
                    $rota->{$campo} = $dados[$campo];
                }
            }

            if (array_key_exists('previsao', $dados)) {
                $rota->previsao = Carbon::parse($dados['previsao']);
            }

            if (array_key_exists('data_inicio', $dados)) {
                $rota->data_rota = Carbon::parse($dados['data_inicio'])->toDateString();
                $rota->data_inicio = Carbon::parse($dados['data_inicio']);
            }

            if (array_key_exists('observacoes', $dados)) {
                $rota->observacoes = $dados['observacoes'];
            }

            $rota->save();

            $novosPedidos = $this->resolvePedidos($dados);
            if (!empty($novosPedidos)) {
                $existentes = $rota->historicos()->pluck('pedido_id_pedido')->unique()->toArray();
                $novosPedidos = array_values(array_diff($novosPedidos, $existentes));

                if (!empty($novosPedidos)) {
                    $this->criaHistoricosIniciais($rota, $novosPedidos, $dados['observacoes'] ?? null);
                }
            }
        });

        $rota->load(['pedidos.notaFiscal', 'motorista.usuario', 'veiculo', 'origem', 'destino', 'historicos.pedido']);

        return response()->json($rota);
    }

    public function destroy(Rota $rota)
    {
        DB::transaction(function () use ($rota) {
            $rota->historicos()->delete();
            $rota->delete();
        });

        return response()->json([], 204);
    }

    public function historicos(Rota $rota)
    {
        $historicos = $rota->historicos()->with('pedido.notaFiscal')->orderByDesc('data')->get();

        return response()->json($historicos);
    }

    public function registrarHistorico(Request $request, Rota $rota)
    {
        $dados = $request->validate([
            'status' => ['required', Rule::in(['Em trânsito', 'Finalizado', 'Ocorrência'])],
            'data' => ['required', 'date', 'before_or_equal:' . Carbon::now()->format('Y-m-d H:i:s')],
            'observacao' => ['nullable', 'string', 'max:255'],
            'pedido_id' => ['nullable', 'integer'],
            'aplicar_em_todos' => ['nullable', 'boolean'],
            'tipo' => ['nullable', Rule::in(self::TIPOS_SUPORTADOS)],
            'foto' => ['nullable', 'file', 'max:2048'],
        ]);

        $ultimoHistorico = $rota->historicos()->orderByDesc('data')->first();
        if ($ultimoHistorico && $ultimoHistorico->status === 'Finalizado') {
            return response()->json(['error' => 'Não é possível alterar a rota, pois o último histórico está como "Finalizado".'], 409);
        }

        $pedidosAssociados = $rota->historicos()->pluck('pedido_id_pedido')->unique();
        if ($pedidosAssociados->isEmpty()) {
            return response()->json(['error' => 'A rota não possui pedidos associados.'], 400);
        }

        if (!empty($dados['pedido_id']) && !$pedidosAssociados->contains($dados['pedido_id'])) {
            return response()->json(['error' => 'O pedido informado não pertence à rota selecionada.'], 422);
        }

        $pedidoIds = $dados['aplicar_em_todos'] ?? false
            ? $pedidosAssociados->all()
            : (isset($dados['pedido_id']) ? [$dados['pedido_id']] : $pedidosAssociados->all());

        $arquivoFoto = null;
        if ($request->hasFile('foto')) {
            $arquivoFoto = $request->file('foto')->store('historicos', 'public');
        }

        $tipo = $dados['tipo'] ?? $rota->tipo;
        $tipo = $this->normalizaTipo($tipo);

        $historicosCriados = [];
        foreach ($pedidoIds as $pedidoId) {
            $historico = $this->criaHistoricoAvancado(
                $rota,
                (int) $pedidoId,
                $dados['status'],
                $tipo,
                Carbon::parse($dados['data']),
                $dados['observacao'] ?? null,
                $arquivoFoto
            );
            if ($historico) {
                $historicosCriados[] = $historico;
            }
        }

        return response()->json($historicosCriados, 201);
    }

    private function validaRota(Request $request, bool $atualizacao = false): array
    {
        $tipoRegra = $atualizacao ? 'sometimes' : 'required';

        return $request->validate([
            'tipo' => [$tipoRegra, Rule::in(self::TIPOS_SUPORTADOS)],
            'id_origem' => [$tipoRegra, 'integer', 'exists:centrodistribuicao,id_centro'],
            'id_destino' => ['sometimes', 'nullable', 'integer', 'exists:centrodistribuicao,id_centro'],
            'distancia' => [$tipoRegra, 'numeric', 'min:0'],
            'previsao' => [$tipoRegra, 'date'],
            'data_inicio' => [$tipoRegra, 'date'],
            'id_motorista' => [$tipoRegra, 'integer', 'exists:motorista,id_motorista'],
            'id_veiculo' => [$tipoRegra, 'integer', 'exists:veiculo,id_veiculo'],
            'observacoes' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'string'],
            'pedido_ids' => ['sometimes', 'array'],
            'pedido_ids.*' => ['integer', 'exists:pedido,id_pedido'],
            'chaves_nota' => ['sometimes'],
        ]);
    }

    private function resolvePedidos(array $dados): array
    {
        $ids = [];
        if (!empty($dados['pedido_ids']) && is_array($dados['pedido_ids'])) {
            $ids = array_map('intval', $dados['pedido_ids']);
        }

        if (!empty($dados['chaves_nota'])) {
            $chaves = $dados['chaves_nota'];
            if (is_string($chaves)) {
                $chaves = array_filter(array_map('trim', explode(',', $chaves)));
            }
            if (is_array($chaves) && !empty($chaves)) {
                $pedidosPorNota = Pedido::whereHas('notaFiscal', function ($query) use ($chaves) {
                    $query->whereIn('chave_acesso', $chaves);
                })->pluck('id_pedido')->all();

                $ids = array_merge($ids, $pedidosPorNota);
            }
        }

        return array_values(array_unique($ids));
    }

    private function criaHistoricosIniciais(Rota $rota, array $pedidoIds, ?string $observacao): void
    {
        $statusInicial = $this->statusInicialPorTipo($rota->tipo);
        $momento = Carbon::now();

        foreach ($pedidoIds as $pedidoId) {
            Historico::create([
                'rotas_id_rotas' => $rota->id_rotas,
                'pedido_id_pedido' => $pedidoId,
                'data' => $momento,
                'status' => $statusInicial,
                'foto' => null,
                'observacao' => $observacao,
            ]);

            if ($pedido = Pedido::find($pedidoId)) {
                $pedido->status = $statusInicial;
                $pedido->save();
            }
        }
    }

    private function statusInicialPorTipo(string $tipo): string
    {
        return match ($this->normalizaTipo($tipo)) {
            'Coleta' => 'Aguardando coleta',
            'Transferencia' => 'Aguardando transferência',
            'Entrega' => 'Em processo de separação no destino',
            default => 'Pendente',
        };
    }

    private function normalizaTipo(string $tipo): string
    {
        $tipo = trim($tipo);
        $tipo = ucfirst(mb_strtolower($tipo));

        if ($tipo === 'Transferência') {
            return 'Transferencia';
        }

        return $tipo;
    }

    private function criaHistoricoAvancado(
        Rota $rota,
        int $pedidoId,
        string $statusBase,
        string $tipo,
        Carbon $data,
        ?string $observacao,
        ?string $foto
    ): ?Historico {
        [$statusHistorico, $statusPedido] = $this->defineStatusPorTipoEAcao($tipo, $statusBase);

        if ($statusHistorico === null) {
            return null;
        }

        $historico = Historico::create([
            'rotas_id_rotas' => $rota->id_rotas,
            'pedido_id_pedido' => $pedidoId,
            'data' => $data,
            'status' => $statusHistorico,
            'foto' => $foto,
            'observacao' => $observacao,
        ]);

        if ($pedido = Pedido::find($pedidoId)) {
            $pedido->status = $statusPedido;
            $pedido->save();
        }

        return $historico->load('pedido');
    }

    private function defineStatusPorTipoEAcao(string $tipo, string $statusBase): array
    {
        $tipoNormalizado = $this->normalizaTipo($tipo);
        $statusBase = trim($statusBase);

        return match ($tipoNormalizado) {
            'Coleta' => match ($statusBase) {
                'Em trânsito' => ['Em processo de coleta', 'Em processo de coleta'],
                'Finalizado' => ['Coleta realizada', 'Coleta realizada'],
                'Ocorrência' => ['Coleta não realizada', 'Coleta não realizada'],
                default => [null, null],
            },
            'Transferencia' => match ($statusBase) {
                'Em trânsito' => ['Em processo de transferência', 'Em processo de transferência'],
                'Finalizado' => ['Transferência realizada', 'Transferência realizada'],
                'Ocorrência' => ['Transferência não realizada', 'Transferência não realizada'],
                default => [null, null],
            },
            'Entrega' => match ($statusBase) {
                'Em trânsito' => ['Em rota de entrega', 'Em rota de entrega'],
                'Finalizado' => ['Entrega realizada', 'Entrega realizada'],
                'Ocorrência' => ['Entrega não realizada', 'Entrega não realizada'],
                default => [null, null],
            },
            default => [null, null],
        };
    }
}
