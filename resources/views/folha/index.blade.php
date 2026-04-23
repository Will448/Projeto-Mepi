@extends('layout.app')
@section('title', 'Folha de Pagamento')
@section('page-title', 'Folha de Pagamento')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-family:'Syne',sans-serif;font-weight:800;color:var(--verde-escuro);margin:0;">Folhas de Pagamento</h5>
        <p style="color:#888;font-size:.85rem;margin:0;">{{ $folhas->total() }} registro(s) encontrado(s)</p>
    </div>
    <a href="{{ route(auth()->user()->role.'.folha.create') }}" class="btn-mepi">
        <i class="bi bi-plus-lg"></i> Gerar Folha
    </a>
</div>

{{-- Filtros --}}
<div class="card-mepi mb-4">
    <div class="card-mepi-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label" style="font-size:.78rem;font-weight:600;color:#666;margin-bottom:4px;">Funcionário</label>
                <select name="funcionario_id" class="form-select form-select-sm" style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
                    <option value="">Todos</option>
                    @foreach($funcionarios as $f)
                    <option value="{{ $f->id }}" {{ request('funcionario_id') == $f->id ? 'selected':'' }}>{{ $f->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" style="font-size:.78rem;font-weight:600;color:#666;margin-bottom:4px;">Competência</label>
                <select name="competencia" class="form-select form-select-sm" style="border-radius:7px;border-color:#ddd;font-size:.83rem;">
                    <option value="">Todas</option>
                    @foreach($competencias as $c)
                    <option value="{{ $c }}" {{ request('competencia') === $c ? 'selected':'' }}>
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $c)->locale('pt_BR')->isoFormat('MMMM/YYYY') }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn-mepi w-100" style="justify-content:center;"><i class="bi bi-search"></i></button>
                @if(request()->hasAny(['funcionario_id','competencia']))
                <a href="{{ route(auth()->user()->role.'.folha.index') }}"
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
                    <th>Competência</th>
                    <th>Bruto</th>
                    <th>INSS</th>
                    <th>IRRF</th>
                    <th>Férias (+)</th>
                    <th style="color:var(--verde);">Líquido</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($folhas as $f)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:.87rem;">{{ $f->funcionario->nome }}</div>
                        <div style="font-size:.74rem;color:#888;">{{ $f->funcionario->cargo->nome }}</div>
                    </td>
                    <td style="font-size:.83rem;font-weight:600;">{{ $f->competencia_formatada }}</td>
                    <td style="font-size:.83rem;">R$ {{ number_format($f->salario_bruto,2,',','.') }}</td>
                    <td style="font-size:.82rem;color:#dc2626;">- R$ {{ number_format($f->desconto_inss,2,',','.') }}</td>
                    <td style="font-size:.82rem;color:#dc2626;">- R$ {{ number_format($f->desconto_irrf,2,',','.') }}</td>
                    <td style="font-size:.82rem;color:var(--verde);">
                        @if($f->adicional_ferias > 0)
                            + R$ {{ number_format($f->adicional_ferias,2,',','.') }}
                        @else
                            <span style="color:#ccc;">—</span>
                        @endif
                    </td>
                    <td style="font-weight:800;font-size:.9rem;color:var(--verde);">
                        R$ {{ number_format($f->salario_liquido,2,',','.') }}
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route(auth()->user()->role.'.folha.show', $f) }}"
                               class="btn btn-sm"
                               style="background:rgba(26,107,58,.1);color:var(--verde);border:none;border-radius:7px;padding:5px 10px;font-size:.78rem;"
                               title="Ver holerite">
                                <i class="bi bi-receipt-cutoff"></i>
                            </a>
                            <form method="POST" action="{{ route(auth()->user()->role.'.folha.destroy', $f) }}"
                                  onsubmit="return confirm('Excluir folha de {{ $f->funcionario->nome }} ({{ $f->competencia_formatada }})?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm"
                                        style="background:rgba(239,68,68,.1);color:#dc2626;border:none;border-radius:7px;padding:5px 10px;font-size:.78rem;">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="bi bi-receipt-cutoff" style="font-size:2rem;color:#ccc;display:block;margin-bottom:8px;"></i>
                        <span style="color:#aaa;font-size:.88rem;">Nenhuma folha gerada ainda.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($folhas->hasPages())
<div class="mt-3 d-flex justify-content-end">{{ $folhas->links() }}</div>
@endif

@endsection
