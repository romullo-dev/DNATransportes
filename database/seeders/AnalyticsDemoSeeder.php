<?php

namespace Database\Seeders;

use App\Models\CentroDistribuicao;
use App\Models\Cliente;
use App\Models\ComprovanteEntrega;
use App\Models\Endereco;
use App\Models\Frete;
use App\Models\Historico;
use App\Models\ModeloVeiculo;
use App\Models\Motorista;
use App\Models\NotaFiscal;
use App\Models\Ocorrencia;
use App\Models\Pedido;
use App\Models\Rota;
use App\Models\Usuario;
use App\Models\Veiculo;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AnalyticsDemoSeeder extends Seeder
{
    public function run(): void
    {
        $requiredTables = [
            'usuario',
            'cliente',
            'endereco',
            'motorista',
            'modelo_veiculo',
            'veiculo',
            'centro_distribuicao',
            'notafiscal',
            'pedido',
            'fretes',
            'rotas',
            'historico',
        ];

        foreach ($requiredTables as $table) {
            if (! Schema::hasTable($table)) {
                $this->command?->warn("Tabela {$table} não encontrada. Rode as migrations antes de popular os dados analíticos.");

                return;
            }
        }

        DB::transaction(function () {
            $this->criarUsuariosAnaliticos();
            $clientes = $this->criarClientes();
            $enderecos = $this->criarEnderecos();
            $centros = $this->criarCentrosDistribuicao();
            $motoristas = $this->criarMotoristas();
            $veiculos = $this->criarVeiculos();
            $pedidos = $this->criarPedidos($clientes, $enderecos);
            $rotas = $this->criarRotas($centros, $motoristas, $veiculos);
            $this->criarHistoricos($pedidos, $rotas);
            $this->criarOcorrencias($pedidos, $rotas);
            $this->criarComprovantes($pedidos);
        });

        $this->command?->info('Dados demo de analytics criados/atualizados com sucesso.');
    }

    private function criarUsuariosAnaliticos(): void
    {
        foreach ([
            ['nome' => 'Admin Analytics', 'user' => 'admin_analytics', 'tipo_usuario' => 'admin'],
            ['nome' => 'Operador Analytics', 'user' => 'operador_analytics', 'tipo_usuario' => 'operador'],
        ] as $usuario) {
            Usuario::updateOrCreate(
                ['user' => $usuario['user']],
                [
                    'nome' => $usuario['nome'],
                    'password' => Hash::make('password'),
                    'tipo_usuario' => $usuario['tipo_usuario'],
                    'cpf' => $usuario['user'] === 'admin_analytics' ? '90000000001' : '90000000002',
                    'status_funcionario' => 'Ativo',
                    'email' => $usuario['user'].'@dnatransportes.demo',
                    'telefone' => '40000000',
                ],
            );
        }
    }

    private function criarClientes()
    {
        return collect(range(1, 10))->map(function (int $index) {
            return Cliente::updateOrCreate(
                ['documento' => '900000000000'.str_pad((string) $index, 2, '0', STR_PAD_LEFT)],
                [
                    'nome' => 'Cliente Demo '.$index,
                    'tipo' => $index <= 5 ? 'emitente' : 'destinatário',
                ],
            );
        });
    }

    private function criarEnderecos()
    {
        return collect(range(1, 12))->map(function (int $index) {
            return Endereco::firstOrCreate(
                [
                    'cep' => '0100'.str_pad((string) $index, 4, '0', STR_PAD_LEFT),
                    'logradouro' => 'Rua Demo '.$index,
                    'casa' => (string) (100 + $index),
                    'cidade' => ['São Paulo', 'Campinas', 'Santos', 'Guarulhos'][$index % 4],
                    'uf' => 'SP',
                ],
                [
                    'observacao' => 'Endereço demo para analytics',
                    'bairro' => 'Bairro Demo',
                ],
            );
        });
    }

    private function criarCentrosDistribuicao()
    {
        return collect(range(1, 5))->map(function (int $index) {
            return CentroDistribuicao::updateOrCreate(
                ['nome' => 'CD Demo '.$index],
                [
                    'cidade' => ['São Paulo', 'Campinas', 'Ribeirão Preto', 'Sorocaba', 'Santos'][$index - 1],
                    'uf' => 'SP',
                    'latitude' => -23.5 + ($index / 10),
                    'longitude' => -46.6 + ($index / 10),
                    'status' => 'Ativo',
                    'logradouro' => 'Avenida Logística '.$index,
                    'cep' => '0200'.str_pad((string) $index, 4, '0', STR_PAD_LEFT),
                    'bairro' => 'Distrito Industrial',
                ],
            );
        });
    }

    private function criarMotoristas()
    {
        return collect(range(1, 8))->map(function (int $index) {
            $usuario = Usuario::updateOrCreate(
                ['user' => 'motorista_demo_'.$index],
                [
                    'nome' => 'Motorista Demo '.$index,
                    'password' => Hash::make('password'),
                    'tipo_usuario' => 'motorista',
                    'cpf' => '9100000000'.$index,
                    'status_funcionario' => 'Ativo',
                    'email' => 'motorista.demo.'.$index.'@dnatransportes.demo',
                    'telefone' => '410000'.str_pad((string) $index, 2, '0', STR_PAD_LEFT),
                ],
            );

            return Motorista::updateOrCreate(
                ['id_Usuario' => $usuario->id_usuario],
                [
                    'cnh' => '9200000000'.$index,
                    'categoria' => ['C', 'D', 'E'][$index % 3],
                    'validade_cnh' => now()->addYears(2 + ($index % 3))->toDateString(),
                ],
            );
        });
    }

    private function criarVeiculos()
    {
        $modelos = collect([
            ['marca' => 'Mercedes-Benz', 'modelo' => 'Atego 2426', 'categoria' => 'Truck'],
            ['marca' => 'Volkswagen', 'modelo' => 'Delivery 11.180', 'categoria' => 'Urbano'],
            ['marca' => 'Volvo', 'modelo' => 'VM 270', 'categoria' => 'Rodoviário'],
        ])->map(fn (array $modelo) => ModeloVeiculo::updateOrCreate(
            ['marca' => $modelo['marca'], 'modelo' => $modelo['modelo']],
            ['categoria' => $modelo['categoria'], 'descricao' => 'Modelo demo analytics', 'status' => 'Ativo'],
        ));

        return collect(range(1, 12))->map(function (int $index) use ($modelos) {
            return Veiculo::updateOrCreate(
                ['placa' => 'DNA'.str_pad((string) $index, 4, '0', STR_PAD_LEFT)],
                [
                    'ano' => 2018 + ($index % 8),
                    'cor' => ['Branco', 'Prata', 'Azul'][$index % 3],
                    'status_veiculo' => $index % 6 === 0 ? 'Inativo' : 'Ativo',
                    'observacoes' => 'Veículo demo analytics',
                    'id_modelo_veiculo' => $modelos[$index % $modelos->count()]->id_modelo_veiculo,
                    'renavam' => '930000000'.str_pad((string) $index, 2, '0', STR_PAD_LEFT),
                    'chassi' => '9BDNADEMO'.str_pad((string) $index, 8, '0', STR_PAD_LEFT),
                    'tara_kg' => 4000 + ($index * 100),
                    'pbt_kg' => 12000 + ($index * 250),
                ],
            );
        });
    }

    private function criarPedidos($clientes, $enderecos)
    {
        return collect(range(1, 80))->map(function (int $index) use ($clientes, $enderecos) {
            $emissao = CarbonImmutable::now()->subDays(120 - $index);
            $entregue = $index <= 45;
            $atrasado = ($index > 35 && $index <= 45) || ($index > 70);
            $status = $entregue ? 'entregue' : ($index <= 70 ? 'em rota entrega' : 'em preparo');

            $nota = NotaFiscal::updateOrCreate(
                ['chave_acesso' => '35260530'.str_pad((string) $index, 36, '0', STR_PAD_LEFT)],
                [
                    'numero_nfe' => 7000 + $index,
                    'serie' => 1,
                    'emissao' => $emissao,
                    'valor_total' => 800 + ($index * 137.45),
                    'peso' => 80 + ($index * 9.75),
                    'quantidade_volumes' => 2 + ($index % 12),
                    'cliente_remetente' => $clientes[$index % $clientes->count()]->id_cliente,
                    'cliente_destinatario' => $clientes[($index + 3) % $clientes->count()]->id_cliente,
                    'endereco_remetente' => $enderecos[$index % $enderecos->count()]->id_endereco,
                    'endereco_destinatario' => $enderecos[($index + 5) % $enderecos->count()]->id_endereco,
                ],
            );

            $pedidoPayload = [
                'codigo_rastreamento' => 'dna_demo_'.str_pad((string) $index, 4, '0', STR_PAD_LEFT),
                'status' => $status,
                'created_at' => $emissao,
                'updated_at' => now(),
            ];

            foreach ([
                'sla_previsto_em' => $atrasado ? $emissao->addHours(24) : $emissao->addHours(72),
                'peso' => 80 + ($index * 9.75),
                'volume' => 1 + ($index % 20),
                'valor' => 450 + ($index * 42.5),
            ] as $column => $value) {
                if (Schema::hasColumn('pedido', $column)) {
                    $pedidoPayload[$column] = $value;
                }
            }

            $pedido = Pedido::updateOrCreate(
                ['id_notaFiscal' => $nota->id_notaFiscal],
                $pedidoPayload,
            );

            $fretePayload = ['id_pedido' => $pedido->id_pedido];

            if (Schema::hasColumn('fretes', 'valor')) {
                $fretePayload['valor'] = 450 + ($index * 42.5);
            }

            if (Schema::hasColumn('fretes', 'peso_cobrado')) {
                $fretePayload['peso_cobrado'] = 80 + ($index * 9.75);
            }

            Frete::updateOrCreate(['id_pedido' => $pedido->id_pedido], $fretePayload);

            return $pedido;
        });
    }

    private function criarRotas($centros, $motoristas, $veiculos)
    {
        return collect(range(1, 20))->map(function (int $index) use ($centros, $motoristas, $veiculos) {
            $inicio = CarbonImmutable::now()->subDays(90 - ($index * 3));

            $payload = [
                'id_motorista' => $motoristas[$index % $motoristas->count()]->id_motorista,
                'id_veiculo' => $veiculos[$index % $veiculos->count()]->id_Veiculo,
                'tipo' => ['coleta', 'transferencia', 'entrega'][$index % 3],
                'distancia' => 35 + ($index * 18.5),
                'previsao' => $inicio->addHours(8),
                'data_rota' => $inicio,
                'data_inicio' => $inicio,
                'data_criacao' => $inicio->subHour(),
                'id_origem' => $centros[$index % $centros->count()]->id_centro_distribuicao,
                'id_destino' => $centros[($index + 1) % $centros->count()]->id_centro_distribuicao,
            ];

            if (Schema::hasColumn('rotas', 'status')) {
                $payload['status'] = $index <= 14 ? 'Finalizada' : 'Em andamento';
            }

            return Rota::updateOrCreate(
                ['observacoes' => 'Rota demo analytics '.$index],
                $payload,
            );
        });
    }

    private function criarHistoricos($pedidos, $rotas): void
    {
        foreach ($pedidos as $index => $pedido) {
            $rota = $rotas[$index % $rotas->count()];
            $base = CarbonImmutable::parse($pedido->created_at);
            $entregue = $index < 45;
            $atrasado = ($index >= 35 && $index < 45) || ($index >= 70);

            foreach ([
                ['status' => 'Aguardando coleta', 'data' => $base->addHours(1)],
                ['status' => $index % 3 === 0 ? 'Em processo de transferência' : 'Em rota de entrega', 'data' => $base->addHours(12)],
            ] as $historico) {
                Historico::firstOrCreate([
                    'rotas_id_rotas' => $rota->id_rotas,
                    'pedido_id_pedido' => $pedido->id_pedido,
                    'status' => $historico['status'],
                    'data' => $historico['data'],
                ], [
                    'foto' => '',
                    'observacao' => 'Histórico demo analytics',
                ]);
            }

            if ($entregue) {
                Historico::firstOrCreate([
                    'rotas_id_rotas' => $rota->id_rotas,
                    'pedido_id_pedido' => $pedido->id_pedido,
                    'status' => 'Entrega realizada',
                    'data' => $base->addHours($atrasado ? 96 : 48),
                ], [
                    'foto' => '',
                    'observacao' => $atrasado ? 'Entrega demo fora do prazo' : 'Entrega demo no prazo',
                ]);
            }
        }
    }

    private function criarOcorrencias($pedidos, $rotas): void
    {
        if (! Schema::hasTable('ocorrencias')) {
            return;
        }

        $tipos = ['atraso', 'avaria', 'extravio', 'reentrega'];

        foreach (range(1, 30) as $index) {
            $pedido = $pedidos[($index * 2) % $pedidos->count()];
            $rota = $rotas[$index % $rotas->count()];
            $tipo = $tipos[$index % count($tipos)];

            Ocorrencia::updateOrCreate(
                [
                    'id_pedido' => $pedido->id_pedido,
                    'tipo' => $tipo,
                    'descricao' => 'Ocorrência demo analytics '.$index,
                ],
                [
                    'id_rotas' => $rota->id_rotas,
                    'id_historico' => null,
                    'status' => $index % 4 === 0 ? 'Resolvida' : 'Aberta',
                    'resolvida_em' => $index % 4 === 0 ? now()->subDays($index % 10) : null,
                    'created_at' => now()->subDays($index * 2),
                    'updated_at' => now(),
                ],
            );
        }
    }

    private function criarComprovantes($pedidos): void
    {
        if (! Schema::hasTable('comprovantes_entrega')) {
            return;
        }

        foreach ($pedidos->take(45) as $pedido) {
            $historico = Historico::where('pedido_id_pedido', $pedido->id_pedido)
                ->where('status', 'Entrega realizada')
                ->first();

            ComprovanteEntrega::updateOrCreate(
                ['id_pedido' => $pedido->id_pedido],
                [
                    'id_historico' => $historico?->id_historico,
                    'imagem' => null,
                    'assinatura' => 'Assinatura demo',
                    'latitude' => -23.55052,
                    'longitude' => -46.633308,
                    'entregue_em' => $historico?->data ?? now(),
                ],
            );
        }
    }
}
