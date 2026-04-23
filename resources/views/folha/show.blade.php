@extends('layout.app')
@section('title', 'Holerite')
@section('page-title', 'Holerite')

@section('content')

<div class="mb-4 d-flex justify-content-between align-items-center">
   <a href="{{ auth()->user()->role === 'funcionario' ? route('funcionario.holerite') : route(auth()->user()->role.'.folha.index') }}" style="color:#888;text-decoration:none;font-size:.85rem;">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
    <button onclick="window.print()" class="btn-mepi">
        <i class="bi bi-printer"></i> Imprimir
    </button>
</div>

{{-- Holerite --}}
<div id="holerite" style="max-width:680px;background:#fff;border:1px solid #e5e5dc;border-radius:16px;overflow:hidden;">

    {{-- Cabeçalho --}}
    <div style="background:linear-gradient(135deg,var(--verde-escuro),var(--verde));padding:28px 32px;color:#fff;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;">
            <div>
                <p style="font-size:.7rem;color:rgba(255,255,255,.6);margin:0;text-transform:uppercase;letter-spacing:1px;">Holerite</p>
                <h4 style="font-family:'Syne',sans-serif;font-weight:800;margin:4px 0 0;">{{ $folha->funcionario->nome }}</h4>
                <p style="font-size:.85rem;color:rgba(255,255,255,.75);margin:4px 0 0;">
                    {{ $folha->funcionario->cargo->nome }}
                </p>
            </div>
            <div style="text-align:right;">
                <p style="font-size:.7rem;color:rgba(255,255,255,.6);margin:0;text-transform:uppercase;letter-spacing:1px;">Competência</p>
                <p style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:800;margin:4px 0 0;color:var(--amarelo);">
                    {{ $folha->competencia_formatada }}
                </p>
                <p style="font-size:.75rem;color:rgba(255,255,255,.5);margin:2px 0 0;">
                    Gerado em {{ $folha->created_at->format('d/m/Y') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Corpo --}}
    <div style="padding:28px 32px;">

        {{-- Proventos --}}
        <p style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#aaa;margin-bottom:8px;">Proventos</p>
        <div style="margin-bottom:20px;">
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f0e8;">
                <span style="font-size:.87rem;color:#444;">Salário Base</span>
                <span style="font-size:.87rem;font-weight:600;">R$ {{ number_format($folha->salario_bruto,2,',','.') }}</span>
            </div>
            @if($folha->adicional_ferias > 0)
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f0e8;">
                <span style="font-size:.87rem;color:#444;">Adicional de Férias (1/3 constitucional)</span>
                <span style="font-size:.87rem;font-weight:600;color:var(--verde);">+ R$ {{ number_format($folha->adicional_ferias,2,',','.') }}</span>
            </div>
            @endif
            @if($folha->outros_adicionais > 0)
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f0e8;">
                <span style="font-size:.87rem;color:#444;">Outros Adicionais</span>
                <span style="font-size:.87rem;font-weight:600;color:var(--verde);">+ R$ {{ number_format($folha->outros_adicionais,2,',','.') }}</span>
            </div>
            @endif
        </div>

        {{-- Descontos --}}
        <p style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#aaa;margin-bottom:8px;">Descontos</p>
        <div style="margin-bottom:24px;">
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f0e8;">
                <span style="font-size:.87rem;color:#444;">INSS (contribuição progressiva)</span>
                <span style="font-size:.87rem;font-weight:600;color:#dc2626;">- R$ {{ number_format($folha->desconto_inss,2,',','.') }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f0e8;">
                <span style="font-size:.87rem;color:#444;">IRRF</span>
                <span style="font-size:.87rem;font-weight:600;color:#dc2626;">- R$ {{ number_format($folha->desconto_irrf,2,',','.') }}</span>
            </div>
            @if($folha->outros_descontos > 0)
            <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f0e8;">
                <span style="font-size:.87rem;color:#444;">Outros Descontos</span>
                <span style="font-size:.87rem;font-weight:600;color:#dc2626;">- R$ {{ number_format($folha->outros_descontos,2,',','.') }}</span>
            </div>
            @endif
        </div>

        {{-- Líquido --}}
        <div style="background:rgba(26,107,58,.07);border:1px solid rgba(26,107,58,.15);border-radius:12px;padding:18px 22px;display:flex;justify-content:space-between;align-items:center;">
            <span style="font-weight:700;font-size:1rem;color:var(--verde-escuro);">Salário Líquido a Receber</span>
            <span style="font-family:'Syne',sans-serif;font-size:1.6rem;font-weight:800;color:var(--verde);">
                R$ {{ number_format($folha->salario_liquido,2,',','.') }}
            </span>
        </div>

        @if($folha->observacao)
        <div style="margin-top:16px;padding:12px 16px;background:#f7f5ee;border-radius:8px;font-size:.82rem;color:#666;">
            <strong>Observação:</strong> {{ $folha->observacao }}
        </div>
        @endif
    </div>

    {{-- Rodapé --}}
    <div style="padding:16px 32px;border-top:1px solid #f0f0e8;background:#fafaf5;display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:.72rem;color:#aaa;">MEPI — Sistema de Gestão</span>
        <span style="font-size:.72rem;color:#aaa;">Admissão: {{ $folha->funcionario->data_admissao->format('d/m/Y') }}</span>
    </div>
</div>

@endsection

@push('styles')
<style>
@media print {
    .sidebar, .topbar, .btn-mepi, .mb-4 > a { display: none !important; }
    .main-content { margin-left: 0 !important; padding-top: 0 !important; }
    #holerite { border: none !important; border-radius: 0 !important; }
}
</style>
@endpush
