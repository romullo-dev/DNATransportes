<?php

namespace App\Http\Controllers;

use App\Models\CentroDistribuicao;
use Doctrine\DBAL\Schema\View;
use Illuminate\Http\Request;

class CentroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = CentroDistribuicao::all();
        return View('centro.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'nome' => 'required|string',
                'cidade' => 'required|string',
                'uf' => 'required|string',
                'latitude' => 'required|string',
                'longitude' => 'required|string',
                'status' => 'required|string',
                'logradouro' => 'required|string',
                'cep' => 'required|string|max:8',
                'bairro' => 'required|string'
            ]);

            CentroDistribuicao::create($data);
            return redirect()->back()->with('success', 'Centro de distribuição cadastrado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('centro.index')->with('error', 'Erro ao cadastrar ');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CentroDistribuicao $centroDistribuicao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CentroDistribuicao $centroDistribuicao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CentroDistribuicao $centroDistribuicao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CentroDistribuicao $centroDistribuicao)
    {
        //
    }
}
