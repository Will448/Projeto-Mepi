@extends('layout.app')

@section('title', 'Detalhes do Usuário')

@section('content')

<h2 class="mb-4">👤 Detalhes do Usuário</h2>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <strong>ID:</strong> {{ $user->id }}
            </li>
            <li class="list-group-item">
                <strong>Nome:</strong> {{ $user->name }}
            </li>
            <li class="list-group-item">
                <strong>Email:</strong> {{ $user->email }}
            </li>
        </ul>

    </div>
</div>

<a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary mt-3">
    Voltar
</a>

@endsection