@extends('layout.app')
@section('title', 'Férias')
@section('page-title', 'Controle de Férias')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-family:'Syne',sans-serif;font-weight:800;color:var(--verde-escuro);margin:0;">Solicitações de Férias</h5>
        <p style="color:#888;font-size:.85rem;margin:0;">{{ $ferias->total() }} solicitação(ões) encontrada(s)</p>
    </div>
    <a href="{{ route(auth()->user()->role.'.ferias.create') }}" class="btn-mepi">
        <i class="bi bi-plus-lg"></i> Cadastrar Férias
    </a>
</div>

{{-- Filtros --}}
<div class="card-mepi mb-4">
    <div class="card-mepi-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label" style="font-size:.78rem;font-weight:600;color:#666;margin-bottom:4px;">Funcionário</label>
                <select name="funcionario_id" class="form-select form-select-sm" style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
                    <option value="">Todos os funcionários</option>
                    @foreach($funcionarios as $f)
                    <option value="{{ $f->id }}" {{ request('funcionario_id') == $f->id ? 'selected' : '' }}>
                        {{ $f->nome }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" style="font-size:.78rem;font-weight:600;color:#666;margin-bottom:4px;">Status</label>
                <select name="status" class="form-select form-select-sm" style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
                    <option value="">Todos</option>
                    <option value="pendente"  {{ request('status') === 'pendente'  ? 'selected':'' }}>Pendentes</option>
                    <option value="aprovado"  {{ request('status') === 'aprovado'  ? 'selected':'' }}>Aprovadas</option>
                    <option value="negado"    {{ request('status') === 'negado'    ? 'selected':'' }}>Negadas</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn-mepi w-100" style="justify-content:center;">
                    <i class="bi bi-search"></i> Filtrar
                </button>
                @if(request()->hasAny(['status','funcionario_id']))
                <a href="{{ route(auth()->user()->role.'.ferias.index') }}"
                   style="padding:8px 12px;border-radius:7px;background:#f0f0e8;color:#666;text-decoration:none;font-size:.83rem;display:flex;align-items:center;">
                    <i class="bi bi-x-lg"></i>
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Listagem --}}
<div class="card-mepi">
    <div class="card-mepi-body p-0">
        <table class="table table-mepi mb-0">
            <thead>
                <tr>
                    <th>Funcionário</th>
                    <th>Período de Gozo</th>
                    <th>Dias</th>
                    <th>Abono</th>
                    <th>Período Aquisitivo</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ferias as $f)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:.87rem;">{{ $f->funcionario->nome }}</div>
                        <div style="font-size:.74rem;color:#888;">{{ $f->funcionario->cargo->nome }}</div>
                    </td>
                    <td style="font-size:.83rem;">
                        {{ $f->data_inicio->format('d/m/Y') }} →
                        {{ $f->data_fim->format('d/m/Y') }}
                    </td>
                    <td>
                        <span style="font-weight:700;font-size:.85rem;color:var(--verde-escuro);">
                            {{ $f->dias_gozados }}d
                        </span>
                    </td>
                    <td style="font-size:.82rem;">
                        @if($f->abono_pecuniario)
                            <span class="badge-pendente">{{ $f->dias_abono }}d vendidos</span>
                        @else
                            <span style="color:#aaa;">—</span>
                        @endif
                    </td>
                    <td style="font-size:.78rem;color:#666;">
                        {{ $f->periodo_aquisitivo_inicio->format('d/m/Y') }}<br>
                        {{ $f->periodo_aquisitivo_fim->format('d/m/Y') }}
                    </td>
                    <td>
                        <span class="badge-{{ $f->status }}">{{ ucfirst($f->status) }}</span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route(auth()->user()->role.'.ferias.show', $f) }}"
                               class="btn btn-sm"
                               style="background:rgba(26,107,58,.1);color:var(--verde);border:none;border-radius:7px;padding:5px 10px;font-size:.78rem;"
                               title="Detalhes">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($f->isPendente())
                            <a href="{{ route(auth()->user()->role.'.ferias.edit', $f) }}"
                                    class="btn btn-sm"
                                    style="background:rgba(234,179,8,.15);color:#b45309;border:none;border-radius:7px;padding:5px 10px;font-size:.78rem;"
                                    title="Editar datas">
                                        <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route(auth()->user()->role.'.ferias.update', $f) }}">
                                @csrf @method('PUT')
                                
                                <input type="hidden" name="status" value="aprovado">
                                <button type="submit"
                                        class="btn btn-sm"
                                        style="background:rgba(26,107,58,.12);color:var(--verde);border:none;border-radius:7px;padding:5px 10px;font-size:.78rem;"
                                        title="Aprovar">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route(auth()->user()->role.'.ferias.update', $f) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="negado">
                                <button type="submit"
                                        class="btn btn-sm"
                                        style="background:rgba(239,68,68,.1);color:#dc2626;border:none;border-radius:7px;padding:5px 10px;font-size:.78rem;"
                                        title="Negar">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="bi bi-calendar-heart" style="font-size:2rem;color:#ccc;display:block;margin-bottom:8px;"></i>
                        <span style="color:#aaa;font-size:.88rem;">Nenhuma solicitação encontrada.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($ferias->hasPages())
<div class="mt-3 d-flex justify-content-end">{{ $ferias->links() }}</div>
@endif

@endsection
