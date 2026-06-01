{{-- ============================================================
     ARQUIVO 2: resources/views/auditoria/show.blade.php
     ============================================================ --}}
@extends('layout.app')
@section('title', 'Detalhe da Auditoria')
@section('page-title', 'Detalhe do Registro')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.auditoria.index') }}" style="color:#888;text-decoration:none;font-size:.85rem;">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="card-mepi" style="max-width:760px;">
    <div class="card-mepi-header">
        <h6><i class="bi bi-journal-text me-2"></i>Registro de Auditoria #{{ $auditoria->id }}</h6>
    </div>
    <div class="card-mepi-body">
        <div class="row g-3 mb-4">
            @foreach([
                ['Data/Hora',  $auditoria->created_at->format('d/m/Y H:i:s')],
                ['Usuário',    $auditoria->user?->name ?? 'Sistema'],
                ['Ação',       ucfirst($auditoria->acao)],
                ['Módulo',     $auditoria->modelo . ($auditoria->modelo_id ? " #{$auditoria->modelo_id}" : '')],
                ['IP',         $auditoria->ip ?? '—'],
                ['Descrição',  $auditoria->descricao ?? '—'],
            ] as [$l, $v])
            <div class="col-md-6">
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#aaa;margin-bottom:3px;">{{ $l }}</div>
                <div style="font-size:.88rem;font-weight:600;color:#333;">{{ $v }}</div>
            </div>
            @endforeach
        </div>

        @if($auditoria->dados_antes)
        <div class="mb-3">
            <p style="font-size:.78rem;font-weight:700;color:#dc2626;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">
                <i class="bi bi-arrow-left-circle me-1"></i>Dados Antes
            </p>
            <pre style="background:#fff8f8;border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:14px;font-size:.78rem;overflow-x:auto;color:#333;">{{ json_encode($auditoria->dados_antes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
        @endif

        @if($auditoria->dados_depois)
        <div>
            <p style="font-size:.78rem;font-weight:700;color:var(--verde);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">
                <i class="bi bi-arrow-right-circle me-1"></i>Dados Depois
            </p>
            <pre style="background:#f5fdf8;border:1px solid rgba(26,107,58,.2);border-radius:8px;padding:14px;font-size:.78rem;overflow-x:auto;color:#333;">{{ json_encode($auditoria->dados_depois, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
        @endif
    </div>
</div>

@endsection
