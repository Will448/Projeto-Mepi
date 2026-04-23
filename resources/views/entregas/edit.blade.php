{{-- ============================================================
     ARQUIVO 1: resources/views/entregas/edit.blade.php
     (Registrar devolução)
     ============================================================ --}}
@extends('layout.app')
@section('title', 'Registrar Devolução')
@section('page-title', 'Registrar Devolução')

@section('content')

<div class="mb-4">
    <a href="{{ route(auth()->user()->role.'.entregas.index') }}" style="color:#888;text-decoration:none;font-size:.85rem;">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="card-mepi" style="max-width:520px;">
    <div class="card-mepi-header">
        <h6><i class="bi bi-arrow-return-left me-2"></i>Devolução de Equipamento</h6>
    </div>
    <div class="card-mepi-body">

        {{-- Resumo --}}
        <div style="background:var(--off-white);border-radius:10px;padding:14px 16px;margin-bottom:20px;">
            <div style="font-size:.78rem;color:#888;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;">Equipamento</div>
            <div style="font-weight:700;font-size:.95rem;">{{ $entrega->equipamento->nome }}</div>
            <div style="font-size:.78rem;color:#aaa;font-family:monospace;">{{ $entrega->equipamento->numero_serie }}</div>
            <div style="margin-top:10px;font-size:.82rem;color:#666;">
                <i class="bi bi-person me-1"></i> {{ $entrega->funcionario->nome }}
                &nbsp;·&nbsp;
                <i class="bi bi-calendar me-1"></i> Entregue em {{ $entrega->data_entrega->format('d/m/Y') }}
                &nbsp;·&nbsp;
                {{ $entrega->data_entrega->diffInDays(now()) }} dias em uso
            </div>
        </div>

        <form method="POST" action="{{ route(auth()->user()->role.'.entregas.update', $entrega) }}">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Data da Devolução *</label>
                <input type="date" name="data_devolucao"
                       value="{{ old('data_devolucao', now()->format('Y-m-d')) }}"
                       min="{{ $entrega->data_entrega->format('Y-m-d') }}"
                       max="{{ now()->format('Y-m-d') }}"
                       class="form-control @error('data_devolucao') is-invalid @enderror"
                       style="border-radius:8px;border-color:#ddd;font-size:.85rem;">
                @error('data_devolucao') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Observação</label>
                <textarea name="observacao" rows="3"
                          class="form-control"
                          placeholder="Condições na devolução, danos observados..."
                          style="border-radius:8px;border-color:#ddd;font-size:.85rem;resize:vertical;">{{ old('observacao', $entrega->observacao) }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn-mepi">
                    <i class="bi bi-check-lg"></i> Confirmar Devolução
                </button>
                <a href="{{ route(auth()->user()->role.'.entregas.index') }}"
                   style="padding:9px 20px;border-radius:8px;background:#f0f0e8;color:#555;text-decoration:none;font-size:.85rem;font-weight:600;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
