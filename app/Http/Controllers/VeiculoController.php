<?php

namespace App\Http\Controllers;

use App\Http\Requests\VeiculoRequest;
use App\Models\ModeloVeiculo;
use App\Models\Veiculo;
use Exception;
use Illuminate\Http\Request;
use Log;

class VeiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modelos = Veiculo::with('modelo_veiculo')->paginate(10);
        $modeloSelect = ModeloVeiculo::all();

        return view('veiculo.veiculo.index', compact('modelos', 'modeloSelect'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VeiculoRequest $request)
    {

        try {
            $data = $request->validated();
            Veiculo::create($data);
            return redirect()->back()->with('success', 'Veículo cadastrado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao cadastrar veículo.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ModeloVeiculo $modeloVeiculo)
    {
        //
    }

    public function update(Request $request, $id)
    {
        try {
            $veiculo = Veiculo::findOrFail($id);

            $validated = $request->validate([
                'ano' => 'required|integer|between:1900,2099',
                'cor' => 'required|string|max:50',
                'status_veiculo' => 'required|string',
                'id_modelo_veiculo' => 'required|exists:modelo_veiculo,id_modelo_veiculo',
                'tara_kg' => 'required|numeric|min:0',
                'pbt_kg' => 'required|numeric|gte:tara_kg',
                'observacoes' => 'nullable|string|max:5000',
            ]);

            $validated['placa'] = $veiculo->placa;
            $validated['renavam'] = $veiculo->renavam;
            $validated['chassi'] = $veiculo->chassi;

            $veiculo->update($validated);

            return redirect()->back()->with('success', "✅ Veículo {$veiculo->placa} atualizado com sucesso!");
        } catch (Exception $e) {
            return redirect()->back()->with('error', '❌ Ocorreu um erro ao atualizar o veículo. Verifique os dados e tente novamente.');
        }
    }

    public function destroy($id)
    {
        try {
            $veiculo = Veiculo::findOrFail($id);
            $veiculo->delete();
            return back()->with('success', "🗑️ Veículo {$veiculo->placa} excluído com sucesso!");
        } catch (Exception $e) {
            Log::error('Erro ao excluir veículo: ' . $e->getMessage());
            return back()->with('error', '❌ Não foi possível excluir o veículo.');
        }
    }
}
