<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Funcionario;
use App\Models\Ferias;
use App\Models\FolhaPagamento;
use App\Models\Equipamento;
use App\Models\EntregaEquipamento;
use App\Services\FeriasService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('dashboard.admin', [
            'totalFuncionarios'   => Funcionario::where('status', 'ativo')->count(),
            'feriasPendentes'     => Ferias::where('status', 'pendente')->count(),
            'episEntregues'       => EntregaEquipamento::whereNull('data_devolucao')->count(),
            'folhasDoMes'   => FolhaPagamento::whereRaw("LEFT(competencia,7) = ?", [now()->format('Y-m')])->count(),
            'folhasGeradas' => FolhaPagamento::count(),
            'ultimosFuncionarios' => Funcionario::with('cargo')->latest()->take(5)->get(),
            'feriasPendentesLista'=> Ferias::with('funcionario')->where('status','pendente')->latest()->take(5)->get(),
        ]);
    }

    public function rh()
    {
        $vencendo = Carbon::today()->addDays(30);

        return view('dashboard.rh', [
            'totalFuncionarios'   => Funcionario::where('status', 'ativo')->count(),
            'feriasPendentes'     => Ferias::where('status', 'pendente')->count(),
            'folhasMes'           => FolhaPagamento::whereRaw("LEFT(competencia,7) = ?", [now()->format('Y-m')])->count(),
            'episVencendo'        => Equipamento::whereNotNull('validade')->where('validade','<=',$vencendo)->count(),
            'folhasGeradas' => FolhaPagamento::count(),
            'feriasPendentesLista'=> Ferias::with('funcionario')->where('status','pendente')->latest()->take(6)->get(),
            'episVencendoLista'   => Equipamento::whereNotNull('validade')->where('validade','<=',$vencendo)->orderBy('validade')->take(6)->get(),
        ]);
    }

    public function funcionario(FeriasService $feriasService)
    {
        $user       = auth()->user();
        $funcionario = Funcionario::with('cargo')->where('user_id', $user->id)->first();

        // Calcula saldo total de dias disponíveis somando todos os períodos
        $saldoFerias = 0;
        if ($funcionario) {
            $periodos    = $feriasService->periodosAquisitivos($funcionario);
            $saldoFerias = collect($periodos)->sum('saldo_disponivel');
        }

        return view('dashboard.funcionario', [
            'funcionario'  => $funcionario,
            'saldoFerias'  => $saldoFerias,
            'episAtivos'   => $funcionario
                              ? EntregaEquipamento::where('funcionario_id', $funcionario->id)->whereNull('data_devolucao')->count()
                              : 0,
            'minhasFerias' => $funcionario
                              ? Ferias::where('funcionario_id', $funcionario->id)->latest()->take(4)->get()
                              : collect(),
            'ultimaFolha'  => $funcionario
                              ? FolhaPagamento::where('funcionario_id', $funcionario->id)->latest()->first()
                              : null,
        ]);
    }
}
