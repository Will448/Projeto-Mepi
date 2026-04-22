{{-- ============================================================
     resources/views/cargos/create.blade.php
     ============================================================ --}}
@extends('layout.app')
@section('title', 'Novo Cargo')
@section('page-title', 'Novo Cargo')

@section('content')
<div style="max-width:560px;">

    <div class="mb-4">
        <a href="{{ route(auth()->user()->role.'.cargos.index') }}"
           style="font-size:.85rem;color:var(--verde);text-decoration:none;">
            <i class="bi bi-arrow-left me-1"></i> Voltar para Cargos
        </a>
    </div>

    <div class="card-mepi">
        <div class="card-mepi-header">
            <h6><i class="bi bi-briefcase me-2"></i>Dados do Cargo</h6>
        </div>
        <div class="card-mepi-body">
            <form method="POST" action="{{ route(auth()->user()->role.'.cargos.store') }}">
                @csrf
                @include('cargos._form')
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-mepi">
                        <i class="bi bi-check-lg"></i> Salvar Cargo
                    </button>
                    <a href="{{ route(auth()->user()->role.'.cargos.index') }}"
                       class="btn btn-sm"
                       style="background:#f0f0e8;color:#555;border:none;padding:9px 18px;border-radius:8px;font-size:.85rem;font-weight:600;">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


{{-- ============================================================
     resources/views/cargos/edit.blade.php
     ============================================================ --}}
{{-- ATENÇÃO: crie um arquivo separado edit.blade.php com este conteúdo --}}

{{--
@extends('layouts.app')
@section('title', 'Editar Cargo')
@section('page-title', 'Editar Cargo')

@section('content')
<div style="max-width:560px;">

    <div class="mb-4">
        <a href="{{ route(auth()->user()->role.'.cargos.index') }}"
           style="font-size:.85rem;color:var(--verde);text-decoration:none;">
            <i class="bi bi-arrow-left me-1"></i> Voltar para Cargos
        </a>
    </div>

    <div class="card-mepi">
        <div class="card-mepi-header">
            <h6><i class="bi bi-pencil me-2"></i>Editar: {{ $cargo->nome }}</h6>
        </div>
        <div class="card-mepi-body">
            <form method="POST" action="{{ route(auth()->user()->role.'.cargos.update', $cargo) }}">
                @csrf @method('PUT')
                @include('cargos._form')
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-mepi">
                        <i class="bi bi-check-lg"></i> Atualizar Cargo
                    </button>
                    <a href="{{ route(auth()->user()->role.'.cargos.index') }}"
                       class="btn btn-sm"
                       style="background:#f0f0e8;color:#555;border:none;padding:9px 18px;border-radius:8px;font-size:.85rem;font-weight:600;">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
--}}
