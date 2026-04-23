<?php

namespace App\Http\Controllers;

use App\Models\Equipamento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EquipamentoController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipamento::withCount([
                'entregas',
                'entregas as em_uso_count' => fn($q) => $q->whereNull('data_devolucao'),
            ])
            ->when($request->busca, fn($q) =>
                $q->where('nome', 'like', "%{$request->busca}%")
                  ->orWhere('numero_serie', 'like', "%{$request->busca}%")
            )
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->tipo,   fn($q) => $q->where('tipo', $request->tipo))
            ->orderBy('nome');

        $equipamentos = $query->paginate(12)->withQueryString();

        // Tipos cadastrados para o filtro
        $tipos = Equipamento::selectRaw('DISTINCT tipo')->orderBy('tipo')->pluck('tipo');

        // Totais para os cards de resumo
        $totais = [
            'total'      => Equipamento::count(),
            'disponivel' => Equipamento::where('status', 'disponivel')->count(),
            'entregue'   => Equipamento::where('status', 'entregue')->count(),
            'manutencao' => Equipamento::where('status', 'manutencao')->count(),
        ];

        return view('equipamentos.index', compact('equipamentos', 'tipos', 'totais'));
    }

    public function create()
    {
        return view('equipamentos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'         => ['required', 'string', 'max:150'],
            'numero_serie' => ['required', 'string', 'max:100', 'unique:equipamentos,numero_serie'],
            'tipo'         => ['required', 'string', 'max:80'],
            'validade'     => ['nullable', 'date'],
            'status'       => ['required', 'in:disponivel,entregue,manutencao'],
        ]);

        Equipamento::create($request->only('nome', 'numero_serie', 'tipo', 'validade', 'status'));

        return redirect()
            ->route($this->prefix() . 'equipamentos.index')
            ->with('success', 'Equipamento cadastrado com sucesso!');
    }

    public function show(Equipamento $equipamento)
    {
        $equipamento->load([
            'entregas' => fn($q) => $q->with('funcionario')->orderByDesc('data_entrega'),
        ]);
        return view('equipamentos.show', compact('equipamento'));
    }

    public function edit(Equipamento $equipamento)
    {
        return view('equipamentos.edit', compact('equipamento'));
    }

    public function update(Request $request, Equipamento $equipamento)
    {
        $request->validate([
            'nome'         => ['required', 'string', 'max:150'],
            'numero_serie' => ['required', 'string', 'max:100',
                               Rule::unique('equipamentos', 'numero_serie')->ignore($equipamento->id)],
            'tipo'         => ['required', 'string', 'max:80'],
            'validade'     => ['nullable', 'date'],
            'status'       => ['required', 'in:disponivel,entregue,manutencao'],
        ]);

        $equipamento->update($request->only('nome', 'numero_serie', 'tipo', 'validade', 'status'));

        return redirect()
            ->route($this->prefix() . 'equipamentos.index')
            ->with('success', 'Equipamento atualizado com sucesso!');
    }

    public function destroy(Equipamento $equipamento)
    {
        // Bloqueia exclusão se tiver entregas ativas
        if ($equipamento->entregas()->whereNull('data_devolucao')->exists()) {
            return back()->with('error', 'Não é possível excluir: equipamento está em uso por um funcionário.');
        }

        $equipamento->delete();

        return redirect()
            ->route($this->prefix() . 'equipamentos.index')
            ->with('success', 'Equipamento excluído com sucesso!');
    }

    private function prefix(): string
    {
        return str_starts_with(request()->route()->getName(), 'admin.') ? 'admin.' : 'rh.';
    }
}
