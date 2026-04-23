@extends('layout.app')
@section('title', 'Minhas Férias')
@section('page-title', 'Minhas Férias')

@section('content')

{{-- Saldo por período --}}
<div class="row g-3 mb-4">
    @forelse($periodos as $p)
    @php $cor = $p['vencido'] ? '#dc2626' : ($p['saldo_disponivel'] > 0 ? 'var(--verde)' : '#aaa'); @endphp
    <div class="col-md-4">
        <div class="card-mepi">
            <div class="card-mepi-body">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
                    <div>
                        <p style="font-size:.72rem;color:#888;margin:0;text-transform:uppercase;letter-spacing:.5px;">Período Aquisitivo</p>
                        <p style="font-size:.82rem;font-weight:600;color:#333;margin:2px 0 0;">
                            {{ \Carbon\Carbon::parse($p['periodo_aquisitivo_inicio'])->format('d/m/Y') }}
                            →
                            {{ \Carbon\Carbon::parse($p['periodo_aquisitivo_fim'])->format('d/m/Y') }}
                        </p>
                    </div>
                    @if($p['vencido'])
                        <span style="font-size:.68rem;background:rgba(239,68,68,.1);color:#dc2626;padding:2px 8px;border-radius:10px;font-weight:700;">Vencido</span>
                    @endif
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                    <span style="font-size:.78rem;color:#888;">Gozado</span>
                    <span style="font-size:.85rem;font-weight:700;color:#555;">{{ $p['dias_gozados'] }}d</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                    <span style="font-size:.78rem;color:#888;">Saldo disponível</span>
                    <span style="font-size:1.1rem;font-weight:800;color:{{ $cor }};">{{ $p['saldo_disponivel'] }}d</span>
                </div>
                <div style="height:5px;background:#f0f0e8;border-radius:5px;overflow:hidden;">
                    <div style="height:100%;width:{{ min(100,($p['dias_gozados']/30)*100) }}%;background:{{ $cor }};border-radius:5px;transition:width .4s;"></div>
                </div>
                @if($p['saldo_disponivel'] > 0 && !$p['vencido'])
                <button onclick="preencherPeriodo('{{ $p['periodo_aquisitivo_inicio'] }}')"
                        class="btn-mepi btn-mepi-amarelo w-100 mt-3" style="justify-content:center;font-size:.8rem;">
                    <i class="bi bi-plus-lg"></i> Solicitar deste período
                </button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div style="background:#f7f5ee;border-radius:12px;padding:24px;text-align:center;color:#888;font-size:.88rem;">
            <i class="bi bi-calendar-x" style="font-size:2rem;color:#ccc;display:block;margin-bottom:8px;"></i>
            Nenhum período aquisitivo completo ainda. Você precisa de 12 meses trabalhados para adquirir férias.
        </div>
    </div>
    @endforelse
</div>

