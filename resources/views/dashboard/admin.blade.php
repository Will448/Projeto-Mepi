@extends('layout.app')

@section('title', 'Admin — Dashboard')
@section('page-title', 'Dashboard Administrativo')

@section('content')

{{-- ── MÉTRICAS ──────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon icon-green"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="metric-val">{{ $totalFuncionarios }}</div>
                <div class="metric-lbl">Funcionários ativos</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon icon-yellow"><i class="bi bi-calendar-heart"></i></div>
            <div>
                <div class="metric-val">{{ $feriasPendentes }}</div>
                <div class="metric-lbl">Férias pendentes</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon icon-blue"><i class="bi bi-shield-check"></i></div>
            <div>
                <div class="metric-val">{{ $episEntregues }}</div>
                <div class="metric-lbl">EPIs em campo</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon icon-red"><i class="bi bi-receipt-cutoff"></i></div>
            <div>
                <div class="metric-val">{{ $folhasGeradas }}</div>
                <div class="metric-lbl">Folhas geradas</div>
            </div>
        </div>
    </div>
</div>

{{-- ── LINHA PRINCIPAL ───────────────────────────────────── --}}
<div class="row g-4 mb-4">

    {{-- Últimos funcionários --}}
    <div class="col-lg-6">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-people me-2"></i>Últimos Funcionários</h6>
                <a href="{{ route('admin.funcionarios.index') }}" class="btn-mepi btn-mepi-amarelo" style="padding:5px 14px;font-size:0.78rem;">
                    Ver todos <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-mepi-body p-0">
                <table class="table table-mepi mb-0">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Cargo</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimosFuncionarios as $f)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:0.85rem;">{{ $f->nome }}</div>
                                <div style="font-size:0.75rem;color:#888;">{{ $f->email }}</div>
                            </td>
                            <td style="font-size:0.82rem;color:#555;">{{ $f->cargo->nome }}</td>
                            <td><span class="badge-{{ $f->status }}">{{ ucfirst($f->status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Nenhum funcionário ainda.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Férias pendentes --}}
    <div class="col-lg-6">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-calendar-heart me-2"></i>Férias Aguardando Aprovação</h6>
                <a href="#" class="btn-mepi btn-mepi-amarelo" style="padding:5px 14px;font-size:0.78rem;">
                    Ver todas <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-mepi-body p-0">
                <table class="table table-mepi mb-0">
                    <thead>
                        <tr>
                            <th>Funcionário</th>
                            <th>Período</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feriasPendentesLista as $ferias)
                        <tr>
                            <td style="font-size:0.85rem;font-weight:600;">{{ $ferias->funcionario->nome }}</td>
                            <td style="font-size:0.78rem;color:#555;">
                                {{ $ferias->data_inicio->format('d/m/y') }} →
                                {{ $ferias->data_fim->format('d/m/y') }}<br>
                                <span style="color:#888;">{{ $ferias->dias_gozados }} dias</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <form method="POST" action="{{ route('admin.ferias.update', $ferias) }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="aprovado">
                                        <button class="btn btn-sm" style="background:rgba(26,107,58,0.1);color:var(--verde);border:none;font-size:0.75rem;padding:3px 10px;border-radius:6px;">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.ferias.update', $ferias) }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="negado">
                                        <button class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#dc2626;border:none;font-size:0.75rem;padding:3px 10px;border-radius:6px;">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Nenhuma solicitação pendente.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection