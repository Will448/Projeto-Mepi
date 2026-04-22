<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    public function index()
    {
        $cargos = Cargo::withCount('funcionarios')->orderBy('nome')->paginate(10);
        return view('cargos.index', compact('cargos'));
    }

    public function create()
    {
        return view('cargos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'         => ['required', 'string', 'max:100'],
            'descricao'    => ['nullable', 'string', 'max:500'],
            'salario_base' => ['required', 'numeric', 'min:0'],
        ]);

        Cargo::create($request->only('nome', 'descricao', 'salario_base'));

        return redirect()
            ->route($this->prefix() . 'cargos.index')
            ->with('success', 'Cargo criado com sucesso!');
    }

    public function edit(Cargo $cargo)
    {
        return view('cargos.edit', compact('cargo'));
    }

    public function update(Request $request, Cargo $cargo)
    {
        $request->validate([
            'nome'         => ['required', 'string', 'max:100'],
            'descricao'    => ['nullable', 'string', 'max:500'],
            'salario_base' => ['required', 'numeric', 'min:0'],
        ]);

        $cargo->update($request->only('nome', 'descricao', 'salario_base'));

        return redirect()
            ->route($this->prefix() . 'cargos.index')
            ->with('success', 'Cargo atualizado com sucesso!');
    }

    public function destroy(Cargo $cargo)
    {
        if ($cargo->funcionarios()->count() > 0) {
            return back()->with('error', 'Não é possível excluir: cargo possui funcionários vinculados.');
        }

        $cargo->delete();

        return redirect()
            ->route($this->prefix() . 'cargos.index')
            ->with('success', 'Cargo excluído com sucesso!');
    }

    // Detecta se veio de rota admin. ou rh.
    private function prefix(): string
    {
        return str_starts_with(request()->route()->getName(), 'admin.') ? 'admin.' : 'rh.';
    }
}
