@extends('layout.app')

@section('title', 'Novo Usuário')

@section('content')

<h2 class="mb-4">➕ Novo Usuário</h2>

<form method="POST" action="{{ route('admin.usuarios.store') }}">
    @csrf

    @include('users._form')

    <div class="mt-3">
        <button class="btn btn-success">Salvar</button>
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
</form>

@endsection