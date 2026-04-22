@extends('layout.app')
@section('title', 'Funcionários')
@section('page-title', 'Funcionários')

@section('content')

{{-- Cabeçalho --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-family:'Syne',sans-serif;font-weight:800;color:var(--verde-escuro);margin:0;">Funcionários</h5>
        <p style="color:#888;font-size:.85rem;margin:0;">{{ $funcionarios->total() }} funcionário(s) encontrado(s)</p>
    </div>
    <a href="{{ route(auth()->user()->role.'.funcionarios.create') }}" class="btn-mepi">
        <i class="bi bi-person-plus"></i> Novo Funcionário
    </a>
</div>

{{-- Filtros --}}
<div class="card-mepi mb-4">
    <div class="card-mepi-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label" style="font-size:.78rem;font-weight:600;color:#666;margin-bottom:4px;">Buscar</label>
                <input type="text" name="busca" value="{{ request('busca') }}"
                       class="form-control form-control-sm"
                       placeholder="Nome, CPF ou e-mail..."
                       style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
            </div>
            <div class="col-md-3">
                <label class="form-label" style="font-size:.78rem;font-weight:600;color:#666;margin-bottom:4px;">Status</label>
                <select name="status" class="form-select form-select-sm" style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
                    <option value="">Todos</option>
                    <option value="ativo"    {{ request('status') === 'ativo'    ? 'selected' : '' }}>Ativo</option>
                    <option value="inativo"  {{ request('status') === 'inativo'  ? 'selected' : '' }}>Inativo</option>
                    <option value="afastado" {{ request('status') === 'afastado' ? 'selected' : '' }}>Afastado</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" style="font-size:.78rem;font-weight:600;color:#666;margin-bottom:4px;">Cargo</label>
                <select name="cargo_id" class="form-select form-select-sm" style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
                    <option value="">Todos os cargos</option>
                    @foreach($cargos as $c)
                    <option value="{{ $c->id }}" {{ request('cargo_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->nome }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn-mepi w-100" style="justify-content:center;">
                    <i class="bi bi-search"></i> Filtrar
                </button>
                @if(request()->hasAny(['busca','status','cargo_id']))
                <a href="{{ route(auth()->user()->role.'.funcionarios.index') }}"
                   style="padding:8px 12px;border-radius:7px;background:#f0f0e8;color:#666;text-decoration:none;font-size:.83rem;white-space:nowrap;display:flex;align-items:center;">
                    <i class="bi bi-x-lg"></i>
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Tabela --}}
<div class="card-mepi">
    <div class="card-mepi-body p-0">
        <table class="table table-mepi mb-0">
            <thead>
                <tr>
                    <th>Funcionário</th>
                    <th>CPF</th>
                    <th>Cargo</th>
                    <th>Salário</th>
                    <th>Admissão</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($funcionarios as $f)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:34px;height:34px;border-radius:50%;background:var(--verde);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.82rem;flex-shrink:0;">
                                {{ strtoupper(substr($f->nome, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:.87rem;">{{ $f->nome }}</div>
                                <div style="font-size:.75rem;color:#888;">{{ $f->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.82rem;color:#555;">{{ $f->cpf }}</td>
                    <td style="font-size:.83rem;">{{ $f->cargo->nome }}</td>
                    <td style="font-size:.83rem;font-weight:600;color:var(--verde);">
                        R$ {{ number_format($f->salario, 2, ',', '.') }}
                    </td>
                    <td style="font-size:.82rem;color:#555;">
                        {{ $f->data_admissao->format('d/m/Y') }}
                    </td>
                    <td>
                        <span class="badge-{{ $f->status }}">{{ ucfirst($f->status) }}</span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route(auth()->user()->role.'.funcionarios.show', $f) }}"
                               class="btn btn-sm"
                               style="background:rgba(26,107,58,.1);color:var(--verde);border:none;border-radius:7px;padding:5px 10px;font-size:.78rem;"
                               title="Ver detalhes">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route(auth()->user()->role.'.funcionarios.edit', $f) }}"
                               class="btn btn-sm"
                               style="background:rgba(245,196,0,.15);color:#8a6d00;border:none;border-radius:7px;padding:5px 10px;font-size:.78rem;"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route(auth()->user()->role.'.funcionarios.destroy', $f) }}"
                                  onsubmit="return confirm('Inativar {{ $f->nome }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm"
                                        style="background:rgba(239,68,68,.1);color:#dc2626;border:none;border-radius:7px;padding:5px 10px;font-size:.78rem;"
                                        title="Inativar">
                                    <i class="bi bi-person-x"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="bi bi-people" style="font-size:2rem;color:#ccc;display:block;margin-bottom:8px;"></i>
                        <span style="color:#aaa;font-size:.88rem;">Nenhum funcionário encontrado.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($funcionarios->hasPages())
<div class="mt-3 d-flex justify-content-end">
    {{ $funcionarios->links() }}
</div>
@endif

@endsection
