@extends('layout.app')
@section('title', 'Equipamentos')
@section('page-title', 'Equipamentos e EPIs')

@section('content')

{{-- Cards de resumo --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon icon-blue"><i class="bi bi-shield-check"></i></div>
            <div>
                <div class="metric-val">{{ $totais['total'] }}</div>
                <div class="metric-lbl">Total cadastrado</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon icon-green"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="metric-val">{{ $totais['disponivel'] }}</div>
                <div class="metric-lbl">Disponíveis</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon icon-yellow"><i class="bi bi-person-check"></i></div>
            <div>
                <div class="metric-val">{{ $totais['entregue'] }}</div>
                <div class="metric-lbl">Em uso</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="metric-card">
            <div class="metric-icon icon-red"><i class="bi bi-tools"></i></div>
            <div>
                <div class="metric-val">{{ $totais['manutencao'] }}</div>
                <div class="metric-lbl">Em manutenção</div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-family:'Syne',sans-serif;font-weight:800;color:var(--verde-escuro);margin:0;">Equipamentos Cadastrados</h5>
        <p style="color:#888;font-size:.85rem;margin:0;">{{ $equipamentos->total() }} item(ns) encontrado(s)</p>
    </div>
    <a href="{{ route(auth()->user()->role.'.equipamentos.create') }}" class="btn-mepi">
        <i class="bi bi-plus-lg"></i> Novo Equipamento
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
                       placeholder="Nome ou nº de série..."
                       style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
            </div>
            <div class="col-md-3">
                <label class="form-label" style="font-size:.78rem;font-weight:600;color:#666;margin-bottom:4px;">Tipo</label>
                <select name="tipo" class="form-select form-select-sm" style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
                    <option value="">Todos os tipos</option>
                    @foreach($tipos as $t)
                    <option value="{{ $t }}" {{ request('tipo') === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" style="font-size:.78rem;font-weight:600;color:#666;margin-bottom:4px;">Status</label>
                <select name="status" class="form-select form-select-sm" style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
                    <option value="">Todos</option>
                    <option value="disponivel"  {{ request('status') === 'disponivel'  ? 'selected':'' }}>Disponível</option>
                    <option value="entregue"    {{ request('status') === 'entregue'    ? 'selected':'' }}>Em uso</option>
                    <option value="manutencao"  {{ request('status') === 'manutencao'  ? 'selected':'' }}>Manutenção</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn-mepi w-100" style="justify-content:center;">
                    <i class="bi bi-search"></i>
                </button>
                @if(request()->hasAny(['busca','tipo','status']))
                <a href="{{ route(auth()->user()->role.'.equipamentos.index') }}"
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
                    <th>Equipamento</th>
                    <th>Nº de Série</th>
                    <th>Tipo</th>
                    <th>Validade</th>
                    <th>Usos</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($equipamentos as $eq)
                @php
                    $vencido  = $eq->validade && $eq->validade->isPast();
                    $vencendo = $eq->validade && !$vencido && $eq->validade->diffInDays(now()) >= -30;
                @endphp
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:.87rem;">{{ $eq->nome }}</div>
                        @if($eq->em_uso_count > 0)
                            <div style="font-size:.72rem;color:var(--verde);">
                                <i class="bi bi-person-check me-1"></i>Em uso por {{ $eq->em_uso_count }} pessoa(s)
                            </div>
                        @endif
                    </td>
                    <td style="font-size:.82rem;font-family:monospace;color:#555;">{{ $eq->numero_serie }}</td>
                    <td>
                        <span style="background:#f0f0e8;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600;color:#555;">
                            {{ $eq->tipo }}
                        </span>
                    </td>
                    <td style="font-size:.82rem;">
                        @if($eq->validade)
                            <span style="color:{{ $vencido ? '#dc2626' : ($vencendo ? '#b08c00' : '#555') }};font-weight:{{ $vencido || $vencendo ? '600' : '400' }};">
                                @if($vencido)<i class="bi bi-exclamation-circle me-1"></i>@endif
                                {{ $eq->validade->format('d/m/Y') }}
                            </span>
                        @else
                            <span style="color:#ccc;">—</span>
                        @endif
                    </td>
                    <td style="font-size:.82rem;color:#666;">{{ $eq->entregas_count }}x</td>
                    <td>
                        @php
                        $statusCor = match($eq->status) {
                            'disponivel' => 'badge-ativo',
                            'entregue'   => 'badge-pendente',
                            'manutencao' => 'badge-negado',
                            default      => ''
                        };
                        $statusLabel = match($eq->status) {
                            'disponivel' => 'Disponível',
                            'entregue'   => 'Em uso',
                            'manutencao' => 'Manutenção',
                            default      => $eq->status
                        };
                        @endphp
                        <span class="{{ $statusCor }}">{{ $statusLabel }}</span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route(auth()->user()->role.'.equipamentos.show', $eq) }}"
                               class="btn btn-sm"
                               style="background:rgba(26,107,58,.1);color:var(--verde);border:none;border-radius:7px;padding:5px 10px;font-size:.78rem;"
                               title="Ver histórico">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route(auth()->user()->role.'.equipamentos.edit', $eq) }}"
                               class="btn btn-sm"
                               style="background:rgba(245,196,0,.15);color:#8a6d00;border:none;border-radius:7px;padding:5px 10px;font-size:.78rem;"
                               title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route(auth()->user()->role.'.equipamentos.destroy', $eq) }}"
                                  onsubmit="return confirm('Excluir {{ $eq->nome }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm"
                                        style="background:rgba(239,68,68,.1);color:#dc2626;border:none;border-radius:7px;padding:5px 10px;font-size:.78rem;"
                                        title="Excluir">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="bi bi-shield-check" style="font-size:2rem;color:#ccc;display:block;margin-bottom:8px;"></i>
                        <span style="color:#aaa;font-size:.88rem;">Nenhum equipamento cadastrado.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($equipamentos->hasPages())
<div class="mt-3 d-flex justify-content-end">{{ $equipamentos->links() }}</div>
@endif

@endsection