<div class="row g-4">

    {{-- Formulário de solicitação --}}
    @if(count($periodos) > 0)
    <div class="col-lg-5">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-send me-2"></i>Nova Solicitação</h6>
            </div>
            <div class="card-mepi-body">
                @if($errors->any())
                <div class="alert-mepi-error mb-3">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $e)<li style="font-size:.83rem;">{{ $e }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('funcionario.ferias.solicitar') }}">
                    @csrf

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
                            @if($p['saldo_disponivel'] > 0 && !$p['vencido'])
                            <option value="{{ $p['periodo_aquisitivo_inicio'] }}"
                                {{ old('periodo_aquisitivo_inicio') === $p['periodo_aquisitivo_inicio'] ? 'selected':'' }}>
                                {{ \Carbon\Carbon::parse($p['periodo_aquisitivo_inicio'])->format('d/m/Y') }}
                                → {{ \Carbon\Carbon::parse($p['periodo_aquisitivo_fim'])->format('d/m/Y') }}
                                ({{ $p['saldo_disponivel'] }}d disponíveis)
                            </option>
                            @endif
                            @endforeach
                        </select>
                        @error('periodo_aquisitivo_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Datas --}}
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label" style="font-size:.83rem;font-weight:600;color:#444;">Início *</label>
                            <input type="date" name="data_inicio" id="dataInicio"
                                   value="{{ old('data_inicio') }}"
                                   min="{{ now()->addDay()->format('Y-m-d') }}"
                                   class="form-control @error('data_inicio') is-invalid @enderror"
                                   style="border-radius:8px;border-color:#ddd;font-size:.85rem;">
                            @error('data_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label" style="font-size:.83rem;font-weight:600;color:#444;">Fim *</label>
                            <input type="date" name="data_fim" id="dataFim"
                                   value="{{ old('data_fim') }}"
                                   class="form-control @error('data_fim') is-invalid @enderror"
                                   style="border-radius:8px;border-color:#ddd;font-size:.85rem;">
                            @error('data_fim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Contador de dias --}}
                    <div id="contadorDias" style="display:none;background:rgba(26,107,58,.07);border-radius:8px;padding:10px 14px;margin-bottom:12px;font-size:.83rem;color:var(--verde);font-weight:600;">
                        <i class="bi bi-calendar-check me-1"></i>
                        <span id="txtDias">0 dias selecionados</span>
                    </div>

                    {{-- Abono pecuniário --}}
                    <div class="mb-3">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.85rem;font-weight:600;color:#444;">
                            <input type="checkbox" name="abono_pecuniario" value="1" id="ckAbono"
                                   {{ old('abono_pecuniario') ? 'checked':'' }}
                                   style="width:16px;height:16px;accent-color:var(--verde);">
                            Solicitar abono pecuniário (vender dias)
                        </label>
                        <p style="font-size:.73rem;color:#aaa;margin:4px 0 0 24px;">Você pode vender até 10 dos 30 dias (1/3 do salário pelos dias vendidos)</p>
                    </div>

                    <div id="abonoField" style="{{ old('abono_pecuniario') ? '':'display:none;' }}margin-bottom:12px;">
                        <label class="form-label" style="font-size:.83rem;font-weight:600;color:#444;">Quantos dias vender? (máx. 10)</label>
                        <input type="number" name="dias_abono" id="diasAbono"
                               value="{{ old('dias_abono', 0) }}"
                               min="0" max="10"
                               class="form-control"
                               style="border-radius:8px;border-color:#ddd;font-size:.85rem;max-width:120px;">
                    </div>

                    <button type="submit" class="btn-mepi w-100" style="justify-content:center;">
                        <i class="bi bi-send"></i> Enviar Solicitação
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Histórico --}}
    <div class="{{ count($periodos) > 0 ? 'col-lg-7' : 'col-12' }}">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-clock-history me-2"></i>Histórico de Solicitações</h6>
            </div>
            <div class="card-mepi-body p-0">
                <table class="table table-mepi mb-0">
                    <thead>
                        <tr>
                            <th>Período de Gozo</th>
                            <th>Dias</th>
                            <th>Abono</th>
                            <th>Status</th>
                            <th>Obs.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ferias as $f)
                        <tr>
                            <td style="font-size:.83rem;">
                                {{ $f->data_inicio->format('d/m/Y') }} →<br>
                                {{ $f->data_fim->format('d/m/Y') }}
                            </td>
                            <td style="font-weight:700;color:var(--verde-escuro);">{{ $f->dias_gozados }}d</td>
                            <td style="font-size:.8rem;">
                                @if($f->abono_pecuniario)
                                    <span class="badge-pendente">{{ $f->dias_abono }}d</span>
                                @else
                                    <span style="color:#ccc;">—</span>
                                @endif
                            </td>
                            <td><span class="badge-{{ $f->status }}">{{ ucfirst($f->status) }}</span></td>
                            <td style="font-size:.78rem;color:#888;max-width:140px;">
                                {{ $f->observacao ? Str::limit($f->observacao, 40) : '—' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="bi bi-calendar2-x" style="font-size:1.8rem;color:#ccc;display:block;margin-bottom:6px;"></i>
                                <span style="color:#aaa;font-size:.85rem;">Nenhuma solicitação ainda.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
// Preenche o select com o período clicado nos cards
function preencherPeriodo(inicio) {
    document.getElementById('selectPeriodo').value = inicio;
    document.getElementById('selectPeriodo').scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// Contador de dias selecionados
function atualizarDias() {
    const ini = document.getElementById('dataInicio').value;
    const fim = document.getElementById('dataFim').value;
    const div = document.getElementById('contadorDias');
    const txt = document.getElementById('txtDias');

    if (ini && fim) {
        const d1 = new Date(ini), d2 = new Date(fim);
        const dias = Math.round((d2 - d1) / 86400000) + 1;
        if (dias > 0) {
            txt.textContent = dias + ' dia' + (dias > 1 ? 's' : '') + ' selecionado' + (dias > 1 ? 's' : '');
            div.style.display = '';
            // Atualiza min do fim
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
