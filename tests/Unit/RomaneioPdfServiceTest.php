<?php

namespace Tests\Unit;

use App\Models\CentroDistribuicao;
use App\Models\Cliente;
use App\Models\ModeloVeiculo;
use App\Models\Motorista;
use App\Models\NotaFiscal;
use App\Models\Pedido;
use App\Models\Rota;
use App\Models\Usuario;
use App\Models\Veiculo;
use App\Services\RomaneioPdfService;
use DomainException;
use Illuminate\Support\Collection;
use Tests\TestCase;

class RomaneioPdfServiceTest extends TestCase
{
    public function test_gera_pdf_do_romaneio(): void
    {
        $pdf = app(RomaneioPdfService::class)->gerar($this->rotaCompleta());

        $this->assertStringStartsWith('%PDF-1.4', $pdf);
        $this->assertStringContainsString('%%EOF', $pdf);
    }

    public function test_bloqueia_romaneio_sem_nota_fiscal(): void
    {
        $rota = $this->rotaCompleta();
        $rota->setRelation('pedidos', new Collection);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('nota fiscal vinculada');

        app(RomaneioPdfService::class)->gerar($rota);
    }

    private function rotaCompleta(): Rota
    {
        $origem = new CentroDistribuicao(['nome' => 'CD Matriz', 'cidade' => 'São Paulo', 'uf' => 'SP']);
        $destino = new CentroDistribuicao(['nome' => 'CD Rio', 'cidade' => 'Rio de Janeiro', 'uf' => 'RJ']);
        $modelo = new ModeloVeiculo(['marca' => 'Mercedes-Benz', 'modelo' => 'Atego', 'categoria' => 'Truck']);
        $veiculo = new Veiculo(['placa' => 'ABC1D23', 'capacidade_kg' => 12000]);
        $veiculo->setRelation('modeloVeiculo', $modelo);

        $usuario = new Usuario(['nome' => 'Motorista Teste', 'cpf' => '12345678900']);
        $motorista = new Motorista(['cnh' => '99999999999', 'categoria' => 'D']);
        $motorista->setRelation('usuario', $usuario);

        $remetente = new Cliente(['nome' => 'Cliente Remetente']);
        $destinatario = new Cliente(['nome' => 'Cliente Destinatário']);
        $nota = new NotaFiscal([
            'chave_acesso' => str_repeat('1', 44),
            'numero_nfe' => 123,
            'serie' => 1,
            'emissao' => now(),
            'peso' => 100.5,
            'quantidade_volumes' => 3,
            'valor_total' => 1500.25,
        ]);
        $nota->id_notaFiscal = 1;
        $nota->setRelation('remetente', $remetente);
        $nota->setRelation('destinatario', $destinatario);

        $pedido = new Pedido;
        $pedido->id_pedido = 1;
        $pedido->setRelation('notaFiscal', $nota);

        $rota = new Rota([
            'status' => 'Planejada',
            'tipo' => 'entrega',
            'data_criacao' => now(),
            'data_inicio' => now(),
            'previsao' => now()->addDay(),
        ]);
        $rota->id_rotas = 77;
        $rota->setRelation('origem', $origem);
        $rota->setRelation('destino', $destino);
        $rota->setRelation('veiculo', $veiculo);
        $rota->setRelation('motorista', $motorista);
        $rota->setRelation('pedidos', new Collection([$pedido]));

        return $rota;
    }
}
