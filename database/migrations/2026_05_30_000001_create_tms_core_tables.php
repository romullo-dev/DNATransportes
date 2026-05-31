<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('usuario')) {
            Schema::create('usuario', function (Blueprint $table) {
                $table->id('id_usuario');
                $table->string('nome');
                $table->string('user')->unique();
                $table->string('password');
                $table->string('tipo_usuario', 30)->index();
                $table->string('cpf', 20)->nullable()->unique();
                $table->string('status_funcionario', 30)->default('Ativo')->index();
                $table->string('email')->nullable()->unique();
                $table->string('telefone', 30)->nullable();
                $table->string('foto')->nullable();
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('modelo_veiculo')) {
            Schema::create('modelo_veiculo', function (Blueprint $table) {
                $table->id('id_modelo_veiculo');
                $table->string('marca');
                $table->string('modelo');
                $table->string('categoria', 80)->nullable()->index();
                $table->text('descricao')->nullable();
                $table->string('status', 30)->default('Ativo')->index();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('centro_distribuicao')) {
            Schema::create('centro_distribuicao', function (Blueprint $table) {
                $table->id('id_centro_distribuicao');
                $table->string('nome');
                $table->string('logradouro')->nullable();
                $table->string('bairro')->nullable();
                $table->string('cidade')->index();
                $table->string('uf', 2)->index();
                $table->string('cep', 12)->nullable();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->string('status', 30)->default('Ativo')->index();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('cliente')) {
            Schema::create('cliente', function (Blueprint $table) {
                $table->id('id_cliente');
                $table->string('nome');
                $table->string('documento', 20)->unique();
                $table->string('tipo', 40)->nullable()->index();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('cliente_contatos')) {
            Schema::create('cliente_contatos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_cliente')->constrained('cliente', 'id_cliente')->cascadeOnDelete();
                $table->string('nome');
                $table->string('email')->nullable();
                $table->string('telefone', 30)->nullable();
                $table->string('cargo', 80)->nullable();
                $table->boolean('principal')->default(false);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('endereco')) {
            Schema::create('endereco', function (Blueprint $table) {
                $table->id('id_endereco');
                $table->string('cep', 12)->index();
                $table->string('logradouro');
                $table->string('casa', 30)->nullable();
                $table->string('bairro')->nullable()->index();
                $table->string('cidade')->index();
                $table->string('uf', 2)->index();
                $table->text('observacao')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('produtos')) {
            Schema::create('produtos', function (Blueprint $table) {
                $table->id('id_produto');
                $table->string('nome');
                $table->string('sku', 80)->nullable()->index();
                $table->decimal('peso_kg', 12, 3)->nullable();
                $table->decimal('valor', 12, 2)->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('motorista')) {
            Schema::create('motorista', function (Blueprint $table) {
                $table->id('id_motorista');
                $table->string('cnh', 20)->unique();
                $table->string('categoria', 5)->index();
                $table->date('validade_cnh')->index();
                $table->foreignId('id_Usuario')->constrained('usuario', 'id_usuario')->restrictOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('veiculo')) {
            Schema::create('veiculo', function (Blueprint $table) {
                $table->id('id_Veiculo');
                $table->string('placa', 10)->unique();
                $table->unsignedSmallInteger('ano');
                $table->string('cor', 80);
                $table->string('status_veiculo', 30)->default('Ativo')->index();
                $table->foreignId('id_modelo_veiculo')->constrained('modelo_veiculo', 'id_modelo_veiculo')->restrictOnDelete();
                $table->string('renavam', 20)->nullable()->unique();
                $table->string('chassi', 40)->nullable()->unique();
                $table->decimal('tara_kg', 12, 3)->default(0);
                $table->decimal('pbt_kg', 12, 3)->default(0);
                $table->decimal('capacidade_kg', 12, 3)->nullable();
                $table->text('observacoes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('manutencoes_veiculos')) {
            Schema::create('manutencoes_veiculos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_Veiculo')->constrained('veiculo', 'id_Veiculo')->cascadeOnDelete();
                $table->string('tipo', 80);
                $table->date('data_manutencao');
                $table->date('proxima_manutencao')->nullable()->index();
                $table->decimal('valor', 12, 2)->nullable();
                $table->text('observacao')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('notafiscal')) {
            Schema::create('notafiscal', function (Blueprint $table) {
                $table->id('id_notaFiscal');
                $table->string('chave_acesso', 44)->unique();
                $table->unsignedInteger('numero_nfe')->index();
                $table->unsignedInteger('serie')->nullable();
                $table->dateTime('emissao')->index();
                $table->decimal('valor_total', 14, 2)->default(0);
                $table->decimal('peso', 12, 3)->nullable();
                $table->unsignedInteger('quantidade_volumes')->default(0);
                $table->foreignId('cliente_remetente')->constrained('cliente', 'id_cliente')->restrictOnDelete();
                $table->foreignId('cliente_destinatario')->constrained('cliente', 'id_cliente')->restrictOnDelete();
                $table->foreignId('endereco_remetente')->constrained('endereco', 'id_endereco')->restrictOnDelete();
                $table->foreignId('endereco_destinatario')->constrained('endereco', 'id_endereco')->restrictOnDelete();
                $table->foreignId('id_produto')->nullable()->constrained('produtos', 'id_produto')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('pedido')) {
            Schema::create('pedido', function (Blueprint $table) {
                $table->id('id_pedido');
                $table->string('codigo_rastreamento', 80)->unique();
                $table->foreignId('id_notaFiscal')->constrained('notafiscal', 'id_notaFiscal')->restrictOnDelete();
                $table->string('status', 80)->default('Aguardando coleta')->index();
                $table->dateTime('sla_previsto_em')->nullable()->index();
                $table->decimal('peso', 12, 3)->nullable();
                $table->decimal('volume', 12, 3)->nullable();
                $table->decimal('valor', 14, 2)->nullable();
                $table->string('foto')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('fretes')) {
            Schema::create('fretes', function (Blueprint $table) {
                $table->id('id_fretes');
                $table->foreignId('id_pedido')->constrained('pedido', 'id_pedido')->cascadeOnDelete();
                $table->decimal('valor', 14, 2)->nullable();
                $table->decimal('peso_cobrado', 12, 3)->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('rotas')) {
            Schema::create('rotas', function (Blueprint $table) {
                $table->id('id_rotas');
                $table->foreignId('id_motorista')->constrained('motorista', 'id_motorista')->restrictOnDelete();
                $table->foreignId('id_veiculo')->constrained('veiculo', 'id_Veiculo')->restrictOnDelete();
                $table->string('tipo', 40)->index();
                $table->decimal('distancia', 10, 2)->default(0);
                $table->dateTime('previsao')->nullable()->index();
                $table->dateTime('data_rota')->nullable()->index();
                $table->dateTime('data_inicio')->nullable();
                $table->dateTime('data_criacao')->nullable();
                $table->string('status', 80)->default('Planejada')->index();
                $table->text('observacoes')->nullable();
                $table->foreignId('id_origem')->nullable()->constrained('centro_distribuicao', 'id_centro_distribuicao')->nullOnDelete();
                $table->foreignId('id_destino')->nullable()->constrained('centro_distribuicao', 'id_centro_distribuicao')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('historico')) {
            Schema::create('historico', function (Blueprint $table) {
                $table->id('id_historico');
                $table->foreignId('rotas_id_rotas')->constrained('rotas', 'id_rotas')->cascadeOnDelete();
                $table->foreignId('pedido_id_pedido')->constrained('pedido', 'id_pedido')->cascadeOnDelete();
                $table->dateTime('data')->index();
                $table->string('status', 100)->index();
                $table->string('foto')->nullable();
                $table->string('local')->nullable();
                $table->text('descricao')->nullable();
                $table->text('observacao')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ocorrencias')) {
            Schema::create('ocorrencias', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_pedido')->constrained('pedido', 'id_pedido')->cascadeOnDelete();
                $table->foreignId('id_rotas')->nullable()->constrained('rotas', 'id_rotas')->nullOnDelete();
                $table->foreignId('id_historico')->nullable()->constrained('historico', 'id_historico')->nullOnDelete();
                $table->string('tipo', 60)->index();
                $table->string('status', 40)->default('Aberta')->index();
                $table->text('descricao');
                $table->dateTime('resolvida_em')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('comprovantes_entrega')) {
            Schema::create('comprovantes_entrega', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_pedido')->constrained('pedido', 'id_pedido')->cascadeOnDelete();
                $table->foreignId('id_historico')->nullable()->constrained('historico', 'id_historico')->nullOnDelete();
                $table->string('imagem')->nullable();
                $table->longText('assinatura')->nullable();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->dateTime('entregue_em')->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('comprovantes_entrega');
        Schema::dropIfExists('ocorrencias');
        Schema::dropIfExists('historico');
        Schema::dropIfExists('rotas');
        Schema::dropIfExists('fretes');
        Schema::dropIfExists('pedido');
        Schema::dropIfExists('notafiscal');
        Schema::dropIfExists('manutencoes_veiculos');
        Schema::dropIfExists('veiculo');
        Schema::dropIfExists('motorista');
        Schema::dropIfExists('produtos');
        Schema::dropIfExists('endereco');
        Schema::dropIfExists('cliente_contatos');
        Schema::dropIfExists('cliente');
        Schema::dropIfExists('centro_distribuicao');
        Schema::dropIfExists('modelo_veiculo');
        Schema::dropIfExists('usuario');
    }
};
