<?php

namespace App\Http\Controllers;

use App\Models\Ferias;
use App\Models\Funcionario;
use App\Services\FeriasService;
use Illuminate\Http\Request;

class FeriasController extends Controller
{
    public function __construct(private FeriasService $feriasService) {}

    // ── Admin / RH: lista todas as férias ────────────────────────
    public function index(Request $request)
    {
        $query = Ferias::with('funcionario.cargo')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->funcionario_id, fn($q) => $q->where('funcionario_id', $request->funcionario_id))
            ->orderByRaw("FIELD(status,'pendente','aprovado','negado')")
            ->orderByDesc('created_at');

        $ferias       = $query->paginate(12)->withQueryString();
        $funcionarios = Funcionario::where('status', 'ativo')->orderBy('nome')->get();

        return view('ferias.index', compact('ferias', 'funcionarios'));
    }
// ── Admin / RH: formulário de cadastro ──────────────────────
public function create(Request $request)
{
        $funcionarios = Funcionario::with('cargo')->where('status', 'ativo')->orderBy('nome')->get();
        $periodos     = [];
        $funcionario  = null;

        if ($request->funcionario_id) {
            $funcionario = Funcionario::find($request->funcionario_id);
            if ($funcionario) {
                $periodos = $this->feriasService->periodosAquisitivos($funcionario);
            }
        }

        return view('ferias.create', compact('funcionarios', 'periodos', 'funcionario'));
    }

    // ── Admin / RH: salvar cadastro ─────────────────────────────
public function store(Request $request)
{
        $request->validate([
            'funcionario_id'            => ['required', 'exists:funcionarios,id'],
            'periodo_aquisitivo_inicio' => ['required', 'date'],
            'data_inicio'               => ['required', 'date'],
            'data_fim'                  => ['required', 'date', 'after:data_inicio'],
            'abono_pecuniario'          => ['nullable', 'boolean'],
            'dias_abono'                => ['nullable', 'integer', 'min:0', 'max:10'],
            'observacao'                => ['nullable', 'string', 'max:500'],
        ]);

        $funcionario = Funcionario::findOrFail($request->funcionario_id);
        $abono       = $request->boolean('abono_pecuniario');
        $diasAbono   = $abono ? (int) $request->dias_abono : 0;

        $inicio   = \Carbon\Carbon::parse($request->data_inicio);
        $fim      = \Carbon\Carbon::parse($request->data_fim);
        $diasGoza = $inicio->diffInDays($fim) + 1;

        $paInicio = \Carbon\Carbon::parse($request->periodo_aquisitivo_inicio);
        $paFim    = $paInicio->copy()->addYear()->subDay();

        Ferias::create([
            'funcionario_id'            => $funcionario->id,
            'periodo_aquisitivo_inicio' => $paInicio->toDateString(),
            'periodo_aquisitivo_fim'    => $paFim->toDateString(),
            'data_inicio'               => $request->data_inicio,
            'data_fim'                  => $request->data_fim,
            'dias_gozados'              => $diasGoza,
            'abono_pecuniario'          => $abono,
            'dias_abono'                => $abono ? $diasAbono : 0,
            'status'                    => 'pendente',
            'observacao'                => $request->observacao,
        ]);

        return redirect()
            ->route($this->prefix() . 'ferias.index')
            ->with('success', 'Férias cadastradas com sucesso!');
    }
    // ── Admin / RH: aprovar ou negar ─────────────────────────────
    public function update(Request $request, Ferias $ferias)
    {
        $request->validate([
            'status'     => ['required', 'in:aprovado,negado'],
            'observacao' => ['nullable', 'string', 'max:500'],
        ]);

        $ferias->status     = $request->status;
        $ferias->observacao = $request->observacao;
        $ferias->save();

        $msg = $request->status === 'aprovado' ? 'Férias aprovadas!' : 'Férias negadas.';

        return redirect()
            ->route($this->prefix() . 'ferias.show', $ferias)
            ->with('success', $msg);
    }

    // ── Página de edição de datas ────────────────────────────────
    public function edit(Ferias $ferias)
    {
        $ferias->load('funcionario.cargo');
        return view('ferias.edit', compact('ferias'));
    }

    // ── Salvar edição de datas ───────────────────────────────────
    public function editarDatas(Request $request, Ferias $ferias)
    {
        $request->validate([
            'data_inicio' => ['required', 'date'],
            'data_fim'    => ['required', 'date', 'after:data_inicio'],
            'observacao'  => ['nullable', 'string', 'max:500'],
        ]);

        $inicio   = \Carbon\Carbon::parse($request->data_inicio);
        $fim      = \Carbon\Carbon::parse($request->data_fim);
        $diasGoza = $inicio->diffInDays($fim) + 1;

        $ferias->data_inicio  = $request->data_inicio;
        $ferias->data_fim     = $request->data_fim;
        $ferias->dias_gozados = $diasGoza;
        $ferias->observacao   = $request->observacao;
        $ferias->save();

        return redirect()
            ->route($this->prefix() . 'ferias.index')
            ->with('success', 'Datas de férias atualizadas com sucesso!');
    }

    // ── Funcionário: lista as próprias férias ────────────────────
    public function minhasFerias()
    {
        $funcionario = Funcionario::where('user_id', auth()->id())->firstOrFail();
        $periodos    = $this->feriasService->periodosAquisitivos($funcionario);
        $ferias      = Ferias::where('funcionario_id', $funcionario->id)
                             ->orderByDesc('data_inicio')
                             ->get();

        return view('ferias.minhas', compact('funcionario', 'periodos', 'ferias'));
    }

    // ── Funcionário: solicitar férias ────────────────────────────
    public function solicitar(Request $request)
    {
        $request->validate([
            'periodo_aquisitivo_inicio' => ['required', 'date'],
            'data_inicio'               => ['required', 'date', 'after_or_equal:today'],
            'data_fim'                  => ['required', 'date', 'after:data_inicio'],
            'abono_pecuniario'          => ['nullable', 'boolean'],
            'dias_abono'                => ['nullable', 'integer', 'min:0', 'max:10'],
        ]);

        $funcionario = Funcionario::where('user_id', auth()->id())->firstOrFail();
        $abono       = $request->boolean('abono_pecuniario');
        $diasAbono   = $abono ? (int) $request->dias_abono : 0;

        $erros = $this->feriasService->validarSolicitacao(
            $funcionario,
            $request->periodo_aquisitivo_inicio,
            $request->data_inicio,
            $request->data_fim,
            $abono,
            $diasAbono,
        );

        if (!empty($erros)) {
            return back()->withErrors($erros)->withInput();
        }

        $dados = $this->feriasService->prepararDados(
            $funcionario,
            $request->periodo_aquisitivo_inicio,
            $request->data_inicio,
            $request->data_fim,
            $abono,
            $diasAbono,
        );

        Ferias::create($dados);

        return redirect()
            ->route('funcionario.ferias')
            ->with('success', 'Solicitação enviada! Aguarde aprovação do RH.');
    }

    // ── Admin / RH: visualiza detalhes de uma solicitação ────────
    public function show(Ferias $ferias)
    {
        $ferias = Ferias::with('funcionario.cargo')->findOrFail($ferias->id);

        if (!$ferias->funcionario) {
            return back()->with('error', 'Funcionário não encontrado para esta solicitação.');
        }

        $periodos = $this->feriasService->periodosAquisitivos($ferias->funcionario);

        return view('ferias.show', compact('ferias', 'periodos'));
    }

    private function prefix(): string
    {
        return str_starts_with(request()->route()->getName(), 'admin.') ? 'admin.' : 'rh.';
    }
}