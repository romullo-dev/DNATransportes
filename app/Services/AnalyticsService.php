<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AnalyticsService
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function resumo(array $filters = []): array
    {
        $pedidos = $this->pedidos($filters);
        $sla = $this->sla($filters);
        $faturamento = $this->faturamento($filters);
        $ocorrencias = $this->ocorrencias($filters);

        return [
            'periodo' => $this->periodo($filters),
            'pedidos' => [
                'total' => $pedidos['total'],
                'entregues' => $pedidos['entregues'],
                'em_aberto' => $pedidos['em_aberto'],
                'atrasados' => $pedidos['atrasados'],
            ],
            'sla' => [
                'no_prazo_percentual' => $sla['no_prazo_percentual'],
                'fora_prazo_percentual' => $sla['fora_prazo_percentual'],
            ],
            'financeiro' => [
                'valor_total_frete' => $faturamento['valor_total_frete'],
            ],
            'operacional' => [
                'peso_total' => $pedidos['peso_total'],
                'volumes_total' => $pedidos['volumes_total'],
                'tempo_medio_entrega_horas' => $pedidos['tempo_medio_entrega_horas'],
            ],
            'ocorrencias' => [
                'total' => $ocorrencias['total'],
                'por_tipo' => $ocorrencias['por_tipo'],
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function pedidos(array $filters = []): array
    {
        $query = $this->pedidoQuery($filters);
        $total = (clone $query)->count('pedido.id_pedido');
        $entregues = $this->entreguesCount($filters);
        $atrasados = $this->atrasadosCount($filters);
        $emAberto = max($total - $entregues, 0);

        return [
            'periodo' => $this->periodo($filters),
            'total' => $total,
            'entregues' => $entregues,
            'em_aberto' => $emAberto,
            'atrasados' => $atrasados,
            'por_status' => $this->groupCount((clone $query), 'pedido.status'),
            'peso_total' => $this->round((clone $query)->sum(DB::raw($this->pesoExpression())), 3),
            'volumes_total' => (int) (clone $query)->sum('notafiscal.quantidade_volumes'),
            'tempo_medio_entrega_horas' => $this->tempoMedioEntrega($filters),
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function rotas(array $filters = []): array
    {
        $query = $this->rotaQuery($filters);

        return [
            'periodo' => $this->periodo($filters),
            'total' => (clone $query)->count('rotas.id_rotas'),
            'por_status' => Schema::hasColumn('rotas', 'status') ? $this->groupCount((clone $query), 'rotas.status') : [],
            'por_tipo' => $this->groupCount((clone $query), 'rotas.tipo'),
            'distancia_total' => $this->round((clone $query)->sum('rotas.distancia'), 2),
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function motoristas(array $filters = []): array
    {
        $query = $this->historicoEntregaQuery($filters)
            ->join('rotas', 'rotas.id_rotas', '=', 'historico.rotas_id_rotas')
            ->join('motorista', 'motorista.id_motorista', '=', 'rotas.id_motorista')
            ->join('usuario', 'usuario.id_usuario', '=', 'motorista.id_Usuario')
            ->selectRaw('motorista.id_motorista, usuario.nome, count(distinct historico.pedido_id_pedido) as entregas')
            ->groupBy('motorista.id_motorista', 'usuario.nome')
            ->orderByDesc('entregas')
            ->limit(10);

        return [
            'periodo' => $this->periodo($filters),
            'ranking' => $query->get(),
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function clientes(array $filters = []): array
    {
        $query = $this->pedidoQuery($filters)
            ->join('cliente as cliente_destinatario', 'cliente_destinatario.id_cliente', '=', 'notafiscal.cliente_destinatario')
            ->selectRaw('cliente_destinatario.id_cliente, cliente_destinatario.nome, count(distinct pedido.id_pedido) as pedidos')
            ->groupBy('cliente_destinatario.id_cliente', 'cliente_destinatario.nome')
            ->orderByDesc('pedidos')
            ->limit(10);

        return [
            'periodo' => $this->periodo($filters),
            'clientes_com_mais_pedidos' => $query->get(),
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function ocorrencias(array $filters = []): array
    {
        if (! Schema::hasTable('ocorrencias')) {
            return [
                'periodo' => $this->periodo($filters),
                'total' => 0,
                'por_tipo' => [],
                'por_status' => [],
            ];
        }

        $query = $this->ocorrenciaQuery($filters);

        return [
            'periodo' => $this->periodo($filters),
            'total' => (clone $query)->count('ocorrencias.id'),
            'por_tipo' => $this->groupCount((clone $query), 'ocorrencias.tipo'),
            'por_status' => $this->groupCount((clone $query), 'ocorrencias.status'),
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function sla(array $filters = []): array
    {
        $total = (clone $this->pedidoQuery($filters))->count('pedido.id_pedido');
        $foraPrazo = $this->atrasadosCount($filters);
        $noPrazo = max($total - $foraPrazo, 0);

        return [
            'periodo' => $this->periodo($filters),
            'total_avaliado' => $total,
            'no_prazo' => $noPrazo,
            'fora_prazo' => $foraPrazo,
            'no_prazo_percentual' => $this->percentual($noPrazo, $total),
            'fora_prazo_percentual' => $this->percentual($foraPrazo, $total),
            'principais_ofensores' => $this->principaisOfensoresAtraso($filters),
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function faturamento(array $filters = []): array
    {
        $query = $this->pedidoQuery($filters);

        return [
            'periodo' => $this->periodo($filters),
            'valor_total_frete' => $this->round((clone $query)->sum(DB::raw($this->valorFreteExpression())), 2),
            'evolucao_mensal' => $this->evolucaoMensal($filters)['faturamento'],
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function filiais(array $filters = []): array
    {
        $query = $this->pedidoQuery($filters)
            ->join('historico', 'historico.pedido_id_pedido', '=', 'pedido.id_pedido')
            ->join('rotas', 'rotas.id_rotas', '=', 'historico.rotas_id_rotas')
            ->leftJoin('centro_distribuicao as origem', 'origem.id_centro_distribuicao', '=', 'rotas.id_origem')
            ->selectRaw('origem.id_centro_distribuicao, origem.nome, origem.uf, count(distinct pedido.id_pedido) as pedidos, sum('.$this->pesoExpression().') as peso_total')
            ->whereNotNull('origem.id_centro_distribuicao')
            ->groupBy('origem.id_centro_distribuicao', 'origem.nome', 'origem.uf')
            ->orderByDesc('pedidos')
            ->limit(10);

        return [
            'periodo' => $this->periodo($filters),
            'filiais_com_maior_volume' => $query->get(),
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function evolucaoMensal(array $filters = []): array
    {
        $query = $this->pedidoQuery($filters)
            ->selectRaw("date_format(pedido.created_at, '%Y-%m') as mes")
            ->selectRaw('count(distinct pedido.id_pedido) as pedidos')
            ->selectRaw('sum('.$this->valorFreteExpression().') as faturamento')
            ->groupBy('mes')
            ->orderBy('mes');

        $dados = $query->get();

        return [
            'periodo' => $this->periodo($filters),
            'pedidos' => $dados->map(fn ($item) => [
                'mes' => $item->mes,
                'total' => (int) $item->pedidos,
            ]),
            'faturamento' => $dados->map(fn ($item) => [
                'mes' => $item->mes,
                'valor' => $this->round($item->faturamento, 2),
            ]),
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function snapshotPayload(array $filters = []): array
    {
        $resumo = $this->resumo($filters);

        return [
            'total_pedidos' => $resumo['pedidos']['total'],
            'pedidos_entregues' => $resumo['pedidos']['entregues'],
            'pedidos_atrasados' => $resumo['pedidos']['atrasados'],
            'pedidos_em_aberto' => $resumo['pedidos']['em_aberto'],
            'percentual_no_prazo' => $resumo['sla']['no_prazo_percentual'],
            'percentual_fora_prazo' => $resumo['sla']['fora_prazo_percentual'],
            'total_ocorrencias' => $resumo['ocorrencias']['total'],
            'valor_total_frete' => $resumo['financeiro']['valor_total_frete'],
            'peso_total' => $resumo['operacional']['peso_total'],
            'volumes_total' => $resumo['operacional']['volumes_total'],
            'tempo_medio_entrega_horas' => $resumo['operacional']['tempo_medio_entrega_horas'],
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function pedidoQuery(array $filters): Builder
    {
        $query = DB::table('pedido')
            ->leftJoin('notafiscal', 'notafiscal.id_notaFiscal', '=', 'pedido.id_notaFiscal');

        if (Schema::hasTable('fretes')) {
            $query->leftJoin('fretes', 'fretes.id_pedido', '=', 'pedido.id_pedido');
        }

        $this->applyPedidoFilters($query, $filters);

        return $query;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function rotaQuery(array $filters): Builder
    {
        $query = DB::table('rotas');

        if (! empty($filters['data_inicio'])) {
            $query->whereDate('rotas.data_rota', '>=', $filters['data_inicio']);
        }

        if (! empty($filters['data_fim'])) {
            $query->whereDate('rotas.data_rota', '<=', $filters['data_fim']);
        }

        if (! empty($filters['motorista_id'])) {
            $query->where('rotas.id_motorista', $filters['motorista_id']);
        }

        if (! empty($filters['veiculo_id'])) {
            $query->where('rotas.id_veiculo', $filters['veiculo_id']);
        }

        if (! empty($filters['status']) && Schema::hasColumn('rotas', 'status')) {
            $query->where('rotas.status', $filters['status']);
        }

        if (! empty($filters['rota_id'])) {
            $query->where('rotas.id_rotas', $filters['rota_id']);
        }

        if (! empty($filters['filial_id'])) {
            $query->where(function (Builder $query) use ($filters) {
                $query->where('rotas.id_origem', $filters['filial_id'])
                    ->orWhere('rotas.id_destino', $filters['filial_id']);
            });
        }

        return $query;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function ocorrenciaQuery(array $filters): Builder
    {
        $query = DB::table('ocorrencias')
            ->leftJoin('pedido', 'pedido.id_pedido', '=', 'ocorrencias.id_pedido')
            ->leftJoin('notafiscal', 'notafiscal.id_notaFiscal', '=', 'pedido.id_notaFiscal');

        if (! empty($filters['data_inicio'])) {
            $query->whereDate('ocorrencias.created_at', '>=', $filters['data_inicio']);
        }

        if (! empty($filters['data_fim'])) {
            $query->whereDate('ocorrencias.created_at', '<=', $filters['data_fim']);
        }

        if (! empty($filters['tipo_ocorrencia'])) {
            $query->where('ocorrencias.tipo', $filters['tipo_ocorrencia']);
        }

        if (! empty($filters['cliente_id'])) {
            $query->where(function (Builder $query) use ($filters) {
                $query->where('notafiscal.cliente_remetente', $filters['cliente_id'])
                    ->orWhere('notafiscal.cliente_destinatario', $filters['cliente_id']);
            });
        }

        if (! empty($filters['rota_id'])) {
            $query->where('ocorrencias.id_rotas', $filters['rota_id']);
        }

        return $query;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function historicoEntregaQuery(array $filters): Builder
    {
        $query = DB::table('historico')
            ->join('pedido', 'pedido.id_pedido', '=', 'historico.pedido_id_pedido')
            ->leftJoin('notafiscal', 'notafiscal.id_notaFiscal', '=', 'pedido.id_notaFiscal')
            ->where('historico.status', 'Entrega realizada');

        $this->applyPedidoFilters($query, $filters);

        return $query;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applyPedidoFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['data_inicio'])) {
            $query->whereDate('pedido.created_at', '>=', $filters['data_inicio']);
        }

        if (! empty($filters['data_fim'])) {
            $query->whereDate('pedido.created_at', '<=', $filters['data_fim']);
        }

        if (! empty($filters['cliente_id'])) {
            $query->where(function (Builder $query) use ($filters) {
                $query->where('notafiscal.cliente_remetente', $filters['cliente_id'])
                    ->orWhere('notafiscal.cliente_destinatario', $filters['cliente_id']);
            });
        }

        if (! empty($filters['status'])) {
            $query->where('pedido.status', $filters['status']);
        }

        if (! empty($filters['rota_id'])) {
            $query->whereExists(function (Builder $subquery) use ($filters) {
                $subquery->selectRaw('1')
                    ->from('historico')
                    ->whereColumn('historico.pedido_id_pedido', 'pedido.id_pedido')
                    ->where('historico.rotas_id_rotas', $filters['rota_id']);
            });
        }

        if (! empty($filters['motorista_id']) || ! empty($filters['veiculo_id']) || ! empty($filters['filial_id'])) {
            $query->whereExists(function (Builder $subquery) use ($filters) {
                $subquery->selectRaw('1')
                    ->from('historico')
                    ->join('rotas', 'rotas.id_rotas', '=', 'historico.rotas_id_rotas')
                    ->whereColumn('historico.pedido_id_pedido', 'pedido.id_pedido');

                if (! empty($filters['motorista_id'])) {
                    $subquery->where('rotas.id_motorista', $filters['motorista_id']);
                }

                if (! empty($filters['veiculo_id'])) {
                    $subquery->where('rotas.id_veiculo', $filters['veiculo_id']);
                }

                if (! empty($filters['filial_id'])) {
                    $subquery->where(function (Builder $subquery) use ($filters) {
                        $subquery->where('rotas.id_origem', $filters['filial_id'])
                            ->orWhere('rotas.id_destino', $filters['filial_id']);
                    });
                }
            });
        }
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function entreguesCount(array $filters): int
    {
        $query = $this->pedidoQuery($filters)
            ->where(function (Builder $query) {
                $query->whereIn('pedido.status', ['entregue', 'Entrega realizada'])
                    ->orWhereExists(function (Builder $subquery) {
                        $subquery->selectRaw('1')
                            ->from('historico')
                            ->whereColumn('historico.pedido_id_pedido', 'pedido.id_pedido')
                            ->where('historico.status', 'Entrega realizada');
                    });
            });

        return $query->count('pedido.id_pedido');
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function atrasadosCount(array $filters): int
    {
        if (! Schema::hasColumn('pedido', 'sla_previsto_em')) {
            return 0;
        }

        $query = $this->pedidoQuery($filters)
            ->whereNotNull('pedido.sla_previsto_em')
            ->where(function (Builder $query) {
                $query->where(function (Builder $query) {
                    $query->where('pedido.sla_previsto_em', '<', now())
                        ->whereNotExists(function (Builder $subquery) {
                            $subquery->selectRaw('1')
                                ->from('historico')
                                ->whereColumn('historico.pedido_id_pedido', 'pedido.id_pedido')
                                ->where('historico.status', 'Entrega realizada');
                        });
                })->orWhereExists(function (Builder $subquery) {
                    $subquery->selectRaw('1')
                        ->from('historico')
                        ->whereColumn('historico.pedido_id_pedido', 'pedido.id_pedido')
                        ->where('historico.status', 'Entrega realizada')
                        ->whereColumn('historico.data', '>', 'pedido.sla_previsto_em');
                });
            });

        return $query->count('pedido.id_pedido');
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function tempoMedioEntrega(array $filters): float
    {
        $subquery = $this->historicoEntregaQuery($filters)
            ->selectRaw('avg(timestampdiff(hour, pedido.created_at, historico.data)) as media_horas')
            ->value('media_horas');

        return $this->round($subquery, 2);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function principaisOfensoresAtraso(array $filters)
    {
        if (! Schema::hasColumn('pedido', 'sla_previsto_em')) {
            return collect();
        }

        return $this->pedidoQuery($filters)
            ->join('cliente as cliente_destinatario', 'cliente_destinatario.id_cliente', '=', 'notafiscal.cliente_destinatario')
            ->whereNotNull('pedido.sla_previsto_em')
            ->where('pedido.sla_previsto_em', '<', now())
            ->selectRaw('cliente_destinatario.id_cliente, cliente_destinatario.nome, count(distinct pedido.id_pedido) as atrasos')
            ->groupBy('cliente_destinatario.id_cliente', 'cliente_destinatario.nome')
            ->orderByDesc('atrasos')
            ->limit(5)
            ->get();
    }

    private function groupCount(Builder $query, string $column): array
    {
        return $query
            ->selectRaw($column.' as chave, count(*) as total')
            ->groupBy('chave')
            ->pluck('total', 'chave')
            ->map(fn ($total) => (int) $total)
            ->all();
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array{data_inicio: string|null, data_fim: string|null}
     */
    private function periodo(array $filters): array
    {
        return [
            'data_inicio' => $filters['data_inicio'] ?? null,
            'data_fim' => $filters['data_fim'] ?? null,
        ];
    }

    private function percentual(int $valor, int $total): float
    {
        if ($total === 0) {
            return 0.0;
        }

        return round(($valor / $total) * 100, 2);
    }

    private function round(mixed $value, int $precision): float
    {
        return round((float) ($value ?? 0), $precision);
    }

    private function pesoExpression(): string
    {
        if (Schema::hasColumn('pedido', 'peso')) {
            return 'coalesce(pedido.peso, notafiscal.peso, 0)';
        }

        return 'coalesce(notafiscal.peso, 0)';
    }

    private function valorFreteExpression(): string
    {
        if (Schema::hasColumn('fretes', 'valor') && Schema::hasColumn('pedido', 'valor')) {
            return 'coalesce(fretes.valor, pedido.valor, notafiscal.valor_total, 0)';
        }

        if (Schema::hasColumn('pedido', 'valor')) {
            return 'coalesce(pedido.valor, notafiscal.valor_total, 0)';
        }

        return 'coalesce(notafiscal.valor_total, 0)';
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array{data_inicio: string, data_fim: string}
     */
    public function filtrosPeriodoParaSnapshot(?string $dataReferencia = null, string $periodo = 'dia'): array
    {
        $referencia = CarbonImmutable::parse($dataReferencia ?? now());

        if ($periodo === 'mes') {
            return [
                'data_inicio' => $referencia->startOfMonth()->toDateString(),
                'data_fim' => $referencia->endOfMonth()->toDateString(),
            ];
        }

        return [
            'data_inicio' => $referencia->toDateString(),
            'data_fim' => $referencia->toDateString(),
        ];
    }
}
