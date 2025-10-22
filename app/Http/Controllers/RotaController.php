<?php

namespace App\Http\Controllers;

use App\Http\Requests\RotaRequest;
use App\Models\CentroDistribuicao;
use App\Models\Historico;
use App\Models\Motorista;
use App\Models\Pedido;
use App\Models\Rota;
use App\Models\Veiculo;
use Doctrine\DBAL\Schema\View;
use Illuminate\Http\Request;


class RotaController extends Controller
{
    public function index()
    {
        $rota = Rota::with(['pedidos', 'motorista.usuario', 'veiculo', 'origem', 'destino', 'historicos'])->get();
        return View('rotas.index', compact('rota'));
    }

    public function create()
    {
        $centros = CentroDistribuicao::where('status', 'Ativo')->get();
        $motoristas = Motorista::with('usuario')->get();
        $veiculos = Veiculo::where('status_veiculo', 'Ativo')->get();

        $pedido = Pedido::all();

        return View('rotas.create', compact('centros', 'pedido', 'motoristas', 'veiculos'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string',
            'id_origem' => 'required|integer',
            'id_destino' => 'required|integer',
            'distancia' => 'required|numeric',
            'previsao' => 'required|date',
            'data_inicio' => 'required|date',
            'id_motorista' => 'required|integer',
            'id_veiculo' => 'required|integer',
            'observacoes' => 'nullable|string',
            'pedido_id_pedido' => 'nullable|integer',
            'chave_nota' => 'nullable|string',
        ]);

        // Cria a rota
        $rota = new Rota();
        $rota->tipo = $request->tipo;
        $rota->id_origem = $request->id_origem;
        $rota->id_destino = $request->id_destino;
        $rota->distancia = $request->distancia;
        $rota->previsao = $request->previsao;
        $rota->data_rota = $request->data_inicio;
        $rota->data_inicio = $request->data_inicio;
        $rota->data_criacao = now();
        $rota->id_motorista = $request->id_motorista;
        $rota->id_veiculo = $request->id_veiculo;
        $rota->observacoes = $request->observacoes ?? '';
        $rota->save();


        if ($request->filled('chave_nota')) {

            $chaves_nota = array_filter(array_map('trim', explode(',', $request->chave_nota)));

            $pedidos = Pedido::whereHas('notaFiscal', function ($query) use ($chaves_nota) {
                $query->whereIn('chave_acesso', $chaves_nota);
            })->get();

            foreach ($pedidos as $pedido) {
                Historico::create([
                    'rotas_id_rotas' => $rota->id_rotas,
                    'pedido_id_pedido' => $pedido->id_pedido,
                    'status' => 'Aguardando transferência',
                    'data' => now(),
                ]);

                $pedido->save();
            }
        }

        return redirect()->route('rotas.index')->with('success', 'Rota cadastrada com sucesso!');
    }


