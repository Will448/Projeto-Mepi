<?php

namespace App\Http\Controllers;

use App\Models\EntregaEquipamento;
use App\Models\Equipamento;
use App\Models\Funcionario;
use Illuminate\Http\Request;

class EntregaEquipamentoController extends Controller
{
    public function index(Request $request)
    {
        $query = EntregaEquipamento::with(['funcionario', 'equipamento'])
            ->when($request->funcionario_id, fn($q) => $q->where('funcionario_id', $request->funcionario_id))
            ->when($request->equipamento_id, fn($q) => $q->where('equipamento_id', $request->equipamento_id))
            ->when($request->situacao === 'em_uso',    fn($q) => $q->whereNull('data_devolucao'))
            ->when($request->situacao === 'devolvido', fn($q) => $q->whereNotNull('data_devolucao'))
            ->orderByRaw('data_devolucao IS NOT NULL') // em uso primeiro
            ->orderByDesc('data_entrega');

        $entregas     = $query->paginate(12)->withQueryString();
        $funcionarios = Funcionario::where('status', 'ativo')->orderBy('nome')->get();
        $equipamentos = Equipamento::orderBy('nome')->get();

        return view('entregas.index', compact('entregas', 'funcionarios', 'equipamentos'));
    }

    public function create()
    {
        // Só equipamentos disponíveis podem ser entregues
        $equipamentos = Equipamento::where('status', 'disponivel')->orderBy('nome')->get();
        $funcionarios = Funcionario::where('status', 'ativo')->orderBy('nome')->get();
        return view('entregas.create', compact('equipamentos', 'funcionarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'funcionario_id' => ['required', 'exists:funcionarios,id'],
            'equipamento_id' => ['required', 'exists:equipamentos,id'],
            'data_entrega'   => ['required', 'date', 'before_or_equal:today'],
            'observacao'     => ['nullable', 'string', 'max:500'],
        ]);

        $equipamento = Equipamento::findOrFail($request->equipamento_id);

        if (!$equipamento->estaDisponivel()) {
            return back()
                ->withInput()
                ->with('error', 'Este equipamento não está disponível para entrega.');
        }

        EntregaEquipamento::create([
            'funcionario_id' => $request->funcionario_id,
            'equipamento_id' => $request->equipamento_id,
            'data_entrega'   => $request->data_entrega,
            'observacao'     => $request->observacao,
        ]);

        // Atualiza o status do equipamento
        $equipamento->update(['status' => 'entregue']);

        return redirect()
            ->route($this->prefix() . 'entregas.index')
            ->with('success', 'EPI entregue com sucesso!');
    }

    public function show(EntregaEquipamento $entrega)
    {
        $entrega->load('funcionario.cargo', 'equipamento');
        return view('entregas.show', compact('entrega'));
    }

    // Registrar devolução — exibir formulário
    public function edit(EntregaEquipamento $entrega)
    {
        if ($entrega->foidevolvido()) {
            return redirect()
                ->route($this->prefix() . 'entregas.index')
                ->with('error', 'Este equipamento já foi devolvido.');
        }

        $entrega->load('funcionario', 'equipamento');
        return view('entregas.edit', compact('entrega'));
    }

    // Registrar devolução — salvar
    public function update(Request $request, EntregaEquipamento $entrega)
    {
        $request->validate([
            'data_devolucao' => ['required', 'date',
                                 'after_or_equal:' . $entrega->data_entrega->format('Y-m-d'),
                                 'before_or_equal:today'],
            'observacao'     => ['nullable', 'string', 'max:500'],
        ]);

        $entrega->update([
            'data_devolucao' => $request->data_devolucao,
            'observacao'     => $request->observacao,
        ]);

        // Libera o equipamento
        $entrega->equipamento->update(['status' => 'disponivel']);

        return redirect()
            ->route($this->prefix() . 'entregas.index')
            ->with('success', 'Devolução registrada! Equipamento disponível novamente.');
    }

    // Visão do funcionário — seus próprios EPIs
    public function meusEquipamentos()
    {
        $funcionario = Funcionario::where('user_id', auth()->id())->firstOrFail();

        $emUso = EntregaEquipamento::with('equipamento')
            ->where('funcionario_id', $funcionario->id)
            ->whereNull('data_devolucao')
            ->orderByDesc('data_entrega')
            ->get();

        $historico = EntregaEquipamento::with('equipamento')
            ->where('funcionario_id', $funcionario->id)
            ->whereNotNull('data_devolucao')
            ->orderByDesc('data_devolucao')
            ->get();

        return view('entregas.meus', compact('funcionario', 'emUso', 'historico'));
    }

    private function prefix(): string
    {
        return str_starts_with(request()->route()->getName(), 'admin.') ? 'admin.' : 'rh.';
    }
}
