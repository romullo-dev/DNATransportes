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
        // 📦 Total de pedidos
        $totalPedidos = Pedido::count();

        // 🕓 Pega o último status de cada pedido
        $statusPedidos = Pedido::with([
            'historicos' => function ($q) {
                $q->latest('data');
            }
        ])->get()->map(function ($pedido) {
            $status = optional($pedido->historicos->first())->status ?? 'Sem status';
            return trim(strtolower($status)); // 🔥 normaliza pra minúsculo e remove espaços
        });


        // Conta quantos há de cada tipo de status
        $resumoStatus = collect($statusPedidos)->countBy();

        $statusEntregue = $resumoStatus->get('entrega realizada', 0);

        $statusTransito = $resumoStatus->filter(function ($value, $key) {
            return in_array($key, [
                'em processo de coleta',
                'em processo de transferência',
                'em rota de entrega',
                'Transferência realizada',
                'Aguardando coleta',
                'Coleta realizada'
            ]);
        })->sum();

        $statusCancelado = $resumoStatus->filter(function ($value, $key) {
            return in_array($key, [
                'coleta não realizada',
                'transferência não realizada',
                'entrega não realizada',
            ]);
        })->sum();

        $statusOutros = $totalPedidos - ($statusEntregue + $statusTransito + $statusCancelado);

        // 📅 Pedidos criados nos últimos 6 meses
        $meses = collect();
        $dadosPedidos = collect();

        for ($i = 5; $i >= 0; $i--) {
            $data = \Carbon\Carbon::now()->subMonths($i);
            $mes = ucfirst($data->translatedFormat('M'));

            $quantidade = Pedido::whereMonth('created_at', $data->month)
                ->whereYear('created_at', $data->year)
                ->count();

            $meses->push($mes);
            $dadosPedidos->push($quantidade);
        }

        //dd($resumoStatus);

        // 🔁 Retorna a view com os dados
        return view('painel.index', compact(
            'totalPedidos',
            'statusEntregue',
            'statusTransito',
            'statusCancelado',
            'statusOutros',
            'meses',
            'dadosPedidos'
        ));
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
            return redirect()->back()->with('error', 'Erro: ');
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
        ])
            ->findOrFail($id);

        $rotas = $pedido->rotas->unique('id_rotas');

        return view('pedido.edit', compact('pedido', 'rotas'));
    }

    public function update(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);

        if ($pedido->status != 'em rota entrega') {
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