    public function store_entrega(Request $request)
    {
        try {
            $request->validate([
                'tipo' => 'required|string',
                'id_origem' => 'required|integer',
                'distancia' => 'required|numeric',
                'previsao' => 'required|date',
                'data_inicio' => 'required|date',
                'id_motorista' => 'required|integer',
                'id_veiculo' => 'required|integer',
                'observacoes' => 'nullable|string',
                'chave_nota' => 'nullable|string',
            ]);

            $rota = new Rota();
            $rota->tipo = $request->tipo;
            $rota->id_origem = $request->id_origem;
            $rota->id_destino = $request->id_origem;
            $rota->distancia = $request->distancia;
            $rota->previsao = $request->previsao;
            $rota->data_rota = $request->data_inicio;
            $rota->data_inicio = $request->data_inicio;
            $rota->data_criacao = now();
            $rota->id_motorista = $request->id_motorista;
            $rota->id_veiculo = $request->id_veiculo;
            $rota->observacoes = $request->observacoes ?? '';
            $rota->save();

            if ($request->filled('chave_nota')) {
                $chaves_nota = array_filter(array_map('trim', explode(',', $request->chave_nota)));

                $pedidos = Pedido::whereHas('notaFiscal', function ($query) use ($chaves_nota) {
                    $query->whereIn('chave_acesso', $chaves_nota);
                })->get();

                switch ($rota->tipo) {
                    case 'entrega':
                        foreach ($pedidos as $pedido) {
                            Historico::create([
                                'rotas_id_rotas' => $rota->id_rotas,
                                'pedido_id_pedido' => $pedido->id_pedido,
                                'data' => now(),
                                'status' => 'Em processo de separação no destino',
                                'foto' => '',
                                'observacao' => $request->observacoes ?? '',
                            ]);
                        }
                        break;

                    case 'coleta':
                        foreach ($pedidos as $pedido) {
                            Historico::create([
                                'rotas_id_rotas' => $rota->id_rotas,
                                'pedido_id_pedido' => $pedido->id_pedido,
                                'data' => now(),
                                'status' => 'Aguardando coleta',
                                'foto' => '',
                                'observacao' => $request->observacoes ?? '',
                            ]);
                        }
                        break;

                    default:
                        return redirect()->route('rotas.index')->with('error', 'Erro ao cadastrar a rota de entrega: ');
                }
            }

            return redirect()->route('rotas.index')->with('success', 'Rota de entrega cadastrada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('rotas.index')->with('error', 'Erro ao cadastrar a rota de entrega: ');
        }
    }




    public function show(Rota $rotas)
    {
        $data = $rotas;
        return view('rotas.show', [
            'data' => $data,
            'mapboxToken' => env('MAPBOX_ACCESS_TOKEN')
        ]);
    }

    public function historico(RotaRequest $request)
    {
        try {
            $data = $request->validated();
            $data['data'] = \Carbon\Carbon::parse($data['data'])->format('Y-m-d H:i:s');
            $tipo = $data['tipo'];


            $ultimoHistorico = Historico::where('rotas_id_rotas', $data['rotas_id_rotas'])
                ->orderByDesc('data')
                ->first();


            if (
                $ultimoHistorico &&
                in_array($ultimoHistorico->status, [
                    'Coleta realizada',
                    'Transferência realizada',
                    'Entrega realizada'
                ])
            ) {
                return redirect()->route('rotas.index')
                    ->with('error', 'Ops! Esta rota já foi finalizada, portanto não é possível realizar alterações.');
            }



            if ($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto')->store('historicos', 'public');
            } else {
                $data['foto'] = null;
            }

            $pedidos = Historico::where('rotas_id_rotas', $data['rotas_id_rotas'])
                ->pluck('pedido_id_pedido')
                ->unique();


            if ($request->hasFile('foto')) {
                $file = $request->file('foto');

                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $file->move(public_path('canhotos'), $filename);

                $data['foto'] = $filename;
            }

            switch ($tipo) {
                case 'Coleta':
                    if ($data['status'] === 'Em trânsito') {
                        foreach ($pedidos as $pedidoId) {
                            Historico::create([
                                'rotas_id_rotas' => $data['rotas_id_rotas'],
                                'pedido_id_pedido' => $pedidoId,
                                'data' => $data['data'],
                                'status' => 'Em processo de coleta',
                                'foto' => $data['foto'],
                                'observacao' => $data['observacao'] ?? null,
                            ]);
                        }
                    } elseif ($data['status'] === 'Finalizado') {
                        foreach ($pedidos as $pedidoId) {
                            Historico::create([
                                'rotas_id_rotas' => $data['rotas_id_rotas'],
                                'pedido_id_pedido' => $pedidoId,
                                'data' => $data['data'],
                                'status' => 'Coleta realizada',
                                'foto' => $data['foto'],
                                'observacao' => $data['observacao'] ?? null,
                            ]);
                        }
                    } elseif ($data['status'] === 'Ocorrência') {
                        foreach ($pedidos as $pedidoId) {
                            Historico::create([
                                'rotas_id_rotas' => $data['rotas_id_rotas'],
                                'pedido_id_pedido' => $pedidoId,
                                'data' => $data['data'],
                                'status' => 'Coleta não realizada',
                                'foto' => $data['foto'],
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
                                'data' => $data['data'],
                                'status' => 'Em processo de transferência',
                                'foto' => $data['foto'],
                                'observacao' => $data['observacao'] ?? null,
                            ]);
                        }
                    } elseif ($data['status'] === 'Finalizado') {
                        foreach ($pedidos as $pedidoId) {
                            Historico::create([
                                'rotas_id_rotas' => $data['rotas_id_rotas'],
                                'pedido_id_pedido' => $pedidoId,
                                'data' => $data['data'],
                                'status' => 'Transferência realizada',
                                'foto' => $data['foto'],
                                'observacao' => $data['observacao'] ?? null,
                            ]);
                        }
                    } elseif ($data['status'] === 'Ocorrência') {
                        foreach ($pedidos as $pedidoId) {
                            Historico::create([
                                'rotas_id_rotas' => $data['rotas_id_rotas'],
                                'pedido_id_pedido' => $pedidoId,
                                'data' => $data['data'],
                                'status' => 'Transferência não realizada',
                                'foto' => $data['foto'],
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
                                'data' => $data['data'],
                                'status' => 'Em rota de entrega',
                                'foto' => $data['foto'],
                                'observacao' => $data['observacao'] ?? null,
                            ]);
                        }
                    } elseif ($data['status'] === 'Finalizado') {
                        foreach ($pedidos as $pedidoId) {
                            Historico::create([
                                'rotas_id_rotas' => $data['rotas_id_rotas'],
                                'pedido_id_pedido' => $pedidoId,
                                'data' => $data['data'],
                                'status' => 'Entrega realizada',
                                'foto' => $data['foto'],
                                'observacao' => $data['observacao'] ?? null,
                            ]);
                        }
                    } elseif ($data['status'] === 'Ocorrência') {
                        foreach ($pedidos as $pedidoId) {
                            Historico::create([
                                'rotas_id_rotas' => $data['rotas_id_rotas'],
                                'pedido_id_pedido' => $pedidoId,
                                'data' => $data['data'],
                                'status' => 'Entrega não realizada',
                                'foto' => $data['foto'],
                                'observacao' => $data['observacao'] ?? null,
                            ]);
                        }
                    }
                    break;
                default:
                    return redirect()->route('rotas.index')->with('error', 'Erro ao alterar a rota: ');
            }

            return redirect()->route('rotas.index')->with('success', 'Rota alterada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('rotas.index')->with('error', 'Erro ao alterar a rota: ');
        }
    }

    public function destroy(Rota $rota)
    {
        //
    }
}
