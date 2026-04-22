@extends('layout.app')

@section('title', 'Editar Usuário')

@section('content')

<h2 class="mb-4">✏️ Editar Usuário</h2>

<form method="POST" action="{{ route('admin.usuarios.update', $user) }}">   @csrf
    @method('PUT')

    @include('users._form', ['user' => $user])

    <div class="mt-3">
        <button class="btn btn-primary">Atualizar</button>
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
</form>

@endsection