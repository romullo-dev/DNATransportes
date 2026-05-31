<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('analytics_snapshots')) {
            Schema::create('analytics_snapshots', function (Blueprint $table) {
                $table->id();
                $table->date('data_referencia')->unique();
                $table->unsignedInteger('total_pedidos')->default(0);
                $table->unsignedInteger('pedidos_entregues')->default(0);
                $table->unsignedInteger('pedidos_atrasados')->default(0);
                $table->unsignedInteger('pedidos_em_aberto')->default(0);
                $table->decimal('percentual_no_prazo', 5, 2)->default(0);
                $table->decimal('percentual_fora_prazo', 5, 2)->default(0);
                $table->unsignedInteger('total_ocorrencias')->default(0);
                $table->decimal('valor_total_frete', 12, 2)->default(0);
                $table->decimal('peso_total', 12, 3)->default(0);
                $table->unsignedInteger('volumes_total')->default(0);
                $table->decimal('tempo_medio_entrega_horas', 10, 2)->default(0);
                $table->timestamps();
            });
        }

        $this->addIndexIfMissing('pedido', 'status', 'analytics_pedido_status_idx');
        $this->addIndexIfMissing('pedido', 'created_at', 'analytics_pedido_created_at_idx');
        $this->addIndexIfMissing('pedido', 'sla_previsto_em', 'analytics_pedido_sla_idx');
        $this->addIndexIfMissing('pedido', 'data_entrega', 'analytics_pedido_data_entrega_idx');
        $this->addIndexIfMissing('rotas', 'status', 'analytics_rotas_status_idx');
        $this->addIndexIfMissing('rotas', 'id_motorista', 'analytics_rotas_motorista_idx');
        $this->addIndexIfMissing('ocorrencias', 'tipo', 'analytics_ocorrencias_tipo_idx');
        $this->addIndexIfMissing('ocorrencias', 'created_at', 'analytics_ocorrencias_created_at_idx');
        $this->addIndexIfMissing('notafiscal', 'emissao', 'analytics_notafiscal_emissao_idx');
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_snapshots');
    }

    private function addIndexIfMissing(string $table, string $column, string $index): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
            return;
        }

        if ($this->hasIndexOnColumn($table, $column)) {
            return;
        }

        Schema::table($table, function (Blueprint $table) use ($column, $index) {
            $table->index($column, $index);
        });
    }

    private function hasIndexOnColumn(string $table, string $column): bool
    {
        try {
            $result = DB::selectOne(
                'select count(*) as total from information_schema.statistics where table_schema = database() and table_name = ? and column_name = ? and index_name <> ?',
                [$table, $column, 'PRIMARY'],
            );

            return (int) ($result->total ?? 0) > 0;
        } catch (Throwable) {
            return true;
        }
    }
};
