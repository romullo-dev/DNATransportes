<?php

namespace App\Http\Controllers;

use App\Http\Requests\MotoristaRequest;
use App\Models\Motorista;
use App\Models\Usuario;
use Exception;
use Illuminate\Http\Request;

class MotoristaController extends Controller
{
    public function index()
{
    $usuarios = Usuario::with('motorista')
        ->where('tipo_usuario', 'motorista')
        ->paginate(10);

    $usuariosSelect = Usuario::where('tipo_usuario', 'motorista')
        ->doesntHave('motorista')
        ->get();
        
    return view('motorista.index', compact('usuarios', 'usuariosSelect'));
}



    public function store(MotoristaRequest $request)
    {
        try {
            Motorista::create($request->validated());
            return redirect()->route('motorista.index')->with('success', 'Motorista criado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao cadastrar motorista.');
        }
    }

    public function show(string $id)
    {
        //
    }


    public function update(MotoristaRequest $request, Motorista $motorista)
    {
        try
        {
         $data = $request->only(['cnh', 'categoria', 'validade_cnh']);
         $motorista->update($data);

          return redirect()->route('motorista.index')->with('success', 'Usuário atualizado com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('motorista.index')->with('error', 'Erro ao atualizar o usuário: ');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
