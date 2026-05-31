<?php

namespace App\Http\Controllers\Api;

use App\DTOs\RegistrarHistoricoRotaData;
use App\Http\Controllers\Controller;
use App\Models\Rota;
use App\Models\Usuario;
use App\Services\ComprovanteEntregaService;
use App\Services\RotaHistoricoService;
use DomainException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class API extends Controller
{
    // LOGIN
    public function loginApi(Request $request)
    {
        try {
            $data = $request->validate(['user' => 'required', 'password' => 'required']);
            $user = Usuario::where('user', $data['user'])->first();

            if (! $user || ! Hash::check($data['password'], $user->password)) {
                return response()->json(['success' => false, 'message' => 'Usuário ou senha inválidos'], 401);
            }

            return response()->json([
                'success' => true,
                'user' => $user,
                'token' => Schema::hasTable('personal_access_tokens')
                    ? $user->createToken('dna-api')->plainTextToken
                    : null,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // TELA DE HOME
    public function index()
    {
        $rotas = Rota::with(['pedidos', 'motorista.usuario', 'veiculo', 'origem', 'destino', 'historicos'])->paginate(perPage: 5);

        return response()->json($rotas);
    }

    // EDITAR ROTA

    public function historico(
        Request $request,
        ComprovanteEntregaService $comprovantes,
        RotaHistoricoService $historicos,
    ) {
        try {
            $validated = $request->validate([
                'pedido_id_pedido' => 'required|integer|exists:pedido,id_pedido',
                'rotas_id_rotas' => 'required|integer|exists:rotas,id_rotas',
                'status' => ['required', Rule::in(['Em trânsito', 'Finalizado', 'Ocorrência'])],
                'tipo' => 'required|string',
                'observacao' => 'nullable|string|max:500',
                'foto' => 'nullable|file|image|max:4096',
            ]);

            $validated['data'] = now();
            $foto = $comprovantes->armazenar($request->file('foto'));
            $registros = $historicos->registrarMovimentacao(
                RegistrarHistoricoRotaData::fromArray($validated, $foto)
            );

            return response()->json([
                'success' => true,
                'message' => 'Histórico salvo e pedido atualizado com sucesso!',
                'total_registros' => $registros->count(),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação dos dados.',
                'errors' => $e->errors(),
            ], 422);
        } catch (DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (Throwable $th) {
            report($th);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno: '.$th->getMessage(),
            ], 500);
        }
    }
}
