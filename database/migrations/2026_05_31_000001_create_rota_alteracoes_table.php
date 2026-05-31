<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rota_alteracoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rotas_id_rotas')->constrained('rotas', 'id_rotas')->cascadeOnDelete();
            $table->foreignId('id_usuario')->nullable()->constrained('usuario', 'id_usuario')->nullOnDelete();
            $table->text('motivo');
            $table->json('dados_anteriores')->nullable();
            $table->json('dados_novos')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rota_alteracoes');
    }
};
