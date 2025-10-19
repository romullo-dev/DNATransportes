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
            // ✅ 1. Validação dos campos obrigatórios
            $validated = $request->validate([x'
            ]);

            // ✅ 2. Formatar a data
            $validated['data'] = Carbon::parse($validated['data'])->format('Y-m-d H:i:s');

            // ✅ 3. Verifica se o último histórico da rota já está finalizado
            $ultimoHistorico = Historico::where('rotas_id_rotas', $validated['rotas_id_rotas'])
                ->orderByDesc('data')
                ->first();

            if ($ultimoHistorico && $ultimoHistorico->status === 'Finalizado') {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta rota já foi finalizada, não é possível alterá-la.',
                ], 400);
            }

            // ✅ 4. Upload de foto (se enviada)
            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')->store('historicos', 'public');
            } else {
                $validated['foto'] = null;
            }

            // ✅ 5. Cria o novo histórico
            $historico = Historico::create($validated);

            // ✅ 6. Atualiza o status do pedido de acordo com o tipo de rota
            $pedido = Pedido::find($validated['pedido_id_pedido']);
            if (!$pedido) {
                return response()->json(['success' => false, 'message' => 'Pedido não encontrado.'], 404);
            }

            switch (strtolower($validated['tipo'])) {
                case 'coleta':
                    if ($validated['status'] === 'Em rota de coleta') {
                        $pedido->status = 'Em trânsito';
                    } elseif ($validated['status'] === 'Finalizado') {
                        $pedido->status = 'Coleta Finalizada';
                    }
                    break;

                case 'transferencia':
                    if ($validated['status'] === 'Em trânsito') {
                        $pedido->status = 'Em trânsito';
                    } elseif ($validated['status'] === 'Finalizado') {
                        $pedido->status = 'Transferência Finalizada';
                    }
                    break;

                case 'entrega':
                    if ($validated['status'] === 'Em trânsito') {
                        $pedido->status = 'Em rota de entrega';
                    } elseif ($validated['status'] === 'Finalizado') {
                        $pedido->status = 'Entregue';
                    }
                    break;
            }

            $pedido->save();

            // ✅ 7. Retorna sucesso com detalhes
            return response()->json([
                'success' => true,
                'message' => 'Histórico registrado e pedido atualizado com sucesso!',
                'data' => [
                    'historico' => $historico,
                    'pedido_status' => $pedido->status,
                ],
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação dos dados enviados.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno no servidor: ' . $th->getMessage(),
            ], 500);
        }
    }
}
