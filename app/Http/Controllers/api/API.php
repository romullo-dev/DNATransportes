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
use function Symfony\Component\Clock\now;

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
            $validated = $request->validate([
                'pedido_id_pedido' => 'required|integer|exists:pedido,id_pedido',
                'rotas_id_rotas' => 'required|integer|exists:rotas,id_rotas',
                'status' => 'required|string|max:100',
                'tipo' => 'required|string',
                'observacao' => 'nullable|string|max:500',
                'foto' => 'nullable|file|image|max:4096',
            ]);

            $validated['data'] = now()->format('Y-m-d H:i:s');

            $data = $request;
            $tipo = $data->tipo;

            $pedidos = Historico::where('rotas_id_rotas', $data['rotas_id_rotas'])
                ->pluck('pedido_id_pedido')
                ->unique();

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('canhotos'), $filename);
                $validated['foto'] = $filename; // ✅ SALVA O NOME CERTO
            } else {
                $validated['foto'] = null;
            }


            $pedido = Pedido::find($validated['pedido_id_pedido']);

            switch ($tipo) {
                case 'Coleta':
                    if ($data['status'] === 'Em trânsito') {
                        foreach ($pedidos as $pedidoId) {
                            Historico::create([
                                'rotas_id_rotas' => $data['rotas_id_rotas'],
                                'pedido_id_pedido' => $pedidoId,
                                'data' => now(),
                                'status' => 'Em processo de coleta',
                                'foto' =>  $validated['foto'],
                                'observacao' => $data['observacao'] ?? null,
                            ]);
                        }
                    } elseif ($data['status'] === 'Finalizado') {
                        foreach ($pedidos as $pedidoId) {
                            Historico::create([
                                'rotas_id_rotas' => $data['rotas_id_rotas'],
                                'pedido_id_pedido' => $pedidoId,
                                'data' => now(),
                                'status' => 'Coleta realizada',
                                'foto' =>  $validated['foto'],
                                'observacao' => $data['observacao'] ?? null,
                            ]);
                        }
                    } elseif ($data['status'] === 'Ocorrência') {
                        foreach ($pedidos as $pedidoId) {
                            Historico::create([
                                'rotas_id_rotas' => $data['rotas_id_rotas'],
                                'pedido_id_pedido' => $pedidoId,
                                'data' => now(),
                                'status' => 'Coleta não realizada',
                                'foto' =>  $validated['foto'],
                                'observacao' => $data['observacao'] ?? null,
                            ]);
                        }
                    }
                    break;
                case 'Transferencia':
                    if ($data['status'] === 'Em trânsito') {
                        foreach ($pedidos as $pedidoId) {
                            Historico::create([
                                'rotas_id_rotas' => $data['rotas_id_rotas'],
                                'pedido_id_pedido' => $pedidoId,
                                'data' => now(),
                                'status' => 'Em processo de transferência',
                                'foto' =>  $validated['foto'],
                                'observacao' => $data['observacao'] ?? null,
                            ]);
                        }
                    } elseif ($data['status'] === 'Finalizado') {
                        foreach ($pedidos as $pedidoId) {
                            Historico::create([
                                'rotas_id_rotas' => $data['rotas_id_rotas'],
                                'pedido_id_pedido' => $pedidoId,
                                'data' => now(),
                                'status' => 'Transferência realizada',
                                'foto' =>  $validated['foto'],
                                'observacao' => $data['observacao'] ?? null,
                            ]);
                        }
                    } elseif ($data['status'] === 'Ocorrência') {
                        foreach ($pedidos as $pedidoId) {
                            Historico::create([
                                'rotas_id_rotas' => $data['rotas_id_rotas'],
                                'pedido_id_pedido' => $pedidoId,
                                'data' => now(),
                                'status' => 'Transferência não realizada',
                                'foto' =>  $validated['foto'],
                                'observacao' => $data['observacao'] ?? null,
                            ]);
                        }
                    }
                    break;
                case 'Entrega':
                    if ($data['status'] === 'Em trânsito') {
                        foreach ($pedidos as $pedidoId) {
                            Historico::create([
                                'rotas_id_rotas' => $data['rotas_id_rotas'],
                                'pedido_id_pedido' => $pedidoId,
                                'data' => now(),
                                'status' => 'Em rota de entrega',
                                'foto' =>  $validated['foto'],
                                'observacao' => $data['observacao'] ?? null,
                            ]);
                        }
                    } elseif ($data['status'] === 'Finalizado') {
                        foreach ($pedidos as $pedidoId) {
                            Historico::create([
                                'rotas_id_rotas' => $data['rotas_id_rotas'],
                                'pedido_id_pedido' => $pedidoId,
                                'data' => now(),
                                'status' => 'Entrega realizada',
                                'foto' =>  $validated['foto'],
                                'observacao' => $data['observacao'] ?? null,
                            ]);
                        }
                    } elseif ($data['status'] === 'Ocorrência') {
                        foreach ($pedidos as $pedidoId) {
                            Historico::create([
                                'rotas_id_rotas' => $data['rotas_id_rotas'],
                                'pedido_id_pedido' => $pedidoId,
                                'data' => now(),
                                'status' => 'Entrega não realizada',
                                'foto' =>  $validated['foto'],
                                'observacao' => $data['observacao'] ?? null,
                            ]);
                        }
                    }
                    break;
            }
            return response()->json([
                'success' => true,
                'message' => 'Histórico salvo e pedido atualizado com sucesso!'
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
