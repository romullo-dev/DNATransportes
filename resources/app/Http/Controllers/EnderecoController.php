<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use Doctrine\DBAL\Schema\View;
use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = Endereco::paginate(10);
        return View('ajuste.endereco.index', compact('result'));
    }

    public function show(Endereco $endereco)
    {
        //
    }

    public function update(Request $request, $id_endereco)
    {
        $request->validate([
            'logradouro' => 'required|string',
            'bairro' => 'required|string',
            'cidade' => 'required|string',
            'uf' => 'required|string|max:2',  
            'cep' => 'required|string|max:8', 
            'casa' => 'required|string',
            'observacao' => 'nullable|string',
            'longitude' => 'nullable|string',
            'latitude' => 'nullable|string',
        ]);

        $endereco = Endereco::findOrFail($id_endereco);

        $endereco->logradouro = $request->logradouro;
        $endereco->bairro = $request->bairro;
        $endereco->cidade = $request->cidade;
        $endereco->uf = $request->uf;
        $endereco->cep = $request->cep;
        $endereco->casa = $request->casa;
        $endereco->observacao = $request->observacao;
        $endereco->longitude = $request->longitude;
        $endereco->latitude = $request->latitude;

        $endereco->save();

        return redirect()->route('endereco.index')->with('success', 'Endereço atualizado com sucesso!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Endereco $endereco)
    {
        //
    }
}
