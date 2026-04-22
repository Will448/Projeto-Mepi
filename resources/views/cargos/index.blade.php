@extends('layout.app')
@section('title', 'Cargos')
@section('page-title', 'Cargos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-family:'Syne',sans-serif;font-weight:800;color:var(--verde-escuro);margin:0;">Cargos Cadastrados</h5>
        <p style="color:#888;font-size:.85rem;margin:0;">{{ $cargos->total() }} cargo(s) encontrado(s)</p>
    </div>
    <a href="{{ route(auth()->user()->role.'.cargos.create') }}" class="btn-mepi">
        <i class="bi bi-plus-lg"></i> Novo Cargo
    </a>
</div>

<div class="card-mepi">
    <div class="card-mepi-body p-0">
        <table class="table table-mepi mb-0">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Salário Base</th>
                    <th>Funcionários</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cargos as $cargo)
                <tr>
                    <td style="font-weight:600;">{{ $cargo->nome }}</td>
                    <td style="color:#666;font-size:.83rem;">{{ $cargo->descricao ?? '—' }}</td>
                    <td style="font-weight:600;color:var(--verde);">
                        R$ {{ number_format($cargo->salario_base, 2, ',', '.') }}
                    </td>
                    <td>
                        <span style="background:rgba(26,107,58,.1);color:var(--verde);padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600;">
                            {{ $cargo->funcionarios_count }} pessoa(s)
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route(auth()->user()->role.'.cargos.edit', $cargo) }}"
                               class="btn btn-sm"
                               style="background:rgba(245,196,0,.15);color:#8a6d00;border:none;border-radius:7px;padding:5px 12px;font-size:.78rem;">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <form method="POST" action="{{ route(auth()->user()->role.'.cargos.destroy', $cargo) }}"
                                  onsubmit="return confirm('Excluir o cargo {{ $cargo->nome }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm"
                                        style="background:rgba(239,68,68,.1);color:#dc2626;border:none;border-radius:7px;padding:5px 12px;font-size:.78rem;">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="bi bi-briefcase" style="font-size:2rem;color:#ccc;display:block;margin-bottom:8px;"></i>
                        <span style="color:#aaa;font-size:.88rem;">Nenhum cargo cadastrado ainda.</span><br>
                        <a href="{{ route(auth()->user()->role.'.cargos.create') }}" class="btn-mepi mt-3 d-inline-flex">
                            <i class="bi bi-plus-lg"></i> Criar primeiro cargo
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Paginação --}}
@if($cargos->hasPages())
<div class="mt-3 d-flex justify-content-end">
    {{ $cargos->links() }}
</div>
@endif

@endsection
