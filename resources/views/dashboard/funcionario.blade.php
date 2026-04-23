@extends('layout.app')

@section('title', 'Meu Painel')
@section('page-title', 'Meu Painel')

@section('content')

{{-- Boas-vindas --}}
<div style="background:linear-gradient(135deg,var(--verde-escuro),var(--verde));border-radius:16px;padding:28px 32px;margin-bottom:24px;color:#fff;position:relative;overflow:hidden;">
    <div style="position:absolute;right:-20px;top:-20px;width:160px;height:160px;border-radius:50%;background:rgba(245,196,0,.08);"></div>
    <div style="position:absolute;right:60px;bottom:-40px;width:100px;height:100px;border-radius:50%;background:rgba(245,196,0,.05);"></div>
    <p style="font-size:.8rem;color:rgba(255,255,255,.6);margin-bottom:4px;text-transform:uppercase;letter-spacing:1px;">Bem-vindo de volta</p>
    <h3 style="font-family:'Syne',sans-serif;font-weight:800;font-size:1.6rem;margin-bottom:6px;">{{ auth()->user()->name }}</h3>
    <p style="font-size:.88rem;color:rgba(255,255,255,.7);margin:0;">
        {{ $funcionario?->cargo->nome ?? 'Cargo não definido' }} &nbsp;·&nbsp;
        Admitido em {{ $funcionario?->data_admissao->format('d/m/Y') ?? '—' }}
    </p>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon icon-green"><i class="bi bi-cash-coin"></i></div>
            <div>
                <div class="metric-val">R$ {{ number_format($funcionario?->salario ?? 0, 0, ',', '.') }}</div>
                <div class="metric-lbl">Salário bruto</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon icon-yellow"><i class="bi bi-calendar-heart"></i></div>
            <div>
                <div class="metric-val">{{ $saldoFerias }}</div>
                <div class="metric-lbl">Dias de férias</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon icon-blue"><i class="bi bi-shield-check"></i></div>
            <div>
                <div class="metric-val">{{ $episAtivos }}</div>
                <div class="metric-lbl">EPIs em meu nome</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon icon-red"><i class="bi bi-briefcase"></i></div>
            <div>
                <div class="metric-val">{{ $funcionario?->meses_trabalhados ?? 0 }}m</div>
                <div class="metric-lbl">Tempo de empresa</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Férias recentes --}}
    <div class="col-lg-6">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-calendar-heart me-2"></i>Minhas Férias</h6>
                <a href="{{ route('funcionario.ferias.solicitar') }}" class="btn-mepi btn-mepi-amarelo" style="padding:5px 14px;font-size:0.78rem;">
                    Solicitar <i class="bi bi-plus-lg"></i>
                </a>
            </div>
            <div class="card-mepi-body p-0">
                <table class="table table-mepi mb-0">
                    <thead>
                        <tr><th>Período</th><th>Dias</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($minhasFerias as $f)
                        <tr>
                            <td style="font-size:0.83rem;">
                                {{ $f->data_inicio->format('d/m/Y') }} – {{ $f->data_fim->format('d/m/Y') }}
                            </td>
                            <td style="font-size:0.83rem;">{{ $f->dias_gozados }}d</td>
                            <td><span class="badge-{{ $f->status }}">{{ ucfirst($f->status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3" style="font-size:0.85rem;">Nenhuma solicitação ainda.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Último holerite --}}
    <div class="col-lg-6">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-receipt-cutoff me-2"></i>Último Holerite</h6>
                <a href="{{ route('funcionario.holerite') }}" class="btn-mepi" style="padding:5px 14px;font-size:0.78rem;">
                    Ver histórico <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-mepi-body">
                @if($ultimaFolha)
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0f0e8;">
                        <span style="font-size:.85rem;color:#666;">Competência</span>
                        <span style="font-size:.85rem;font-weight:600;">{{ $ultimaFolha->competencia_formatada }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0f0e8;">
                        <span style="font-size:.85rem;color:#666;">Salário Bruto</span>
                        <span style="font-size:.85rem;">R$ {{ number_format($ultimaFolha->salario_bruto, 2, ',', '.') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0f0e8;">
                        <span style="font-size:.85rem;color:#666;">(-) INSS</span>
                        <span style="font-size:.85rem;color:#dc2626;">- R$ {{ number_format($ultimaFolha->desconto_inss, 2, ',', '.') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0f0e8;">
                        <span style="font-size:.85rem;color:#666;">(-) IRRF</span>
                        <span style="font-size:.85rem;color:#dc2626;">- R$ {{ number_format($ultimaFolha->desconto_irrf, 2, ',', '.') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:10px 0;background:rgba(26,107,58,.06);border-radius:8px;padding:12px;">
                        <span style="font-size:.9rem;font-weight:700;color:var(--verde-escuro);">Salário Líquido</span>
                        <span style="font-size:1rem;font-weight:800;color:var(--verde);">R$ {{ number_format($ultimaFolha->salario_liquido, 2, ',', '.') }}</span>
                    </div>
                </div>
                @else
                <p class="text-muted text-center py-3" style="font-size:.85rem;">Nenhuma folha disponível ainda.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
