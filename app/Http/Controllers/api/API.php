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
        $credentials = $request->validate([
            'user' => ['required'],
            'password' => ['required'],
        ]);

        $usuario = Usuario::where('user', $credentials['user'])->first();

        if ($usuario && Hash::check($credentials['password'], $usuario->password)) {
            return response()->json([
                'success' => true,
                'user' => $usuario,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Credenciais inválidas',
        ], 401);
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
            // Validação dos dados
           

            $data = $request->all();



            // Convertendo a data para o formato adequado
            $data['data'] = Carbon::parse($data['data'])->format('Y-m-d H:i:s');

            // Verificar se o último histórico está finalizado
            $ultimoHistorico = Historico::where('rotas_id_rotas', $data['rotas_id_rotas'])
                ->orderBy('data', 'desc')
                ->first();

            if ($ultimoHistorico && $ultimoHistorico->status == 'Finalizado') {
                return response()->json(['error' => 'Não é possível alterar a rota, pois o último histórico está como "Finalizado".'], 400);
            }

            // Verificando se foi enviado uma foto
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('historicos', 'public');
                $data['foto'] = $path;
            } else {
                $data['foto'] = null;
            }

            // Criar o histórico
            $historico = Historico::create($data);

            // Lógica de atualização dos pedidos
            $tipo = $data['tipo'];
            switch ($tipo) {
                case 'Coleta':
                    if ($data['status'] === 'Em rota de coleta') {
                        $pedido = Pedido::find($data['pedido_id_pedido']);
                        if ($pedido) {
                            $pedido->status = 'Em trânsito';
                            $pedido->save();
                        }
                    } elseif ($data['status'] === 'Finalizado') {
                        $pedido = Pedido::find($data['pedido_id_pedido']);
                        if ($pedido) {
                            $pedido->status = 'Coleta Finalizada';
                            $pedido->save();
                        }
                    }
                    break;

                case 'transferencia':
                    if ($data['status'] === 'Em trânsito') {
                        $pedido = Pedido::find($data['pedido_id_pedido']);
                        if ($pedido) {
                            $pedido->status = 'Em trânsito';
                            $pedido->save();
                        }
                    } elseif ($data['status'] === 'Finalizado') {
                        $pedido = Pedido::find($data['pedido_id_pedido']);
                        if ($pedido) {
                            $pedido->status = 'Transferência Finalizada';
                            $pedido->save();
                        }
                    }
                    break;

                case 'Entrega':
                    if ($data['status'] === 'Em trânsito') {
                        $pedido = Pedido::find($data['pedido_id_pedido']);
                        if ($pedido) {
                            $pedido->status = 'Em rota entrega';
                            $pedido->save();
                        }
                    } elseif ($data['status'] === 'Finalizado') {
                        $pedido = Pedido::find($data['pedido_id_pedido']);
                        if ($pedido) {
                            $pedido->status = 'Entregue';
                            $pedido->save();
                        }
                    }
                    break;

                default:
                    return response()->json(['error' => 'Erro ao alterar a rota: tipo inválido'], 400);
            }

            return response()->json(['success' => 'Rota alterada com sucesso!'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao alterar a rota: ' . $e->getMessage()], 500);
        }
    }
}
