@extends('layout.app')

@section('title', 'Detalhes')

@section('content')

<h2 class="mb-4">👤 Detalhes</h2>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <p><strong>Nome:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Permissão:</strong> {{ ucfirst($user->role) }}</p>

    </div>
</div>

<a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary mt-3">Voltar</a>

@endsection