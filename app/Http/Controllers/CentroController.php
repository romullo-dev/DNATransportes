<?php

namespace App\Http\Controllers;

use App\Models\CentroDistribuicao;
use Doctrine\DBAL\Schema\View;
use Exception;
use Illuminate\Http\Request;
use Log;

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
    public function create()
    {
    }

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
    public function update(Request $request, $id)
    {
        try {
            $centro = CentroDistribuicao::findOrFail($id);

            $validated = $request->validate([
                'nome' => 'required|string|max:100',
                'cep' => 'required|string|min:8|max:9',
                'cidade' => 'required|string|max:100',
                'uf' => 'required|string|max:2',
                'status' => 'required|string|in:Ativo,Inativo',
            ]);

            $centro->update($validated);

            return redirect()->back()->with('success', "✅ Centro <strong>{$centro->nome}</strong> atualizado com sucesso!");
        } catch (Exception $e) {
            Log::error('Erro ao atualizar centro: ' . $e->getMessage());
            return redirect()->back()->with('error', '❌ Ocorreu um erro ao atualizar o centro. Verifique os dados.');
        }
    }

    /**
     * Visualizar detalhes de um centro.
     */
    public function show($id)
    {
        $centro = CentroDistribuicao::findOrFail($id);
        return response()->json($centro);
    }

    /**
     * Excluir centro.
     */
    public function destroy($id)
    {
        try {
            $centro = CentroDistribuicao::findOrFail($id);
            $centro->delete();
            return redirect()->back()->with('success', "✅ Centro <strong>{$centro->nome}</strong> excluído com sucesso!");
        } catch (Exception $e) {
            Log::error('Erro ao excluir centro: ' . $e->getMessage());
            return redirect()->back()->with('error', '❌ Erro ao excluir o centro. Ele pode estar relacionado a outros registros.');
        }
    }
}
