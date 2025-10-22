<?php

namespace App\Http\Controllers;

use App\Models\Historico;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HistoricoController extends Controller
{

    public function store(Request $request)
    {
        try {

            $tipo = $request->tipo;


            //dd($request);


            //dd($tipo);

            if ($tipo === 'Entrega') {
                $request->validate([]);

                $pedido = Pedido::findOrFail($request->pedido_id_pedido);

                // 📸 upload da foto (opcional)
                $fotoPath = null;
                if ($request->hasFile('foto')) {
                    $fotoPath = $request->file('foto')->store('historicos', 'public');
                }

                Historico::create([
                    'pedido_id_pedido' => $pedido->id_pedido,
                    'rotas_id_rotas' => $request->rotas_id_rotas,
                    'status' => $request->status,
                    'observacao' => $request->observacao,
                    'foto' => $fotoPath,
                    'data' => $request->data,
                ]);

                return redirect()->route('rotas.show', ['rotas' => $request->rotas_id_rotas])
                    ->with('success', 'Histórico criado com sucesso!');
            } else {
                return back()->with('error', 'Atualização bloqueada — apenas notas de entrega estão disponíveis para alteração.');
            }
        } catch (\Throwable $th) {
            return back()->with('error', 'Erro ao salvar histórico: ');
        }
    }
}
