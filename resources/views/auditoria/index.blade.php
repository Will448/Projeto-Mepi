{{-- ============================================================
     ARQUIVO 1: resources/views/auditoria/index.blade.php
     ============================================================ --}}
@extends('layout.app')
@section('title', 'Auditoria')
@section('page-title', 'Histórico de Auditoria')

@section('content')

<div class="card-mepi mb-4">
    <div class="card-mepi-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label" style="font-size:.78rem;font-weight:600;color:#666;margin-bottom:4px;">Ação</label>
                <select name="acao" class="form-select form-select-sm" style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
                    <option value="">Todas</option>
                    @foreach($acoes as $a)
                    <option value="{{ $a }}" {{ request('acao') === $a ? 'selected':'' }}>{{ ucfirst($a) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" style="font-size:.78rem;font-weight:600;color:#666;margin-bottom:4px;">Módulo</label>
                <select name="modelo" class="form-select form-select-sm" style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
                    <option value="">Todos</option>
                    @foreach($modelos as $m)
                    <option value="{{ $m }}" {{ request('modelo') === $m ? 'selected':'' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" style="font-size:.78rem;font-weight:600;color:#666;margin-bottom:4px;">Data</label>
                <input type="date" name="data" value="{{ request('data') }}"
                       class="form-control form-control-sm" style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn-mepi w-100" style="justify-content:center;"><i class="bi bi-search"></i></button>
                @if(request()->hasAny(['acao','modelo','data','user_id']))
                <a href="{{ route('admin.auditoria.index') }}"
                   style="padding:7px 12px;border-radius:7px;background:#f0f0e8;color:#666;text-decoration:none;display:flex;align-items:center;">
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
                    <th>Data/Hora</th>
                    <th>Usuário</th>
                    <th>Ação</th>
                    <th>Módulo</th>
                    <th>Descrição</th>
                    <th>IP</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($auditorias as $a)
                <tr>
                    <td style="font-size:.78rem;color:#888;white-space:nowrap;">
                        {{ $a->created_at->format('d/m/Y H:i:s') }}
                    </td>
                    <td style="font-size:.83rem;">{{ $a->user?->name ?? 'Sistema' }}</td>
                    <td>
                        @php
                        $cores = [
                            'created' => 'badge-ativo',
                            'updated' => 'badge-pendente',
                            'deleted' => 'badge-negado',
                            'login'   => 'badge-aprovado',
                            'logout'  => 'badge-inativo',
                        ];
                        @endphp
                        <span class="{{ $cores[$a->acao] ?? '' }}">{{ ucfirst($a->acao) }}</span>
                    </td>
                    <td style="font-size:.82rem;color:#555;">{{ $a->modelo }}</td>
                    <td style="font-size:.82rem;color:#666;max-width:220px;">
                        {{ $a->descricao ? \Illuminate\Support\Str::limit($a->descricao, 60) : '—' }}
                    </td>
                    <td style="font-size:.75rem;color:#aaa;font-family:monospace;">{{ $a->ip }}</td>
                    <td>
                        @if($a->dados_antes || $a->dados_depois)
                        <a href="{{ route('admin.auditoria.show', $a) }}"
                           style="font-size:.75rem;color:var(--verde);text-decoration:none;">
                            <i class="bi bi-eye"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4" style="color:#aaa;font-size:.85rem;">
                        Nenhum registro de auditoria encontrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($auditorias->hasPages())
<div class="mt-3 d-flex justify-content-end">{{ $auditorias->links() }}</div>
@endif

@endsection
