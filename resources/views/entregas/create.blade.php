@extends('layout.app')
@section('title', 'Registrar Entrega')
@section('page-title', 'Registrar Entrega de EPI')

@section('content')

<div class="mb-4">
    <a href="{{ route(auth()->user()->role.'.entregas.index') }}" style="color:#888;text-decoration:none;font-size:.85rem;">
        <i class="bi bi-arrow-left me-1"></i>Voltar para lista
    </a>
</div>

<div class="row g-4" style="max-width:820px;">
    <div class="col-lg-6">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-box-seam me-2"></i>Dados da Entrega</h6>
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

                <form method="POST" action="{{ route(auth()->user()->role.'.entregas.store') }}">
                    @csrf

                    {{-- Funcionário --}}
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Funcionário *</label>
                        <select name="funcionario_id" id="selFuncionario"
                                class="form-select @error('funcionario_id') is-invalid @enderror"
                                style="border-radius:8px;border-color:#ddd;font-size:.85rem;">
                            <option value="">Selecione o funcionário...</option>
                            @foreach($funcionarios as $f)
                            <option value="{{ $f->id }}" {{ old('funcionario_id') == $f->id ? 'selected':'' }}>
                                {{ $f->nome }} — {{ $f->cargo->nome }}
                            </option>
                            @endforeach
                        </select>
                        @error('funcionario_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Equipamento --}}
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Equipamento / EPI *</label>
                        @if($equipamentos->isEmpty())
                            <div style="background:rgba(245,196,0,.1);border:1px solid rgba(245,196,0,.3);border-radius:8px;padding:12px 14px;font-size:.83rem;color:#8a6d00;">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Nenhum equipamento disponível no momento.
                                <a href="{{ route(auth()->user()->role.'.equipamentos.create') }}" style="color:var(--verde);font-weight:600;">Cadastrar novo</a>
                            </div>
                        @else
                        <select name="equipamento_id" id="selEquipamento"
                                class="form-select @error('equipamento_id') is-invalid @enderror"
                                style="border-radius:8px;border-color:#ddd;font-size:.85rem;">
                            <option value="">Selecione o equipamento...</option>
                            @foreach($equipamentos as $eq)
                            <option value="{{ $eq->id }}"
                                    data-serie="{{ $eq->numero_serie }}"
                                    data-tipo="{{ $eq->tipo }}"
                                    data-validade="{{ $eq->validade ? $eq->validade->format('d/m/Y') : '' }}"
                                    {{ old('equipamento_id', request('equipamento_id')) == $eq->id ? 'selected':'' }}>
                                {{ $eq->nome }} ({{ $eq->numero_serie }})
                            </option>
                            @endforeach
                        </select>
                        @error('equipamento_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @endif
                    </div>

                    {{-- Info do equipamento selecionado --}}
                    <div id="infoEq" style="display:none;background:rgba(26,107,58,.06);border-radius:8px;padding:10px 14px;margin-bottom:14px;font-size:.82rem;">
                        <div style="display:flex;gap:16px;flex-wrap:wrap;">
                            <span><i class="bi bi-tag me-1"></i>Tipo: <strong id="eqTipo">—</strong></span>
                            <span><i class="bi bi-upc me-1"></i>Série: <strong id="eqSerie">—</strong></span>
                            <span id="eqValidadeWrap"><i class="bi bi-calendar me-1"></i>Validade: <strong id="eqValidade">—</strong></span>
                        </div>
                    </div>

                    {{-- Data da entrega --}}
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Data da Entrega *</label>
                        <input type="date" name="data_entrega"
                               value="{{ old('data_entrega', now()->format('Y-m-d')) }}"
                               max="{{ now()->format('Y-m-d') }}"
                               class="form-control @error('data_entrega') is-invalid @enderror"
                               style="border-radius:8px;border-color:#ddd;font-size:.85rem;">
                        @error('data_entrega') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Observação --}}
                    <div class="mb-4">
                        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Observação</label>
                        <textarea name="observacao" rows="3"
                                  class="form-control"
                                  placeholder="Condições do equipamento, instruções de uso..."
                                  style="border-radius:8px;border-color:#ddd;font-size:.85rem;resize:vertical;">{{ old('observacao') }}</textarea>
                    </div>

                    <button type="submit" class="btn-mepi w-100" style="justify-content:center;"
                            {{ $equipamentos->isEmpty() ? 'disabled' : '' }}>
                        <i class="bi bi-box-seam"></i> Confirmar Entrega
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Painel lateral informativo --}}
    <div class="col-lg-6">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-info-circle me-2"></i>Equipamentos Disponíveis</h6>
                <span style="font-size:.72rem;color:#aaa;">{{ $equipamentos->count() }} item(s)</span>
            </div>
            <div class="card-mepi-body p-0">
                <table class="table table-mepi mb-0">
                    <thead><tr><th>Nome</th><th>Tipo</th><th>Validade</th></tr></thead>
                    <tbody>
                        @forelse($equipamentos as $eq)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:.83rem;">{{ $eq->nome }}</div>
                                <div style="font-size:.72rem;color:#aaa;font-family:monospace;">{{ $eq->numero_serie }}</div>
                            </td>
                            <td>
                                <span style="background:#f0f0e8;padding:2px 8px;border-radius:20px;font-size:.72rem;font-weight:600;color:#555;">
                                    {{ $eq->tipo }}
                                </span>
                            </td>
                            <td style="font-size:.8rem;">
                                @if($eq->validade)
                                    @if($eq->validade->isPast())
                                        <span style="color:#dc2626;font-weight:600;">
                                            <i class="bi bi-exclamation-circle"></i> {{ $eq->validade->format('d/m/Y') }}
                                        </span>
                                    @else
                                        {{ $eq->validade->format('d/m/Y') }}
                                    @endif
                                @else
                                    <span style="color:#ccc;">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-3" style="color:#aaa;font-size:.83rem;">
                                Nenhum disponível.
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
document.getElementById('selEquipamento')?.addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    const info = document.getElementById('infoEq');

    if (!this.value) { info.style.display = 'none'; return; }

    document.getElementById('eqTipo').textContent   = opt.dataset.tipo  || '—';
    document.getElementById('eqSerie').textContent  = opt.dataset.serie  || '—';

    const valDiv = document.getElementById('eqValidadeWrap');
    if (opt.dataset.validade) {
        document.getElementById('eqValidade').textContent = opt.dataset.validade;
        valDiv.style.display = '';
    } else {
        valDiv.style.display = 'none';
    }

    info.style.display = '';
});

// Pré-selecionar equipamento via query string
window.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('selEquipamento');
    if (sel && sel.value) sel.dispatchEvent(new Event('change'));
});
</script>
@endpush
