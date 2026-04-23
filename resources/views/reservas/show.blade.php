@extends('layout.app')
@section('title', 'Detalhe da Reserva')
@section('page-title', 'Solicitação de Reserva')

@section('content')

<div class="mb-4">
    <a href="{{ route(auth()->user()->role.'.reservas.index') }}" style="color:#888;text-decoration:none;font-size:.85rem;">
        <i class="bi bi-arrow-left me-1"></i>Voltar para lista
    </a>
</div>

<div class="row g-4" style="max-width:860px;">

    {{-- Dados da solicitação --}}
    <div class="col-lg-7">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-box-seam me-2"></i>Detalhes da Solicitação</h6>
                @if($reserva->reserva_convertida)
                    <span class="badge-ativo">Entregue</span>
                @else
                    <span class="badge-{{ $reserva->status }}">{{ ucfirst($reserva->status) }}</span>
                @endif
            </div>
            <div class="card-mepi-body">

                {{-- Funcionário --}}
                <div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--off-white);border-radius:10px;margin-bottom:20px;">
                    <div style="width:42px;height:42px;border-radius:50%;background:var(--verde);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;">
                        {{ strtoupper(substr($reserva->funcionario->nome,0,1)) }}
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:.9rem;">{{ $reserva->funcionario->nome }}</div>
                        <div style="font-size:.78rem;color:#888;">{{ $reserva->funcionario->cargo->nome ?? '' }}</div>
                    </div>
                </div>

                {{-- Equipamento --}}
                <div style="display:flex;align-items:center;gap:12px;padding:12px;background:#f7f5ee;border-radius:10px;margin-bottom:20px;">
                    <div style="width:42px;height:42px;border-radius:10px;background:rgba(26,107,58,.1);display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:var(--verde);">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:.9rem;">{{ $reserva->equipamento->nome }}</div>
                        <div style="font-size:.75rem;color:#888;font-family:monospace;">{{ $reserva->equipamento->numero_serie }}</div>
                        <div style="font-size:.73rem;margin-top:2px;">
                            @php
                                $stLabel = match($reserva->equipamento->status) { 'disponivel'=>'Disponível','entregue'=>'Em uso','manutencao'=>'Manutenção',default=>$reserva->equipamento->status };
                                $stClass  = match($reserva->equipamento->status) { 'disponivel'=>'badge-ativo','entregue'=>'badge-pendente','manutencao'=>'badge-negado',default=>'' };
                            @endphp
                            <span class="{{ $stClass }}">{{ $stLabel }}</span>
                        </div>
                    </div>
                </div>

                {{-- Campos --}}
                @php
                $linhas = [
                    ['Data de uso solicitada', $reserva->data_inicio->format('d/m/Y')],
                    ['Previsão de devolução',  $reserva->data_fim ? $reserva->data_fim->format('d/m/Y') : 'Não informada'],
                    ['Solicitado em',          $reserva->created_at->format('d/m/Y \à\s H:i')],
                ];
                @endphp
                @foreach($linhas as [$label, $valor])
                <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f0f0e8;">
                    <span style="font-size:.82rem;color:#888;">{{ $label }}</span>
                    <span style="font-size:.85rem;font-weight:600;">{{ $valor }}</span>
                </div>
                @endforeach

                {{-- Justificativa --}}
                @if($reserva->justificativa)
                <div style="margin-top:16px;padding:14px;background:rgba(59,130,246,.05);border:1px solid rgba(59,130,246,.15);border-radius:8px;">
                    <p style="font-size:.72rem;color:#3b82f6;font-weight:700;margin-bottom:4px;text-transform:uppercase;letter-spacing:.5px;">
                        <i class="bi bi-chat-left-text me-1"></i>Justificativa do funcionário
                    </p>
                    <p style="font-size:.87rem;color:#444;margin:0;line-height:1.5;">{{ $reserva->justificativa }}</p>
                </div>
                @endif

                {{-- Resposta do RH (se já decidido) --}}
                @if($reserva->observacao_rh)
                <div style="margin-top:12px;padding:14px;background:rgba(245,196,0,.07);border:1px solid rgba(245,196,0,.2);border-radius:8px;">
                    <p style="font-size:.72rem;color:#b08c00;font-weight:700;margin-bottom:4px;text-transform:uppercase;letter-spacing:.5px;">
                        <i class="bi bi-person-check me-1"></i>Observação do RH
                    </p>
                    <p style="font-size:.87rem;color:#555;margin:0;line-height:1.5;">{{ $reserva->observacao_rh }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Painel de ações --}}
    <div class="col-lg-5">

        {{-- Alerta de conflitos --}}
        @if($conflitos->isNotEmpty())
        <div style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.2);border-radius:12px;padding:16px;margin-bottom:16px;">
            <p style="font-size:.78rem;font-weight:700;color:#dc2626;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                {{ $conflitos->count() }} conflito(s) no período
            </p>
            @foreach($conflitos as $c)
            <div style="font-size:.82rem;color:#666;padding:5px 0;border-bottom:1px solid rgba(239,68,68,.1);">
                <strong>{{ $c->funcionario->nome }}</strong> também tem reserva aprovada
                a partir de {{ $c->data_inicio->format('d/m/Y') }}
            </div>
            @endforeach
            <p style="font-size:.75rem;color:#aaa;margin:8px 0 0;">Revise antes de aprovar.</p>
        </div>
        @endif

        {{-- Converter em entrega --}}
        @if($reserva->isAprovado() && !$reserva->reserva_convertida)
        <div class="card-mepi mb-3">
            <div class="card-mepi-header">
                <h6><i class="bi bi-box-seam me-2"></i>Converter em Entrega</h6>
            </div>
            <div class="card-mepi-body">
                <p style="font-size:.83rem;color:#666;margin-bottom:14px;">
                    Quando o funcionário vier buscar o equipamento, clique abaixo para registrar a entrega oficial.
                </p>
                @if($reserva->equipamento->estaDisponivel())
                <form method="POST" action="{{ route(auth()->user()->role.'.reservas.converter', $reserva) }}"
                      onsubmit="return confirm('Confirmar entrega do equipamento?')">
                    @csrf
                    <button type="submit" class="btn-mepi w-100" style="justify-content:center;">
                        <i class="bi bi-box-seam"></i> Registrar Entrega
                    </button>
                </form>
                @else
                <div style="background:#f7f5ee;border-radius:8px;padding:10px 12px;font-size:.82rem;color:#888;text-align:center;">
                    <i class="bi bi-info-circle me-1"></i>
                    Equipamento não disponível no momento ({{ $reserva->equipamento->status }}).
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Decisão (só para pendentes) --}}
        @if($reserva->isPendente())
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-check2-square me-2"></i>Decisão</h6>
            </div>
            <div class="card-mepi-body">
                <form method="POST" action="{{ route(auth()->user()->role.'.reservas.update', $reserva) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.83rem;font-weight:600;color:#444;">
                            Observação (opcional)
                        </label>
                        <textarea name="observacao_rh" rows="3"
                                  class="form-control"
                                  placeholder="Instruções, condições de uso, justificativa da negação..."
                                  style="border-radius:8px;border-color:#ddd;font-size:.85rem;resize:vertical;">{{ old('observacao_rh') }}</textarea>
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
