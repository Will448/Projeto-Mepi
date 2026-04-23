<?php

namespace App\Http\Controllers;

use App\Models\FolhaPagamento;
use App\Models\Funcionario;
use App\Models\Ferias;
use App\Services\FolhaService;
use Illuminate\Http\Request;

class FolhaPagamentoController extends Controller
{
    public function __construct(private FolhaService $folhaService) {}

    // ── Admin / RH: lista todas as folhas ────────────────────────
    public function index(Request $request)
    {
        $competenciaAtual = now()->format('Y-m');

        $query = FolhaPagamento::with('funcionario.cargo')
            ->when($request->competencia, fn($q) => $q->where('competencia', $request->competencia))
            ->when($request->funcionario_id, fn($q) => $q->where('funcionario_id', $request->funcionario_id))
            ->orderBy('competencia', 'desc')
            ->orderBy('created_at', 'desc');

        $folhas       = $query->paginate(12)->withQueryString();
        $funcionarios = Funcionario::where('status', 'ativo')->orderBy('nome')->get();

        $competencias = FolhaPagamento::selectRaw('DISTINCT competencia')
            ->orderBy('competencia', 'desc')
            ->pluck('competencia');

     
    $folhasGeradas = FolhaPagamento::count();

        return view('folha.index', compact('folhas', 'funcionarios', 'competencias', 'competenciaAtual', 'folhasGeradas'));
    }

    // ── Admin / RH: formulário ───────────────────────────────────
    public function create()
    {
        $funcionarios = Funcionario::with('cargo')->where('status', 'ativo')->orderBy('nome')->get();
        $competencia  = now()->format('Y-m');
        return view('folha.create', compact('funcionarios', 'competencia'));
    }

    // ── Simulação AJAX (CORRIGIDO) ───────────────────────────────
    public function simular(Request $request)
    {
        $request->validate([
            'funcionario_id'   => ['required', 'exists:funcionarios,id'],
            'competencia'      => ['required', 'regex:/^\d{4}-\d{2}$/'],
            'outros_adicionais'=> ['nullable', 'numeric', 'min:0'],
            'outros_descontos' => ['nullable', 'numeric', 'min:0'],
        ]);

        $funcionario = Funcionario::findOrFail($request->funcionario_id);

        [$ano, $mes] = explode('-', $request->competencia);

        $feriasNoMes = Ferias::where('funcionario_id', $funcionario->id)
            ->where('status', 'aprovado')
            ->whereMonth('data_inicio', $mes)
            ->whereYear('data_inicio', $ano)
            ->first();

        $diasFerias = $feriasNoMes ? $feriasNoMes->dias_gozados : 0;

        // 🔥 suporta array OU valor simples
        $totalAdicionais = (float) $request->outros_adicionais;

        if ($request->has('adicionais')) {
            $totalAdicionais = collect($request->adicionais)
                ->sum(fn($i) => (float) ($i['valor'] ?? 0));
        }

        $totalDescontos = (float) $request->outros_descontos;

        if ($request->has('descontos')) {
            $totalDescontos = collect($request->descontos)
                ->sum(fn($i) => (float) ($i['valor'] ?? 0));
        }

        $resultado = $this->folhaService->calcular(
            salarioBruto:     $funcionario->salario,
            outrosAdicionais: $totalAdicionais,
            outrosDescontos:  $totalDescontos,
            diasFerias:       $diasFerias,
        );

        return response()->json([
            'funcionario' => $funcionario->nome,
            'cargo'       => $funcionario->cargo->nome,
            'dias_ferias' => $diasFerias,
            ...$resultado,
        ]);
    }

    // ── Salvar folha ─────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'funcionario_id'   => ['required', 'exists:funcionarios,id'],
            'competencia'      => ['required', 'regex:/^\d{4}-\d{2}$/'],
            'observacao'       => ['nullable', 'string', 'max:500'],
            'adicionais'       => ['nullable', 'array'],
            'adicionais.*.descricao' => ['required_with:adicionais.*', 'string', 'max:150'],
            'adicionais.*.valor'     => ['required_with:adicionais.*', 'numeric', 'min:0'],
            'adicionais.*.obs'       => ['nullable', 'string', 'max:255'],
            'descontos'        => ['nullable', 'array'],
            'descontos.*.descricao'=> ['required_with:descontos.*', 'string', 'max:150'],
            'descontos.*.valor'    => ['required_with:descontos.*', 'numeric', 'min:0'],
            'descontos.*.obs'      => ['nullable', 'string', 'max:255'],
        ]);

        if (FolhaPagamento::where('funcionario_id', $request->funcionario_id)
            ->where('competencia', $request->competencia)
            ->exists()) {
            return back()->withInput()
                ->with('error', 'Já existe uma folha para este funcionário nesta competência.');
        }

        $funcionario = Funcionario::findOrFail($request->funcionario_id);

        [$ano, $mes] = explode('-', $request->competencia);

        $feriasNoMes = Ferias::where('funcionario_id', $funcionario->id)
            ->where('status', 'aprovado')
            ->whereMonth('data_inicio', $mes)
            ->whereYear('data_inicio', $ano)
            ->first();

        $diasFerias = $feriasNoMes ? $feriasNoMes->dias_gozados : 0;

        $totalAdicionais = collect($request->adicionais ?? [])
            ->sum(fn($i) => (float) ($i['valor'] ?? 0));

        $totalDescontos = collect($request->descontos ?? [])
            ->sum(fn($i) => (float) ($i['valor'] ?? 0));

        $calc = $this->folhaService->calcular(
            salarioBruto:     $funcionario->salario,
            outrosAdicionais: $totalAdicionais,
            outrosDescontos:  $totalDescontos,
            diasFerias:       $diasFerias,
        );

        FolhaPagamento::create([
            ...$calc,
            'funcionario_id' => $funcionario->id,
            'competencia'    => $request->competencia,
            'observacao'     => $request->observacao,
        ]);

        return redirect()
            ->route($this->prefix() . 'folha.index')
            ->with('success', "Folha de {$funcionario->nome} gerada com sucesso!");
    }

    // ── Ver holerite ─────────────────────────────────────────────
  public function show(FolhaPagamento $folha)
{
    $folha->load('funcionario.cargo');

    // Funcionário só pode ver o próprio holerite
    if (auth()->user()->role === 'funcionario') {
        $funcionario = Funcionario::where('user_id', auth()->id())->firstOrFail();
        if ($folha->funcionario_id !== $funcionario->id) {
            abort(403);
        }
    }

    return view('folha.show', compact('folha'));
}

    // ── Excluir ──────────────────────────────────────────────────
    public function destroy(FolhaPagamento $folha)
    {
        $folha->delete();

        return redirect()
            ->route($this->prefix() . 'folha.index')
            ->with('success', 'Folha excluída.');
    }

    // ── Funcionário ──────────────────────────────────────────────
    public function meuHolerite()
    {
        $funcionario = Funcionario::where('user_id', auth()->id())->firstOrFail();

        $folhas = FolhaPagamento::where('funcionario_id', $funcionario->id)
            ->orderBy('competencia', 'desc')
            ->get();

        return view('folha.meu_holerite', compact('funcionario', 'folhas'));
    }
    

    private function prefix(): string
    {
        return str_starts_with(request()->route()->getName(), 'admin.') ? 'admin.' : 'rh.';
    }
}