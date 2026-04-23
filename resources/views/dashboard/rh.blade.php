@extends('layout.app')

@section('title', 'RH — Dashboard')
@section('page-title', 'Painel de Recursos Humanos')

@section('content')

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon icon-green"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="metric-val">{{ $totalFuncionarios }}</div>
                <div class="metric-lbl">Funcionários</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon icon-yellow"><i class="bi bi-calendar-heart"></i></div>
            <div>
                <div class="metric-val">{{ $feriasPendentes }}</div>
                <div class="metric-lbl">Férias a aprovar</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon icon-blue"><i class="bi bi-receipt-cutoff"></i></div>
            <div>
                <div class="metric-val">{{ $folhasMes }}</div>
                <div class="metric-lbl">Folhas este mês</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon icon-blue"><i class="bi bi-receipt"></i></div>
            <div>
                <div class="metric-val">{{ $folhasGeradas }}</div>
                <div class="metric-lbl">Folhas geradas (total)</div>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon icon-red"><i class="bi bi-shield-exclamation"></i></div>
            <div>
                <div class="metric-val">{{ $episVencendo }}</div>
                <div class="metric-lbl">EPIs vencendo (30d)</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-calendar-heart me-2"></i>Solicitações de Férias Pendentes</h6>
                <a href="{{ route('rh.ferias.index') }}" class="btn-mepi btn-mepi-amarelo" style="padding:5px 14px;font-size:0.78rem;">
                    Gerenciar <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-mepi-body p-0">
                <table class="table table-mepi mb-0">
                    <thead>
                        <tr><th>Funcionário</th><th>Período</th><th>Dias</th><th>Ação</th></tr>
                    </thead>
                    <tbody>
                        @forelse($feriasPendentesLista as $f)
                        <tr>
                            <td style="font-weight:600;font-size:0.85rem;">{{ $f->funcionario->nome }}</td>
                            <td style="font-size:0.8rem;">{{ $f->data_inicio->format('d/m/Y') }} – {{ $f->data_fim->format('d/m/Y') }}</td>
                            <td><span class="badge-pendente">{{ $f->dias_gozados }}d</span></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <form method="POST" action="{{ route('rh.ferias.update', $f) }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="aprovado">
                                        <button class="btn btn-sm" style="background:rgba(26,107,58,0.1);color:var(--verde);border:none;padding:3px 10px;border-radius:6px;font-size:0.75rem;">
                                            <i class="bi bi-check-lg"></i> Aprovar
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('rh.ferias.update', $f) }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="negado">
                                        <button class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#dc2626;border:none;padding:3px 10px;border-radius:6px;font-size:0.75rem;">
                                            <i class="bi bi-x-lg"></i> Negar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3" style="font-size:0.85rem;">Nenhuma pendência.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card-mepi h-100">
            <div class="card-mepi-header">
                <h6><i class="bi bi-shield-exclamation me-2"></i>EPIs com Validade Próxima</h6>
            </div>
            <div class="card-mepi-body p-0">
                <table class="table table-mepi mb-0">
                    <thead>
                        <tr><th>EPI</th><th>Validade</th></tr>
                    </thead>
                    <tbody>
                        @forelse($episVencendoLista as $e)
                        <tr>
                            <td style="font-size:0.85rem;font-weight:600;">{{ $e->nome }}</td>
                            <td>
                                <span class="badge-pendente">{{ $e->validade->format('d/m/Y') }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center text-muted py-3" style="font-size:0.85rem;">Nenhum EPI vencendo.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection


