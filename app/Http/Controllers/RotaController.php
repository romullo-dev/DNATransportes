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
        $rota =  Rota::with(['pedidos', 'motorista.usuario', 'veiculo', 'origem', 'destino', 'historicos'])->get();
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

        if ($rota->tipo === 'transferencia' && $request->filled('pedido_id_pedido')) {
            $pedido = Pedido::find($request->pedido_id_pedido);
            if ($pedido) {
                $pedido->status = 'Aguardando transferencia';
                $pedido->save();
            }
        }


        if ($request->filled('pedido_id_pedido')) {
            Historico::create([
                'rotas_id_rotas' => $rota->id_rotas,
                'pedido_id_pedido' => $request->pedido_id_pedido,
                'data' => $request->data_inicio,
                'status' => 'Aguardando liberação',
                'foto' => '',
            ]);
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
                'pedido_id_pedido' => 'nullable|integer',
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

            if ($request->filled('pedido_id_pedido')) {
                Historico::create([
                    'rotas_id_rotas' => $rota->id_rotas,
                    'pedido_id_pedido' => $request->pedido_id_pedido,
                    'data' => $request->data_inicio,
                    'status' => 'Aguardando liberação',
                    'foto' => '',
                ]);

                $pedido = Pedido::find($request->pedido_id_pedido);
                if ($pedido) {
                    $pedido->status = 'Em Separação';
                    $pedido->save();
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

        $data['data'] = \Carbon\Carbon::parse($data['data'])->format('Y-m-d H:i:s');

        $ultimoHistorico = Historico::where('rotas_id_rotas', $data['rotas_id_rotas'])
            ->orderBy('data', 'desc')
            ->first();

        if ($ultimoHistorico && $ultimoHistorico->status == 'Finalizado') {
            return redirect()->route('rotas.index')->with('error', 'Não é possível alterar a rota, pois o último histórico está como "Finalizado".');
        }

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('historicos', 'public');
            $data['foto'] = $path;
        } else {
            $data['foto'] = null;
        }

        $historico = Historico::create($data);

        $tipo = $data['tipo'];

        switch ($tipo) {
            case 'Transferencia':
                if ($data['status'] === 'Em trânsito') {
                    $pedido = Pedido::find($data['pedido_id_pedido']);
                    if ($pedido) {
                        $pedido->status = 'Em trânsito';
                        $pedido->save();
                    }
                } 
                elseif ($data['status'] === 'Finalizado') {
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
                } 
                elseif ($data['status'] === 'Finalizado') {
                    $pedido = Pedido::find($data['pedido_id_pedido']);
                    if ($pedido) {
                        $pedido->status = 'entregue';
                        $pedido->save();
                    }
                }
                break;

            default:
                return redirect()->route('rotas.index')->with('error', 'Lógica errada');
                break;
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
