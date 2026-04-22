@extends('layout.app')

@section('title', 'Usuários')

@section('content')

<h2 class="mb-4">👤 Usuários</h2>

<a href="{{ route('admin.usuarios.create') }}" class="btn btn-success mb-4">
    + Novo Usuário
</a>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">

        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Funcionário</th>
                    <th>Email</th>
                    <th>Permissão</th>
                    <th width="180">Ações</th>
                </tr>
            </thead>

            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">

                            {{-- Avatar --}}
                            <div class="rounded-circle bg-success text-white d-flex justify-content-center align-items-center"
                                 style="width:40px;height:40px;font-weight:bold;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>

                            <div>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>

                        </div>
                    </td>

                    <td class="text-muted">
                        {{ $user->email }}
                    </td>
                    <td>
                        <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'secondary' }}">
                            {{ ucfirst($user->role) }}
                        </span>

                    </td>
                    <td>
                        <div class="d-flex gap-2">

                            <a href="{{ route('admin.usuarios.show', $user->id) }}"
                               class="btn btn-light btn-sm border">
                                👁
                            </a>

                            <a href="{{ route('admin.usuarios.edit', $user->id) }}"
                               class="btn btn-warning btn-sm">
                                ✏️
                            </a>

                            <form action="{{ route('admin.usuarios.destroy', $user->id) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm">
                                    🗑
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>

    </div>
</div>

<div class="mt-3">
    {{ $users->links() }}
</div>

@endsection