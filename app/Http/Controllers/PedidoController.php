<?php

namespace App\Http\Controllers;

use App\Models\NotaFiscal;
use App\Models\Pedido;
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


}
