<?php

namespace App\Console\Commands;

use App\Models\AnalyticsSnapshot;
use App\Services\AnalyticsService;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class GerarAnalyticsSnapshotCommand extends Command
{
    protected $signature = 'analytics:gerar-snapshot {--data= : Data de referência em Y-m-d} {--periodo=dia : dia ou mes}';

    protected $description = 'Gera ou atualiza um snapshot consolidado de indicadores analíticos.';

    public function handle(AnalyticsService $analytics): int
    {
        $periodo = strtolower((string) $this->option('periodo'));

        if (! in_array($periodo, ['dia', 'mes'], true)) {
            $this->error('O período deve ser dia ou mes.');

            return self::FAILURE;
        }

        $dataReferencia = CarbonImmutable::parse($this->option('data') ?: now());
        $dataSnapshot = $periodo === 'mes'
            ? $dataReferencia->startOfMonth()->toDateString()
            : $dataReferencia->toDateString();

        $payload = $analytics->snapshotPayload(
            $analytics->filtrosPeriodoParaSnapshot($dataSnapshot, $periodo)
        );

        AnalyticsSnapshot::updateOrCreate(
            ['data_referencia' => $dataSnapshot],
            $payload,
        );

        $this->info("Snapshot analítico gerado para {$dataSnapshot}.");

        return self::SUCCESS;
    }
}
