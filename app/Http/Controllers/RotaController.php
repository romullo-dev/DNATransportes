<?php

namespace App\Http\Controllers;

use App\Http\Requests\RotaRequest;
use App\Models\CentroDistribuicao;
use App\Models\Historico;
use App\Models\HistoricoPedido;
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
        $rota = Rota::with([/*'pedidos',*/'motorista.usuario', 'veiculo', 'origem', 'destino', 'historicos'])
            ->paginate(5);

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
            'chave_nota' => 'nullable|string',
        ]);

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

        $historico = Historico::create([
            'rotas_id_rotas' => $rota->id_rotas,
            'data' => $request->data_inicio,
            'status' => 'Aguardando liberação',
            'foto' => '',
            'observacao' => $request->observacoes ?? '',
        ]);

        if ($request->filled('chave_nota')) {
            $chaves_nota = explode(',', $request->chave_nota);

            foreach ($chaves_nota as $chave) {
                $chave = trim($chave);

                $pedidos = Pedido::whereHas('notaFiscal', function ($query) use ($chave) {
                    $query->where('chave_acesso', $chave);
                })->get();

                foreach ($pedidos as $pedido) {
                    HistoricoPedido::create([
                        'id_pedido' => $pedido->id_pedido,
                        'historico_rotas_id_historico' => $historico->id_historico,
                        'status' => '2',
                    ]);
                }
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

            $historico = Historico::create([
                'rotas_id_rotas' => $rota->id_rotas,
                'data' => $request->data_inicio,
                'status' => 'Aguardando liberação',
                'foto' => '',
                'observacao' => $request->observacoes ?? '',
            ]);

            if ($request->filled('chave_nota')) {
                $chaves_nota = explode(',', $request->chave_nota);

                foreach ($chaves_nota as $chave) {
                    $chave = trim($chave);

                    $pedidos = Pedido::whereHas('notaFiscal', function ($query) use ($chave) {
                        $query->where('chave_acesso', $chave);
                    })->get();

                    foreach ($pedidos as $pedido) {

                        switch ($rota->tipo) {
                            case 'coleta':
                                HistoricoPedido::create([
                                    'id_pedido' => $pedido->id_pedido,
                                    'historico_rotas_id_historico' => $historico->id_historico,
                                    'status' => '1',
                                ]);
                                break;


                            case 'entrega':
                                HistoricoPedido::create([
                                    'id_pedido' => $pedido->id_pedido,
                                    'historico_rotas_id_historico' => $historico->id_historico,
                                    'status' => '3',
                                ]);
                                break;

                            default:
                                return redirect()->route('rotas.index')->with('error', 'Erro ao cadastrar a rota: ');
                                break;
                        }
                    }
                }
            }

            return redirect()->route('rotas.index')->with('success', 'Rota cadastrada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('rotas.index')->with('error', 'Erro ao cadastrar a rota: ' . $e->getMessage());
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
            $rota = Rota::findOrFail($data['rotas_id_rotas']); 

            $historico_pedidos = $rota->historicoPedidos;

            if ($historico_pedidos->isEmpty()) {
                return redirect()->route('rotas.index')->with('error', 'Não há históricos de pedidos associados a esta rota.');
            }
           $ultimoHistorico = Historico::where('rotas_id_rotas', $data['rotas_id_rotas'])
                ->orderBy('data', 'desc')
                ->first();
            if ($ultimoHistorico && $ultimoHistorico->status == 'Finalizado') {
                return redirect()->route('rotas.index')->with('error', 'Não é possível alterar a rota finalizada.');
            }

            $data['data'] = \Carbon\Carbon::parse($data['data'])->format('Y-m-d H:i:s');
            $historico = Historico::create($data); 

            foreach ($historico_pedidos as $historicoPedido) {
                $pedido = $historicoPedido->pedido; 

                if (!$pedido) {
                    continue; 
                }

                switch ($data['tipo']) {
                    case 'Coleta':
                        if ($data['status'] === 'Em trânsito') {
                            $status = 'Coleta em andamento';
                        } elseif ($data['status'] === 'Finalizado') {
                            $status = 'Coleta finalizada'; 
                        }
                        break;

                    case 'Transferencia':
                        if ($data['status'] === 'Em trânsito') {
                            $status = 'Transferência em andamento';
                        } elseif ($data['status'] === 'Finalizado') {
                            $status = 'Transferência finalizada'; 
                        }
                        break;

                    case 'Entrega':
                        if ($data['status'] === 'Em trânsito') {
                            $status = 'Entrega em andamento'; 
                        } elseif ($data['status'] === 'Finalizado') {
                            $status = 'Entrega finalizada'; 
                        }
                        break;

                    default:
                        return redirect()->route('rotas.index')->with('error', 'Erro ao alterar a rota: Tipo inválido.');
                }

                $historicoPedidoExistente = HistoricoPedido::where('id_pedido', $pedido->id_pedido)
                    ->where('historico_rotas_id_historico', $historico->id_historico)
                    ->first();

                if ($historicoPedidoExistente) {
                    $historicoPedidoExistente->status = $status;
                    $historicoPedidoExistente->save();
                } else {
                    HistoricoPedido::create([
                        'id_pedido' => $pedido->id_pedido,
                        'historico_rotas_id_historico' => $historico->id_historico, 
                        'status' => $status, 
                    ]);
                }
            }

            return redirect()->route('rotas.index')->with('success', 'Rota alterada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('rotas.index')->with('error', 'Erro ao alterar a rota: ' . $e->getMessage());
        }
    }

    public function destroy(Rota $rota)
    {
        //
    }
}
