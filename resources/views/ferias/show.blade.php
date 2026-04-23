@extends('layout.app')
@section('title', 'Detalhes da Solicitação')
@section('page-title', 'Solicitação de Férias')

@section('content')

<div class="mb-4">
    <a href="{{ route(auth()->user()->role.'.ferias.index') }}" style="color:#888;text-decoration:none;font-size:.85rem;">
        <i class="bi bi-arrow-left me-1"></i>Voltar para lista
    </a>
</div>

<div class="row g-4" style="max-width:860px;">

    {{-- Dados da solicitação --}}
    <div class="col-lg-7">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-calendar-heart me-2"></i>Detalhes da Solicitação</h6>
                <span class="badge-{{ $ferias->status }}">{{ ucfirst($ferias->status) }}</span>
            </div>
            <div class="card-mepi-body">
                {{-- Funcionário --}}
                <div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--off-white);border-radius:10px;margin-bottom:20px;">
                    <div style="width:42px;height:42px;border-radius:50%;background:var(--verde);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1rem;">
                        {{ strtoupper(substr($ferias->funcionario->nome,0,1)) }}
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:.9rem;">{{ $ferias->funcionario->nome }}</div>
                        <div style="font-size:.78rem;color:#888;">{{ $ferias->funcionario->cargo->nome }}</div>
                    </div>
                </div>

                @php
                $linhas = [
                    ['Período de gozo',       $ferias->data_inicio->format('d/m/Y').' → '.$ferias->data_fim->format('d/m/Y')],
                    ['Dias a gozar',           $ferias->dias_gozados.' dias'],
                    ['Abono pecuniário',       $ferias->abono_pecuniario ? $ferias->dias_abono.' dias vendidos' : 'Não'],
                    ['Período aquisitivo',     $ferias->periodo_aquisitivo_inicio->format('d/m/Y').' → '.$ferias->periodo_aquisitivo_fim->format('d/m/Y')],
                    ['Solicitado em',          $ferias->created_at->format('d/m/Y \à\s H:i')],
                ];
                @endphp

                @foreach($linhas as [$label, $valor])
                <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f0f0e8;">
                    <span style="font-size:.82rem;color:#888;">{{ $label }}</span>
                    <span style="font-size:.85rem;font-weight:600;">{{ $valor }}</span>
                </div>
                @endforeach

                @if($ferias->observacao)
                <div style="margin-top:16px;padding:12px;background:rgba(245,196,0,.08);border:1px solid rgba(245,196,0,.2);border-radius:8px;">
                    <p style="font-size:.78rem;color:#8a6d00;font-weight:700;margin-bottom:4px;">
                        <i class="bi bi-chat-left-text me-1"></i>Observação
                    </p>
                    <p style="font-size:.85rem;color:#555;margin:0;">{{ $ferias->observacao }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Saldo de férias do funcionário --}}
    <div class="col-lg-5">
        <div class="card-mepi mb-4">
            <div class="card-mepi-header">
                <h6><i class="bi bi-clock-history me-2"></i>Saldo por Período</h6>
            </div>
            <div class="card-mepi-body p-0">
                @forelse($periodos as $p)
                <div style="padding:12px 16px;border-bottom:1px solid #f0f0e8;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                        <span style="font-size:.78rem;color:#555;">
                            {{ \Carbon\Carbon::parse($p['periodo_aquisitivo_inicio'])->format('d/m/Y') }}
                            →
                            {{ \Carbon\Carbon::parse($p['periodo_aquisitivo_fim'])->format('d/m/Y') }}
                        </span>
                        @if($p['vencido'])
                            <span style="font-size:.7rem;background:rgba(239,68,68,.1);color:#dc2626;padding:2px 8px;border-radius:10px;font-weight:600;">Vencido</span>
                        @endif
                    </div>
                    <div style="display:flex;gap:10px;font-size:.78rem;">
                        <span style="color:#888;">Direito: <strong style="color:#333;">30d</strong></span>
                        <span style="color:#888;">Gozado: <strong style="color:#333;">{{ $p['dias_gozados'] }}d</strong></span>
                        <span style="color:#888;">Saldo: <strong style="color:var(--verde);">{{ $p['saldo_disponivel'] }}d</strong></span>
                    </div>
                    {{-- Barra de progresso --}}
                    <div style="height:4px;background:#f0f0e8;border-radius:4px;margin-top:8px;overflow:hidden;">
                        <div style="height:100%;width:{{ min(100, ($p['dias_gozados']/30)*100) }}%;background:var(--verde);border-radius:4px;"></div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-3" style="font-size:.83rem;">Nenhum período aquisitivo ainda.</p>
                @endforelse
            </div>
        </div>

        {{-- Ação de aprovar/negar --}}
        @if($ferias->isPendente())
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-check2-square me-2"></i>Decisão</h6>
            </div>
            <div class="card-mepi-body">
                <form method="POST" action="{{ route(auth()->user()->role.'.ferias.update', $ferias) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.83rem;font-weight:600;color:#444;">
                            Observação (opcional)
                        </label>
                        <textarea name="observacao" rows="3"
                                  class="form-control"
                                  placeholder="Justificativa, instruções..."
                                  style="border-radius:8px;border-color:#ddd;font-size:.85rem;resize:vertical;">{{ old('observacao') }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="status" value="aprovado"
                                class="btn-mepi flex-fill" style="justify-content:center;">
                            <i class="bi bi-check-lg"></i> Aprovar
                        </button>
                        <button type="submit" name="status" value="negado"
                                class="btn btn-sm flex-fill"
                                style="background:rgba(239,68,68,.1);color:#dc2626;border:none;border-radius:8px;padding:9px;font-size:.85rem;font-weight:600;">
                            <i class="bi bi-x-lg"></i> Negar
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>

</div>

@endsection
