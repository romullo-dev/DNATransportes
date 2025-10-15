<?php

namespace App\Http\Controllers;

use App\Http\Requests\VeiculoRequest;
use App\Models\ModeloVeiculo;
use App\Models\Veiculo;
use Illuminate\Http\Request;

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
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao cadastrar veículo.' . $e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ModeloVeiculo $modeloVeiculo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ModeloVeiculo $modeloVeiculo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ModeloVeiculo $modeloVeiculo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModeloVeiculo $modeloVeiculo)
    {
        //
    }
}
