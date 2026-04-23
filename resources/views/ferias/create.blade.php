@extends('layout.app')
@section('title', 'Cadastrar Férias')
@section('page-title', 'Controle de Férias')

@section('content')

<div class="mb-4">
    <a href="{{ route(auth()->user()->role.'.ferias.index') }}" style="color:#888;text-decoration:none;font-size:.85rem;">
        <i class="bi bi-arrow-left me-1"></i>Voltar para lista
    </a>
</div>

<div class="row g-4" style="max-width:960px;">

    {{-- Formulário --}}
    <div class="col-lg-7">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-calendar-plus me-2"></i>Cadastrar Férias</h6>
            </div>
            <div class="card-mepi-body">

                @if($errors->any())
                <div class="alert-mepi-error mb-3">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $e)
                            <li style="font-size:.83rem;">{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="GET" class="mb-4">
                    <label class="form-label" style="font-size:.83rem;font-weight:600;color:#444;">
                        Selecione o Funcionário *
                    </label>
                    <div class="d-flex gap-2">
                        <select name="funcionario_id"
                                class="form-select"
                                style="border-radius:8px;border-color:#ddd;font-size:.85rem;">
                            <option value="">Selecione...</option>
                            @foreach($funcionarios as $f)
                            <option value="{{ $f->id }}"
                                {{ ($funcionario && $funcionario->id == $f->id) ? 'selected' : '' }}>
                                {{ $f->nome }} — {{ $f->cargo->nome }}
                            </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-mepi" style="white-space:nowrap;">
                            <i class="bi bi-search"></i> Carregar
                        </button>
                    </div>
                </form>

                @if($funcionario)
                <form method="POST" action="{{ route(auth()->user()->role.'.ferias.store') }}">
                    @csrf
                    <input type="hidden" name="funcionario_id" value="{{ $funcionario->id }}">

                    {{-- Período aquisitivo --}}
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.83rem;font-weight:600;color:#444;">
                            Período Aquisitivo *
                        </label>
                        <select name="periodo_aquisitivo_inicio" id="selectPeriodo"
                                class="form-select @error('periodo_aquisitivo_inicio') is-invalid @enderror"
                                style="border-radius:8px;border-color:#ddd;font-size:.85rem;">
                            <option value="">Selecione...</option>
                          @foreach($periodos as $p)
                            @if($p['saldo_disponivel'] > 0)
                            <option value="{{ $p['periodo_aquisitivo_inicio'] }}"
                                {{ old('periodo_aquisitivo_inicio') === $p['periodo_aquisitivo_inicio'] ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($p['periodo_aquisitivo_inicio'])->format('d/m/Y') }}
                                → {{ \Carbon\Carbon::parse($p['periodo_aquisitivo_fim'])->format('d/m/Y') }}
                                ({{ $p['saldo_disponivel'] }}d disponíveis{{ $p['vencido'] ? ' — Vencido' : '' }})
                            </option>
                            @endif
                            @endforeach
                                                    </select>
                        @error('periodo_aquisitivo_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Datas --}}
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label" style="font-size:.83rem;font-weight:600;color:#444;">Início *</label>
                            <input type="date" name="data_inicio" id="dataInicio"
                                   value="{{ old('data_inicio') }}"
                                   class="form-control @error('data_inicio') is-invalid @enderror"
                                   style="border-radius:8px;border-color:#ddd;font-size:.85rem;">
                            @error('data_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label" style="font-size:.83rem;font-weight:600;color:#444;">Fim *</label>
                            <input type="date" name="data_fim" id="dataFim"
                                   value="{{ old('data_fim') }}"
                                   class="form-control @error('data_fim') is-invalid @enderror"
                                   style="border-radius:8px;border-color:#ddd;font-size:.85rem;">
                            @error('data_fim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Contador de dias --}}
                    <div id="contadorDias" style="display:none;background:rgba(26,107,58,.07);border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:.83rem;color:var(--verde);font-weight:600;">
                        <i class="bi bi-calendar-check me-1"></i>
                        <span id="txtDias">0 dias selecionados</span>
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.83rem;font-weight:600;color:#444;">Status *</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror"
                                style="border-radius:8px;border-color:#ddd;font-size:.85rem;">
                            <option value="pendente"  {{ old('status','pendente') === 'pendente'  ? 'selected':'' }}>Pendente</option>
                            <option value="aprovado"  {{ old('status') === 'aprovado'  ? 'selected':'' }}>Aprovado</option>
                            <option value="negado"    {{ old('status') === 'negado'    ? 'selected':'' }}>Negado</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Abono pecuniário --}}
                    <div class="mb-3">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.85rem;font-weight:600;color:#444;">
                            <input type="checkbox" name="abono_pecuniario" value="1" id="ckAbono"
                                   {{ old('abono_pecuniario') ? 'checked':'' }}
                                   style="width:16px;height:16px;accent-color:var(--verde);">
                            Abono pecuniário (vender dias)
                        </label>
                        <p style="font-size:.73rem;color:#aaa;margin:4px 0 0 24px;">Até 10 dos 30 dias (1/3 do salário)</p>
                    </div>

                    <div id="abonoField" style="{{ old('abono_pecuniario') ? '' : 'display:none;' }}margin-bottom:16px;">
                        <label class="form-label" style="font-size:.83rem;font-weight:600;color:#444;">Dias a vender (máx. 10)</label>
                        <input type="number" name="dias_abono" id="diasAbono"
                               value="{{ old('dias_abono', 0) }}"
                               min="0" max="10"
                               class="form-control"
                               style="border-radius:8px;border-color:#ddd;font-size:.85rem;max-width:120px;">
                    </div>

                    {{-- Observação --}}
                    <div class="mb-4">
                        <label class="form-label" style="font-size:.83rem;font-weight:600;color:#444;">Observação</label>
                        <textarea name="observacao" rows="2" class="form-control"
                                  style="border-radius:8px;border-color:#ddd;font-size:.85rem;resize:vertical;"
                                  placeholder="Opcional...">{{ old('observacao') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-mepi">
                            <i class="bi bi-check-lg"></i> Cadastrar Férias
                        </button>
                        <a href="{{ route(auth()->user()->role.'.ferias.index') }}"
                           style="padding:9px 18px;border-radius:8px;background:#f0f0e8;color:#666;text-decoration:none;font-size:.85rem;display:flex;align-items:center;gap:6px;">
                            <i class="bi bi-x-lg"></i> Cancelar
                        </a>
                    </div>
                </form>
                @else
                <div style="background:#f7f5ee;border-radius:10px;padding:24px;text-align:center;color:#888;font-size:.88rem;">
                    <i class="bi bi-person-circle" style="font-size:2rem;color:#ccc;display:block;margin-bottom:8px;"></i>
                    Selecione um funcionário acima para carregar os períodos disponíveis.
                </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Saldo do funcionário selecionado --}}
    @if($funcionario && count($periodos) > 0)
    <div class="col-lg-5">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-clock-history me-2"></i>Saldo de {{ $funcionario->nome }}</h6>
            </div>
            <div class="card-mepi-body p-0">
                @foreach($periodos as $p)
                @php $cor = $p['vencido'] ? '#dc2626' : ($p['saldo_disponivel'] > 0 ? 'var(--verde)' : '#aaa'); @endphp
                <div style="padding:12px 16px;border-bottom:1px solid #f0f0e8;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                        <span style="font-size:.78rem;color:#555;">
                            {{ \Carbon\Carbon::parse($p['periodo_aquisitivo_inicio'])->format('d/m/Y') }}
                            → {{ \Carbon\Carbon::parse($p['periodo_aquisitivo_fim'])->format('d/m/Y') }}
                        </span>
                        @if($p['vencido'])
                            <span style="font-size:.7rem;background:rgba(239,68,68,.1);color:#dc2626;padding:2px 8px;border-radius:10px;font-weight:600;">Vencido</span>
                        @endif
                    </div>
                    <div style="display:flex;gap:10px;font-size:.78rem;">
                        <span style="color:#888;">Direito: <strong style="color:#333;">30d</strong></span>
                        <span style="color:#888;">Gozado: <strong style="color:#333;">{{ $p['dias_gozados'] }}d</strong></span>
                        <span style="color:#888;">Saldo: <strong style="color:{{ $cor }};">{{ $p['saldo_disponivel'] }}d</strong></span>
                    </div>
                    <div style="height:4px;background:#f0f0e8;border-radius:4px;margin-top:8px;overflow:hidden;">
                        <div style="height:100%;width:{{ min(100,($p['dias_gozados']/30)*100) }}%;background:{{ $cor }};border-radius:4px;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>

@endsection

@push('scripts')
<script>
function atualizarDias() {
    const ini = document.getElementById('dataInicio')?.value;
    const fim = document.getElementById('dataFim')?.value;
    const div = document.getElementById('contadorDias');
    const txt = document.getElementById('txtDias');
    if (ini && fim) {
        const dias = Math.round((new Date(fim) - new Date(ini)) / 86400000) + 1;
        if (dias > 0) {
            txt.textContent = dias + ' dia' + (dias > 1 ? 's' : '') + ' selecionado' + (dias > 1 ? 's' : '');
            div.style.display = '';
            document.getElementById('dataFim').min = ini;
            return;
        }
    }
    div.style.display = 'none';
}
document.getElementById('dataInicio')?.addEventListener('change', atualizarDias);
document.getElementById('dataFim')?.addEventListener('change', atualizarDias);
document.getElementById('ckAbono')?.addEventListener('change', function () {
    document.getElementById('abonoField').style.display = this.checked ? '' : 'none';
});
</script>
@endpush