@extends('layout.app')
@section('title', 'Meus EPIs')
@section('page-title', 'Meus Equipamentos e EPIs')

@section('content')

{{-- Em uso --}}
<div class="mb-4">
    <h5 style="font-family:'Syne',sans-serif;font-weight:800;color:var(--verde-escuro);margin-bottom:16px;">
        Em meu nome agora
        <span style="font-size:.75rem;font-weight:400;color:#aaa;margin-left:8px;">{{ $emUso->count() }} item(s)</span>
    </h5>

    @if($emUso->isEmpty())
    <div style="background:#fff;border:1px solid #e5e5dc;border-radius:14px;padding:36px;text-align:center;">
        <i class="bi bi-shield-check" style="font-size:2rem;color:#ccc;display:block;margin-bottom:8px;"></i>
        <p style="color:#aaa;font-size:.88rem;margin:0;">Nenhum equipamento em seu nome no momento.</p>
    </div>
    @else
    <div class="row g-3">
        @foreach($emUso as $e)
        <div class="col-md-4">
            <div class="card-mepi h-100">
                <div class="card-mepi-body">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                        <div style="width:44px;height:44px;border-radius:12px;background:rgba(26,107,58,.1);display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:var(--verde);flex-shrink:0;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:.9rem;">{{ $e->equipamento->nome }}</div>
                            <div style="font-size:.72rem;color:#aaa;font-family:monospace;">{{ $e->equipamento->numero_serie }}</div>
                        </div>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        <div style="display:flex;justify-content:space-between;font-size:.8rem;">
                            <span style="color:#888;">Tipo</span>
                            <span style="font-weight:600;">{{ $e->equipamento->tipo }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;font-size:.8rem;">
                            <span style="color:#888;">Entregue em</span>
                            <span style="font-weight:600;">{{ $e->data_entrega->format('d/m/Y') }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;font-size:.8rem;">
                            <span style="color:#888;">Em uso há</span>
                            <span style="font-weight:600;color:var(--verde);">{{ $e->data_entrega->diffInDays(now()) }} dias</span>
                        </div>
                        @if($e->equipamento->validade)
                        <div style="display:flex;justify-content:space-between;font-size:.8rem;">
                            <span style="color:#888;">Validade</span>
                            <span style="font-weight:600;color:{{ $e->equipamento->validade->isPast() ? '#dc2626' : '#555' }};">
                                {{ $e->equipamento->validade->format('d/m/Y') }}
                                @if($e->equipamento->validade->isPast())
                                    <i class="bi bi-exclamation-circle ms-1"></i>
                                @endif
                            </span>
                        </div>
                        @endif
                    </div>
                    @if($e->observacao)
                    <div style="margin-top:10px;padding:8px 10px;background:#f7f5ee;border-radius:6px;font-size:.75rem;color:#666;">
                        {{ $e->observacao }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- Histórico --}}
@if($historico->isNotEmpty())
<div>
    <h5 style="font-family:'Syne',sans-serif;font-weight:800;color:var(--verde-escuro);margin-bottom:16px;">
        Histórico de devoluções
        <span style="font-size:.75rem;font-weight:400;color:#aaa;margin-left:8px;">{{ $historico->count() }} item(s)</span>
    </h5>

    <div class="card-mepi">
        <div class="card-mepi-body p-0">
            <table class="table table-mepi mb-0">
                <thead>
                    <tr>
                        <th>Equipamento</th>
                        <th>Entregue</th>
                        <th>Devolvido</th>
                        <th>Uso total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($historico as $e)
                    <tr>
                        <td>
                            <div style="font-weight:600;font-size:.86rem;">{{ $e->equipamento->nome }}</div>
                            <div style="font-size:.72rem;color:#aaa;font-family:monospace;">{{ $e->equipamento->numero_serie }}</div>
                        </td>
                        <td style="font-size:.83rem;">{{ $e->data_entrega->format('d/m/Y') }}</td>
                        <td style="font-size:.83rem;">{{ $e->data_devolucao->format('d/m/Y') }}</td>
                        <td style="font-size:.82rem;color:#888;">
                            {{ $e->data_entrega->diffInDays($e->data_devolucao) }} dias
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection
