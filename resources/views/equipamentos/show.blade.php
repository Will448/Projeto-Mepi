@extends('layout.app')
@section('title', $equipamento->nome)
@section('page-title', 'Ficha do Equipamento')

@section('content')

<div class="mb-4 d-flex justify-content-between align-items-center">
    <a href="{{ route(auth()->user()->role.'.equipamentos.index') }}" style="color:#888;text-decoration:none;font-size:.85rem;">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
    <div class="d-flex gap-2">
        @if($equipamento->estaDisponivel())
        <a href="{{ route(auth()->user()->role.'.entregas.create') }}?equipamento_id={{ $equipamento->id }}"
           class="btn-mepi btn-mepi-amarelo">
            <i class="bi bi-box-seam"></i> Registrar Entrega
        </a>
        @endif
        <a href="{{ route(auth()->user()->role.'.equipamentos.edit', $equipamento) }}" class="btn-mepi">
            <i class="bi bi-pencil"></i> Editar
        </a>
    </div>
</div>

{{-- Header --}}
<div style="background:linear-gradient(135deg,var(--verde-escuro),var(--verde));border-radius:16px;padding:24px 32px;margin-bottom:24px;color:#fff;display:flex;align-items:center;gap:20px;">
    <div style="width:56px;height:56px;border-radius:14px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:1.6rem;flex-shrink:0;">
        <i class="bi bi-shield-check"></i>
    </div>
    <div style="flex:1;">
        <h4 style="font-family:'Syne',sans-serif;font-weight:800;margin:0 0 4px;">{{ $equipamento->nome }}</h4>
        <p style="margin:0;color:rgba(255,255,255,.7);font-size:.85rem;">
            <span style="background:rgba(255,255,255,.12);padding:2px 10px;border-radius:20px;">{{ $equipamento->tipo }}</span>
            &nbsp;·&nbsp;
            <span style="font-family:monospace;">{{ $equipamento->numero_serie }}</span>
        </p>
    </div>
    <div style="text-align:right;">
        @php
        $stLabel = match($equipamento->status) { 'disponivel'=>'Disponível','entregue'=>'Em uso','manutencao'=>'Manutenção',default=>$equipamento->status };
        $stClass  = match($equipamento->status) { 'disponivel'=>'badge-ativo','entregue'=>'badge-pendente','manutencao'=>'badge-negado',default=>'' };
        @endphp
        <span class="{{ $stClass }}" style="font-size:.8rem;">{{ $stLabel }}</span>
        @if($equipamento->validade)
        <p style="font-size:.75rem;color:rgba(255,255,255,.5);margin:6px 0 0;">
            Validade: {{ $equipamento->validade->format('d/m/Y') }}
            @if($equipamento->validade->isPast())
                <span style="color:#fca5a5;"> — VENCIDO</span>
            @endif
        </p>
        @endif
    </div>
</div>

{{-- Histórico de entregas --}}
<div class="card-mepi">
    <div class="card-mepi-header">
        <h6><i class="bi bi-clock-history me-2"></i>Histórico de Entregas</h6>
        <span style="font-size:.78rem;color:#aaa;">{{ $equipamento->entregas->count() }} entrega(s) no total</span>
    </div>
    <div class="card-mepi-body p-0">
        <table class="table table-mepi mb-0">
            <thead>
                <tr>
                    <th>Funcionário</th>
                    <th>Entregue em</th>
                    <th>Devolvido em</th>
                    <th>Tempo de uso</th>
                    <th>Observação</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($equipamento->entregas as $e)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:.86rem;">{{ $e->funcionario->nome }}</div>
                    </td>
                    <td style="font-size:.83rem;">{{ $e->data_entrega->format('d/m/Y') }}</td>
                    <td style="font-size:.83rem;">
                        @if($e->data_devolucao)
                            {{ $e->data_devolucao->format('d/m/Y') }}
                        @else
                            <span class="badge-ativo">Em uso</span>
                        @endif
                    </td>
                    <td style="font-size:.8rem;color:#666;">
                        @if($e->data_devolucao)
                            {{ $e->data_entrega->diffInDays($e->data_devolucao) }} dias
                        @else
                            {{ $e->data_entrega->diffInDays(now()) }} dias (atual)
                        @endif
                    </td>
                    <td style="font-size:.78rem;color:#888;max-width:160px;">
                        {{ $e->observacao ? Str::limit($e->observacao, 50) : '—' }}
                    </td>
                    <td>
                        @if(!$e->data_devolucao)
                        <a href="{{ route(auth()->user()->role.'.entregas.edit', $e) }}"
                           style="font-size:.78rem;color:#b08c00;text-decoration:none;background:rgba(245,196,0,.15);padding:4px 10px;border-radius:6px;">
                            <i class="bi bi-arrow-return-left me-1"></i>Devolver
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4" style="color:#aaa;font-size:.85rem;">
                        Este equipamento ainda não foi entregue a nenhum funcionário.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
