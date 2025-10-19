<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Historico;
use App\Models\Motorista;
use App\Models\Pedido;
use App\Models\Rota;
use App\Models\Usuario;
use Carbon\Carbon;
use Dotenv\Validator;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class API extends Controller
{
    //LOGIN
    public function loginApi(Request $request)
    {
        try {
            $data = $request->validate(['user' => 'required', 'password' => 'required']);
            $user = Usuario::where('user', $data['user'])->first();

            if (!$user || !Hash::check($data['password'], $user->password)) {
                return response()->json(['success' => false, 'message' => 'Usuário ou senha inválidos'], 401);
            }

            return response()->json(['success' => true, 'user' => $user]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    //TELA DE HOME
    public function index()
    {
        $rotas = Rota::with(['pedidos', 'motorista.usuario', 'veiculo', 'origem', 'destino', 'historicos'])->paginate(perPage: 5);

        return response()->json($rotas);
    }

    //EDITAR ROTA


    public function historico(Request $request)
{
    try {
        // ✅ Validação
        $validated = $request->validate([
            'pedido_id_pedido' => 'required|integer|exists:pedido,id_pedido',
            'rotas_id_rotas' => 'required|integer|exists:rotas,id_rotas',
            'status' => 'required|string|max:100',
            'tipo' => 'required|string',
            'data' => 'required|date',
            'observacao' => 'nullable|string|max:500',
            'foto' => 'nullable|file|image|max:4096',
        ]);

        $validated['data'] = \Carbon\Carbon::parse($validated['data'])->format('Y-m-d H:i:s');

        // ✅ Upload da imagem
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('historicos', 'public');
        }

        // ✅ Cria o histórico
        $historico = \App\Models\Historico::create($validated);

        // ✅ Atualiza status do pedido
        $pedido = \App\Models\Pedido::find($validated['pedido_id_pedido']);
        if ($pedido) {
            if ($validated['status'] === 'Finalizado') {
                $pedido->status = match (strtolower($validated['tipo'])) {
                    'coleta' => 'Coleta Finalizada',
                    'transferencia' => 'Transferência Finalizada',
                    'entrega' => 'Entregue',
                    default => $pedido->status,
                };
            } elseif ($validated['status'] === 'Em trânsito') {
                $pedido->status = 'Em trânsito';
            }
            $pedido->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Histórico salvo e pedido atualizado com sucesso!',
            'data' => $historico,
        ], 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro de validação dos dados.',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Throwable $th) {
        return response()->json([
            'success' => false,
            'message' => 'Erro interno: ' . $th->getMessage(),
        ], 500);
    }
}

}
