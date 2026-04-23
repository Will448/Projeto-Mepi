@extends('layout.app')
@section('title', 'Editar Equipamento')
@section('page-title', 'Editar Equipamento')

@section('content')

<div class="mb-4">
    <a href="{{ route(auth()->user()->role.'.equipamentos.index') }}" style="color:#888;text-decoration:none;font-size:.85rem;">
        <i class="bi bi-arrow-left me-1"></i>Voltar para lista
    </a>
</div>

<div class="card-mepi" style="max-width:640px;">
    <div class="card-mepi-header">
        <h6><i class="bi bi-pencil me-2"></i>Editar: {{ $equipamento->nome }}</h6>
    </div>
    <div class="card-mepi-body">
        <form method="POST" action="{{ route(auth()->user()->role.'.equipamentos.update', $equipamento) }}">
            @csrf @method('PUT')
            @include('equipamentos._form')
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn-mepi">
                    <i class="bi bi-check-lg"></i> Salvar Alterações
                </button>
                <a href="{{ route(auth()->user()->role.'.equipamentos.show', $equipamento) }}"
                   style="padding:9px 20px;border-radius:8px;background:#f0f0e8;color:#555;text-decoration:none;font-size:.85rem;font-weight:600;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
