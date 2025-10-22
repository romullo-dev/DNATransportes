<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Models\Usuario;
use Doctrine\DBAL\Schema\View;
use FFI\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{
    public function all ()
    {
        return Usuario::all();
    }

    public function store(UsuarioRequest $request)
    {
        try {

            $data = $request->only([
                'nome',
                'user',
                'tipo_usuario',
                'cpf',
                'status_funcionario',
                'email',
                'telefone'
            ]);

            $data['password'] = Hash::make($request->password);

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');

                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $file->move(public_path('usuarios'), $filename);

                $data['foto'] = $filename;
            }



            Usuario::create($data);

            return redirect()->back()->with('success', 'Usuário cadastrado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao cadastrar usuário.' . $e);
        }
    }

    public function read()
    {
        $usuarios = Usuario::orderBy('created_at', 'desc')->paginate(7);

        return view('User.user', compact('usuarios'));
    }

    public function procurar(Request $request)
    {
        $busca = $request->input('busca');
        $tipo = $request->input('tipo');
        $status = $request->input('status');

        $usuarios = Usuario::query();

        if ($busca) {
            $usuarios->where(function ($query) use ($busca) {
                $query->where('nome', 'like', "%{$busca}%")
                    ->orWhere('cpf', 'like', "%{$busca}%");
            });
        }

        if ($tipo) {
            $usuarios->where('tipo_usuario', $tipo);
        }

        if ($status) {
            $usuarios->where('status_funcionario', $status);
        }

        $usuarios = $usuarios->paginate(10);

        return view('User.user', compact('usuarios'));
    }



    public function update(Request $request, Usuario $usuario)
    {


        try {
            $data = $request->only(['nome', 'email', 'status_funcionario', 'tipo_usuario', 'telefone']);

            if ($request->hasFile('foto')) {
                if ($usuario->foto && file_exists(public_path('usuarios/' . $usuario->foto))) {
                    unlink(public_path('usuarios/' . $usuario->foto));
                }

                $file = $request->file('foto');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('usuarios'), $filename);
                $data['foto'] = $filename;
            }


            $usuario->update($data);

            return redirect()->route('read-user')->with('success', 'Usuário atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('read-user')->with('error', 'Erro ao atualizar o usuário: ');
        }
    }


    public function inserirFoto(Request $request, Usuario $usuario)
    {
        $request->validate([]);

        try {
            if ($request->hasFile('foto')) {
                if ($usuario->foto) {
                    Storage::disk('public')->delete($usuario->foto);
                }

                $data['foto'] = $request->file('foto')->store('usuarios', 'public');
            }

            $usuario->update($data);

            return back()->with('success', 'Foto atualizada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar o usuário: ');
        }
    }





    public function destroy($id_usuario)
    {
        try {
            $usuario = Usuario::findOrFail($id_usuario);
            $usuario->delete();
            return Redirect()->route('read-user')->with('success', 'Usuário deletado com sucesso!');
        } catch (Exception $e) {
            return back()->withInput()->with(
                'error',
                'Usuário não deletado'
            );
        }
    }

    public function show($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('User.ver', compact('usuario'));
    }

    public function senha($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('User.modais.senhas', compact('usuario'));
    }

    /**
     * Atualiza a senha do usuário.
     */
    public function updatePassword(Request $request, $id)
    {
        try {
            $user = Usuario::findOrFail($id);

            $request->validate([
                'current_password' => [
                    'required',
                    'string',
                    function ($attribute, $value, $fail) use ($user) {
                        if (!Hash::check($value, $user->password)) {
                            $fail('A senha atual está incorreta!');
                        }
                    },
                ],
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            $user->password = Hash::make($request->new_password);
            $user->save();

            return back()->with('success', 'Senha alterada com sucesso!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Usuário não encontrado.');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro ao atualizar a senha.' . $e);
        }
    }
}
