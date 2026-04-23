<?php

namespace App\Http\Controllers;

use App\Models\ReservaEquipamento;
use App\Models\Equipamento;
use App\Models\Funcionario;
use App\Models\EntregaEquipamento;
use Illuminate\Http\Request;

class ReservaEquipamentoController extends Controller
{
    // ── Admin / RH: lista todas as reservas ──────────────────────
    public function index(Request $request)
    {
        $query = ReservaEquipamento::with(['funcionario', 'equipamento'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->funcionario_id, fn($q) => $q->where('funcionario_id', $request->funcionario_id))
            ->when($request->equipamento_id, fn($q) => $q->where('equipamento_id', $request->equipamento_id))
            ->orderByRaw("FIELD(status,'pendente','aprovado','negado')")
            ->orderByDesc('created_at');

        $reservas     = $query->paginate(12)->withQueryString();
        $funcionarios = Funcionario::where('status','ativo')->orderBy('nome')->get();
        $equipamentos = Equipamento::orderBy('nome')->get();

        return view('reservas.index', compact('reservas','funcionarios','equipamentos'));
    }

    // ── Admin / RH: detalhe + decisão ───────────────────────────
    public function show(ReservaEquipamento $reserva)
    {
        $reserva->load('funcionario.cargo','equipamento');

        // Conflitos: outras reservas aprovadas para o mesmo equipamento
        $conflitos = ReservaEquipamento::where('equipamento_id', $reserva->equipamento_id)
            ->where('id','!=', $reserva->id)
            ->where('status','aprovado')
            ->where('reserva_convertida', false)
            ->when($reserva->data_fim, fn($q) =>
                $q->where('data_inicio','<=',$reserva->data_fim)
            )
            ->where('data_inicio','>=',$reserva->data_inicio)
            ->with('funcionario')
            ->get();

        return view('reservas.show', compact('reserva','conflitos'));
    }

    // ── Admin / RH: aprovar ou negar ─────────────────────────────
    public function update(Request $request, ReservaEquipamento $reserva)
    {
        $request->validate([
            'status'        => ['required','in:aprovado,negado'],
            'observacao_rh' => ['nullable','string','max:500'],
        ]);

        $reserva->update([
            'status'        => $request->status,
            'observacao_rh' => $request->observacao_rh,
        ]);

        $msg = $request->status === 'aprovado'
            ? 'Reserva aprovada! O funcionário será notificado.'
            : 'Reserva negada.';

        return back()->with('success', $msg);
    }

    // ── Admin / RH: converter reserva aprovada em entrega real ───
    public function converter(ReservaEquipamento $reserva)
    {
        if (!$reserva->isAprovado() || $reserva->reserva_convertida) {
            return back()->with('error', 'Esta reserva não pode ser convertida.');
        }

        $equipamento = $reserva->equipamento;

        if (!$equipamento->estaDisponivel()) {
            return back()->with('error', 'O equipamento não está disponível no momento.');
        }

        // Cria a entrega real
        EntregaEquipamento::create([
            'funcionario_id' => $reserva->funcionario_id,
            'equipamento_id' => $reserva->equipamento_id,
            'data_entrega'   => now()->toDateString(),
            'observacao'     => "Originado da reserva #" . $reserva->id,
        ]);

        // Atualiza o equipamento e marca reserva como convertida
        $equipamento->update(['status' => 'entregue']);
        $reserva->update(['reserva_convertida' => true]);

        return redirect()
            ->route($this->prefix().'reservas.index')
            ->with('success', 'Reserva convertida em entrega com sucesso!');
    }

    // ── Funcionário: lista as próprias reservas ──────────────────
    public function minhasReservas()
    {
        $funcionario  = Funcionario::where('user_id', auth()->id())->firstOrFail();
        $reservas     = ReservaEquipamento::with('equipamento')
                            ->where('funcionario_id', $funcionario->id)
                            ->orderByDesc('created_at')
                            ->get();

        // Só equipamentos disponíveis podem ser solicitados
        $equipamentos = Equipamento::where('status','disponivel')->orderBy('nome')->get();

        return view('reservas.minhas', compact('funcionario','reservas','equipamentos'));
    }

    // ── Funcionário: solicitar reserva ───────────────────────────
    public function solicitar(Request $request)
    {
        $request->validate([
            'equipamento_id' => ['required','exists:equipamentos,id'],
            'data_inicio'    => ['required','date','after_or_equal:today'],
            'data_fim'       => ['nullable','date','after:data_inicio'],
            'justificativa'  => ['required','string','min:10','max:500'],
        ]);

        $funcionario = Funcionario::where('user_id', auth()->id())->firstOrFail();
        $equipamento = Equipamento::findOrFail($request->equipamento_id);

        // Bloqueia se o equipamento não estiver disponível
        if (!$equipamento->estaDisponivel()) {
            return back()
                ->withInput()
                ->withErrors(['equipamento_id' => 'Este equipamento não está disponível para reserva.']);
        }

        // Bloqueia se já existe reserva pendente/aprovada do mesmo funcionário para o mesmo EPI
        $jaExiste = ReservaEquipamento::where('funcionario_id', $funcionario->id)
            ->where('equipamento_id', $request->equipamento_id)
            ->whereIn('status', ['pendente','aprovado'])
            ->where('reserva_convertida', false)
            ->exists();

        if ($jaExiste) {
            return back()
                ->withInput()
                ->withErrors(['equipamento_id' => 'Você já possui uma reserva ativa para este equipamento.']);
        }

        ReservaEquipamento::create([
            'funcionario_id' => $funcionario->id,
            'equipamento_id' => $request->equipamento_id,
            'data_inicio'    => $request->data_inicio,
            'data_fim'       => $request->data_fim,
            'justificativa'  => $request->justificativa,
        ]);

        return redirect()
            ->route('funcionario.reservas')
            ->with('success', 'Solicitação de reserva enviada! Aguarde aprovação do RH.');
    }

    // ── Funcionário: cancelar reserva pendente ───────────────────
    public function cancelar(ReservaEquipamento $reserva)
    {
        $funcionario = Funcionario::where('user_id', auth()->id())->firstOrFail();

        if ($reserva->funcionario_id !== $funcionario->id) {
            abort(403);
        }

        if (!$reserva->isPendente()) {
            return back()->with('error', 'Só é possível cancelar reservas pendentes.');
        }

        $reserva->delete();

        return redirect()
            ->route('funcionario.reservas')
            ->with('success', 'Reserva cancelada com sucesso.');
    }

    private function prefix(): string
    {
        return str_starts_with(request()->route()->getName(), 'admin.') ? 'admin.' : 'rh.';
    }
}
