{{-- ============================================================
     ARQUIVO 1: resources/views/entregas/show.blade.php
     ============================================================ --}}
@extends('layout.app')
@section('title', 'Detalhe da Entrega')
@section('page-title', 'Detalhe da Entrega')

@section('content')

<div class="mb-4">
    <a href="{{ route(auth()->user()->role.'.entregas.index') }}" style="color:#888;text-decoration:none;font-size:.85rem;">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="card-mepi" style="max-width:580px;">
    <div class="card-mepi-header">
        <h6><i class="bi bi-box-seam me-2"></i>Registro de Entrega</h6>
        @if($entrega->foidevolvido())
            <span class="badge-ativo">Devolvido</span>
        @else
            <span class="badge-pendente">Em uso</span>
        @endif
    </div>
    <div class="card-mepi-body">
        @php
        $linhas = [
            ['Funcionário',  $entrega->funcionario->nome],
            ['Cargo',        $entrega->funcionario->cargo->nome],
            ['Equipamento',  $entrega->equipamento->nome],
            ['Nº de Série',  $entrega->equipamento->numero_serie],
            ['Tipo',         $entrega->equipamento->tipo],
            ['Data Entrega', $entrega->data_entrega->format('d/m/Y')],
            ['Data Devolução', $entrega->data_devolucao ? $entrega->data_devolucao->format('d/m/Y') : '—'],
            ['Tempo de uso', $entrega->foidevolvido()
                ? $entrega->data_entrega->diffInDays($entrega->data_devolucao) . ' dias'
                : $entrega->data_entrega->diffInDays(now()) . ' dias (ativo)'],
        ];
        @endphp
        @foreach($linhas as [$l, $v])
        <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f0f0e8;">
            <span style="font-size:.82rem;color:#888;">{{ $l }}</span>
            <span style="font-size:.85rem;font-weight:600;">{{ $v }}</span>
        </div>
        @endforeach

        @if($entrega->observacao)
        <div style="margin-top:14px;padding:12px;background:#f7f5ee;border-radius:8px;font-size:.83rem;color:#555;">
            <strong style="color:#444;">Observação:</strong> {{ $entrega->observacao }}
        </div>
        @endif

        @if(!$entrega->foidevolvido())
        <div class="mt-4">
            <a href="{{ route(auth()->user()->role.'.entregas.edit', $entrega) }}"
               class="btn-mepi btn-mepi-amarelo">
                <i class="bi bi-arrow-return-left"></i> Registrar Devolução
            </a>
        </div>
        @endif
    </div>
</div>

@endsection
