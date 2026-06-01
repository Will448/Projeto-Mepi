<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Auditoria;

class SenhaController extends Controller
{
    public function edit()
    {
        return view('senha.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'senha_atual'           => ['required'],
            'nova_senha'            => ['required', 'min:6', 'confirmed'],
        ], [
            'nova_senha.confirmed' => 'A confirmação da nova senha não confere.',
            'nova_senha.min'       => 'A nova senha deve ter ao menos 6 caracteres.',
        ]);

        $user = auth()->user();

        // Verifica se a senha atual está correta
        if (!Hash::check($request->senha_atual, $user->password)) {
            return back()->withErrors(['senha_atual' => 'Senha atual incorreta.']);
        }

        // Impede reutilizar a mesma senha
        if (Hash::check($request->nova_senha, $user->password)) {
            return back()->withErrors(['nova_senha' => 'A nova senha não pode ser igual à atual.']);
        }

        $user->update(['password' => Hash::make($request->nova_senha)]);

        // Registra na auditoria
        Auditoria::registrar('updated', 'User', $user->id,
            null, null, "Usuário \"{$user->name}\" alterou a própria senha."
        );

        return redirect()
            ->back()
            ->with('success', 'Senha alterada com sucesso!');
    }
}
