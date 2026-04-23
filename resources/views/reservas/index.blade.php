@extends('layout.app')
@section('title', 'Reservas de Equipamentos')
@section('page-title', 'Reservas de Equipamentos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-family:'Syne',sans-serif;font-weight:800;color:var(--verde-escuro);margin:0;">Solicitações de Reserva</h5>
        <p style="color:#888;font-size:.85rem;margin:0;">{{ $reservas->total() }} solicitação(ões)</p>
    </div>
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
                    <option value="{{ $f->id }}" {{ request('funcionario_id') == $f->id ? 'selected':'' }}>{{ $f->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" style="font-size:.78rem;font-weight:600;color:#666;margin-bottom:4px;">Equipamento</label>
                <select name="equipamento_id" class="form-select form-select-sm" style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
                    <option value="">Todos</option>
                    @foreach($equipamentos as $e)
                    <option value="{{ $e->id }}" {{ request('equipamento_id') == $e->id ? 'selected':'' }}>{{ $e->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" style="font-size:.78rem;font-weight:600;color:#666;margin-bottom:4px;">Status</label>
                <select name="status" class="form-select form-select-sm" style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
                    <option value="">Todos</option>
                    <option value="pendente" {{ request('status')==='pendente' ? 'selected':'' }}>Pendentes</option>
                    <option value="aprovado" {{ request('status')==='aprovado' ? 'selected':'' }}>Aprovadas</option>
                    <option value="negado"   {{ request('status')==='negado'   ? 'selected':'' }}>Negadas</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn-mepi w-100" style="justify-content:center;"><i class="bi bi-search"></i></button>
                @if(request()->hasAny(['status','funcionario_id','equipamento_id']))
                <a href="{{ route(auth()->user()->role.'.reservas.index') }}"
                   style="padding:8px 12px;border-radius:7px;background:#f0f0e8;color:#666;text-decoration:none;display:flex;align-items:center;">
                    <i class="bi bi-x-lg"></i>
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card-mepi">
    <div class="card-mepi-body p-0">
        <table class="table table-mepi mb-0">
            <thead>
                <tr>
                    <th>Funcionário</th>
                    <th>Equipamento</th>
                    <th>Data solicitada</th>
                    <th>Previsão devolução</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservas as $r)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:.87rem;">{{ $r->funcionario->nome }}</div>
                        <div style="font-size:.73rem;color:#aaa;">{{ $r->funcionario->cargo->nome ?? '' }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:.85rem;">{{ $r->equipamento->nome }}</div>
                        <div style="font-size:.72rem;color:#aaa;font-family:monospace;">{{ $r->equipamento->numero_serie }}</div>
                    </td>
                    <td style="font-size:.83rem;">{{ $r->data_inicio->format('d/m/Y') }}</td>
                    <td style="font-size:.83rem;color:#666;">
                        {{ $r->data_fim ? $r->data_fim->format('d/m/Y') : '—' }}
                    </td>
                    <td>
                        @if($r->reserva_convertida)
                            <span class="badge-ativo">Entregue</span>
                        @else
                            <span class="badge-{{ $r->status }}">{{ ucfirst($r->status) }}</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route(auth()->user()->role.'.reservas.show', $r) }}"
                               class="btn btn-sm"
                               style="background:rgba(26,107,58,.1);color:var(--verde);border:none;border-radius:7px;padding:5px 10px;font-size:.78rem;"
                               title="Ver detalhes">
                                <i class="bi bi-eye"></i>
                            </a>
                            {{-- Converter aprovada em entrega --}}
                            @if($r->isAprovado() && !$r->reserva_convertida && $r->equipamento->estaDisponivel())
                            <form method="POST" action="{{ route(auth()->user()->role.'.reservas.converter', $r) }}"
                                  onsubmit="return confirm('Confirmar entrega do equipamento para {{ $r->funcionario->nome }}?')">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm"
                                        style="background:rgba(245,196,0,.15);color:#8a6d00;border:none;border-radius:7px;padding:5px 10px;font-size:.78rem;"
                                        title="Converter em entrega">
                                    <i class="bi bi-box-seam"></i>
                                </button>
                            </form>
                            @endif
                            {{-- Aprovação rápida --}}
                            @if($r->isPendente())
                            <form method="POST" action="{{ route(auth()->user()->role.'.reservas.update', $r) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="aprovado">
                                <button type="submit"
                                        class="btn btn-sm"
                                        style="background:rgba(26,107,58,.12);color:var(--verde);border:none;border-radius:7px;padding:5px 10px;font-size:.78rem;"
                                        title="Aprovar">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route(auth()->user()->role.'.reservas.update', $r) }}">
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
                    <td colspan="6" class="text-center py-5">
                        <i class="bi bi-box-seam" style="font-size:2rem;color:#ccc;display:block;margin-bottom:8px;"></i>
                        <span style="color:#aaa;font-size:.88rem;">Nenhuma solicitação de reserva encontrada.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($reservas->hasPages())
<div class="mt-3 d-flex justify-content-end">{{ $reservas->links() }}</div>
@endif

@endsection
