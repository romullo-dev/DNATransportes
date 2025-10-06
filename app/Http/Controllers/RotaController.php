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



        // Caso tenha chaves de nota enviadas
        if ($request->filled('chave_nota')) {
            // Limpa e prepara as chaves
            $chaves_nota = array_filter(array_map('trim', explode(',', $request->chave_nota)));

            // Busca todos os pedidos de uma vez só
            $pedidos = Pedido::whereHas('notaFiscal', function ($query) use ($chaves_nota) {
                $query->whereIn('chave_acesso', $chaves_nota);
            })->get();

            // Se quiser confirmar o resultado antes de criar os históricos:

            foreach ($pedidos as $pedido) {
                Historico::create([
                    'rotas_id_rotas' => $rota->id_rotas,
                    'pedido_id_pedido' => $pedido->id_pedido,
                    'status' => 'Em rota',
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
                                'status' => 'Em rota de entrega',
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
                                'status' => 'Em rota de coleta',
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
            return redirect()->route('rotas.index')->with('error', 'Erro ao cadastrar a rota de entrega: ' . $e->getMessage());
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

        // 1️⃣ Verifica se a rota já foi finalizada
        $ultimoHistorico = Historico::where('rotas_id_rotas', $data['rotas_id_rotas'])
            ->orderByDesc('data')
            ->first();

        if ($ultimoHistorico && $ultimoHistorico->status === 'Finalizado') {
            return redirect()->route('rotas.index')->with('error', 'Não é possível alterar a rota, pois o último histórico está como "Finalizado".');
        }

        // 2️⃣ Upload da foto (se existir)
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('historicos', 'public');
        } else {
            $data['foto'] = null;
        }

        // 3️⃣ Busca todos os pedidos associados à rota
        $pedidos = Historico::where('rotas_id_rotas', $data['rotas_id_rotas'])
            ->pluck('pedido_id_pedido')
            ->unique(); // evita duplicar pedidos repetidos na rota

        // 4️⃣ Cria um novo histórico para cada pedido da rota
        foreach ($pedidos as $pedidoId) {
            Historico::create([
                'rotas_id_rotas' => $data['rotas_id_rotas'],
                'pedido_id_pedido' => $pedidoId,
                'data' => $data['data'],
                'status' => $data['status'], // mantém o mesmo texto (case original)
                'foto' => $data['foto'],
                'observacao' => $data['observacao'] ?? null,
            ]);
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
