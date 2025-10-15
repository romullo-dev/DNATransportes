<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModeloVeiculoRequest;
use App\Models\ModeloVeiculo;
use Doctrine\DBAL\Schema\View;
use Exception;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //View ('veiculo.veiculo.index');
        return view('veiculo.modelo.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(ModeloVeiculoRequest $request)
    {
        try {
            $data = $request->validated();
            ModeloVeiculo::create($data);
            return redirect()->back()->with('success', 'Modelo cadastrado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao cadastrar modelo de veiculo.'.$e);
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
