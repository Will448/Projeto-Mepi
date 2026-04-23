@extends('layout.app')
@section('title', 'Entregas de EPI')
@section('page-title', 'Controle de Entregas EPI')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-family:'Syne',sans-serif;font-weight:800;color:var(--verde-escuro);margin:0;">Entregas de Equipamentos</h5>
        <p style="color:#888;font-size:.85rem;margin:0;">{{ $entregas->total() }} registro(s)</p>
    </div>
    <a href="{{ route(auth()->user()->role.'.entregas.create') }}" class="btn-mepi">
        <i class="bi bi-box-seam"></i> Registrar Entrega
    </a>
</div>

{{-- Filtros --}}
<div class="card-mepi mb-4">
    <div class="card-mepi-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label" style="font-size:.78rem;font-weight:600;color:#666;margin-bottom:4px;">Funcionário</label>
                <select name="funcionario_id" class="form-select form-select-sm" style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
                    <option value="">Todos</option>
                    @foreach($funcionarios as $f)
                    <option value="{{ $f->id }}" {{ request('funcionario_id') == $f->id ? 'selected':'' }}>
                        {{ $f->nome }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" style="font-size:.78rem;font-weight:600;color:#666;margin-bottom:4px;">Equipamento</label>
                <select name="equipamento_id" class="form-select form-select-sm" style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
                    <option value="">Todos</option>
                    @foreach($equipamentos as $e)
                    <option value="{{ $e->id }}" {{ request('equipamento_id') == $e->id ? 'selected':'' }}>
                        {{ $e->nome }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" style="font-size:.78rem;font-weight:600;color:#666;margin-bottom:4px;">Situação</label>
                <select name="situacao" class="form-select form-select-sm" style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
                    <option value="">Todas</option>
                    <option value="em_uso"    {{ request('situacao') === 'em_uso'    ? 'selected':'' }}>Em uso</option>
                    <option value="devolvido" {{ request('situacao') === 'devolvido' ? 'selected':'' }}>Devolvido</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn-mepi w-100" style="justify-content:center;">
                    <i class="bi bi-search"></i>
                </button>
                @if(request()->hasAny(['funcionario_id','equipamento_id','situacao']))
                <a href="{{ route(auth()->user()->role.'.entregas.index') }}"
                   style="padding:8px 12px;border-radius:7px;background:#f0f0e8;color:#666;text-decoration:none;font-size:.83rem;display:flex;align-items:center;">
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
                    <th>Equipamento</th>
                    <th>Entregue em</th>
                    <th>Devolvido em</th>
                    <th>Tempo</th>
                    <th>Situação</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entregas as $e)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:.87rem;">{{ $e->funcionario->nome }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:.85rem;">{{ $e->equipamento->nome }}</div>
                        <div style="font-size:.72rem;color:#888;font-family:monospace;">{{ $e->equipamento->numero_serie }}</div>
                    </td>
                    <td style="font-size:.83rem;">{{ $e->data_entrega->format('d/m/Y') }}</td>
                    <td style="font-size:.83rem;color:#666;">
                        {{ $e->data_devolucao ? $e->data_devolucao->format('d/m/Y') : '—' }}
                    </td>
                    <td style="font-size:.8rem;color:#888;">
                        @if($e->data_devolucao)
                            {{ $e->data_entrega->diffInDays($e->data_devolucao) }}d
                        @else
                            {{ $e->data_entrega->diffInDays(now()) }}d <span style="color:var(--verde);font-size:.7rem;">(ativo)</span>
                        @endif
                    </td>
                    <td>
                        @if($e->foidevolvido())
                            <span class="badge-ativo">Devolvido</span>
                        @else
                            <span class="badge-pendente">Em uso</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route(auth()->user()->role.'.entregas.show', $e) }}"
                               class="btn btn-sm"
                               style="background:rgba(26,107,58,.1);color:var(--verde);border:none;border-radius:7px;padding:5px 10px;font-size:.78rem;">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(!$e->foidevolvido())
                            <a href="{{ route(auth()->user()->role.'.entregas.edit', $e) }}"
                               class="btn btn-sm"
                               style="background:rgba(245,196,0,.15);color:#8a6d00;border:none;border-radius:7px;padding:5px 10px;font-size:.78rem;"
                               title="Registrar devolução">
                                <i class="bi bi-arrow-return-left"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="bi bi-box-seam" style="font-size:2rem;color:#ccc;display:block;margin-bottom:8px;"></i>
                        <span style="color:#aaa;font-size:.88rem;">Nenhuma entrega registrada ainda.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($entregas->hasPages())
<div class="mt-3 d-flex justify-content-end">{{ $entregas->links() }}</div>
@endif

@endsection
