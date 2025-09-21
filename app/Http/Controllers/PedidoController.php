<?php

namespace App\Http\Controllers;

use App\Models\NotaFiscal;
use App\Models\Pedido;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        $result = Pedido::with([
            'notaFiscal.remetente',
            'notaFiscal.destinatario',
            'notaFiscal.enderecoRemetente',
            'notaFiscal.enderecoDestinatario',
            'frete'
        ])->paginate(10);

        return view('pedido.index', compact('result'));
    }

    public function painel()
    {
        return view('painel.index');
    }




    public function rastreamento()
    {
        return view('rastreio.index');
    }

    public function show(Request $request)
    {
        try {
            $codigoRastreamento = $request->input('codigo_rastreamento');

            $pedido = Pedido::with([
                'historicos',
                'frete',
                'notaFiscal.remetente',
                'notaFiscal.destinatario',
                'notaFiscal.enderecoRemetente',
                'notaFiscal.enderecoDestinatario'
            ])
                ->where('codigo_rastreamento', $codigoRastreamento)
                ->first();

            if (!$pedido) {
                return redirect()->back()->with('error', 'Código de rastreio não encontrado.');
            }

            return view('rastreio.rastreio', compact('pedido'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro: ' . $e->getMessage());
        }
    }

    public function edit($id)
{
    // Carregar o pedido com todos os detalhes relacionados, incluindo históricos
    $pedido = Pedido::with([
        'notaFiscal.remetente',              // Remetente da nota fiscal
        'notaFiscal.destinatario',           // Destinatário da nota fiscal
        'notaFiscal.enderecoRemetente',      // Endereço do remetente
        'notaFiscal.enderecoDestinatario',   // Endereço do destinatário
        'frete',                             // Detalhes do frete
        'historicos',                        // Carregar históricos do pedido
        //'rotas',                             // Carregar rotas associadas ao pedido
        //'rotas.historicoPedidos',            // Carregar histórico de pedidos das rotas
    ])
    ->findOrFail($id);  // Garantir que o pedido existe, caso contrário, erro 404

    // Usando o dd() para depuração (remover quando finalizar)
    dd($pedido);

    // Recupera todas as rotas associadas a esse pedido
    $rotas = $pedido->rotas()->distinct()->get();


    // Retorna a view passando o pedido e as rotas
    return view('pedido.edit', compact('pedido', 'rotas'));
}




    public function update(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);

        if ($pedido->status != 'em_rota_entrega') {
            return redirect()->back()->with('error', 'O status só pode ser alterado quando a carga estiver em rota de entrega.');
        }

        $validated = $request->validate([
            'status' => 'required|in:em_preparo,no_centro_transferencia,em_transito,em_rota_entrega,entregue',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $pedido->status = $validated['status'];

        if ($request->hasFile('foto')) {

            $path = $request->file('foto')->store('public/fotos_pedidos');
            $pedido->foto = basename($path);
        }

        $pedido->save();

        return redirect()->route('pedidos.index')->with('success', 'Status do pedido atualizado com sucesso!');
    }
}
