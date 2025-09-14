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

    public function rastreamento()
    {
        return view('rastreio.index');
    }

    public function show(Request $request)
    {
        try {
            $codigo = trim($request->input('codigo_rastreamento')); // pega do POST

            $pedido = Pedido::with('historicos') // pega todos os dados do pedido e histórico
                ->where('codigo_rastreamento', $codigo)
                ->first();

            /*if (!$pedido) {
            return redirect()->back()->with('error', 'Código de rastreio não encontrado.');
        }*/

            return view('rastreio.rastreio', compact('pedido'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro: ' . $e->getMessage());
        }
    }


    public function edit($id)
    {
        $pedido = Pedido::with([
            'notaFiscal.remetente',
            'notaFiscal.destinatario',
            'notaFiscal.enderecoRemetente',
            'notaFiscal.enderecoDestinatario',
            'frete',
            'rotas.historicos',
            'rotas'
        ])
            ->findOrFail($id);

        $rotas = $pedido->rotas->unique('id_rotas');

        return view('pedido.edit', compact('pedido', 'rotas'));
    }

    public function update(Request $request, $id)
    {
        // Encontrar o pedido pelo ID
        $pedido = Pedido::findOrFail($id);

        // Verificar se o status atual do pedido permite a atualização
        if ($pedido->status != 'em_rota_entrega') {
            // Se o status não for 'em_rota_entrega', não permite alteração
            return redirect()->back()->with('error', 'O status só pode ser alterado quando a carga estiver em rota de entrega.');
        }

        // Validar os dados recebidos
        $validated = $request->validate([
            'status' => 'required|in:em_preparo,no_centro_transferencia,em_transito,em_rota_entrega,entregue',  // Pode adicionar mais status aqui
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validação para a foto
        ]);

        // Atualizando o status do pedido
        $pedido->status = $validated['status'];

        // Verificar se há foto e atualizar
        if ($request->hasFile('foto')) {
            // Se o pedido já tem uma foto, excluímos a antiga

            // Armazenar a nova foto
            $path = $request->file('foto')->store('public/fotos_pedidos');
            $pedido->foto = basename($path);
        }

        // Salvar a atualização do pedido
        $pedido->save();

        // Retornar ao usuário com sucesso
        return redirect()->route('pedidos.index')->with('success', 'Status do pedido atualizado com sucesso!');
    }
}
