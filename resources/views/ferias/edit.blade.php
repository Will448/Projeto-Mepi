@extends('layout.app')
@section('title', 'Editar Férias')
@section('page-title', 'Controle de Férias')

@section('content')

<div class="mb-4">
    <a href="{{ route(auth()->user()->role.'.ferias.index') }}"
       style="color:#888;text-decoration:none;font-size:.85rem;">
        <i class="bi bi-arrow-left me-1"></i>Voltar para lista
    </a>
</div>

<div class="row g-4" style="max-width:860px;">

    {{-- Formulário de edição --}}
    <div class="col-lg-7">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-pencil me-2"></i>Editar Datas de Férias</h6>
                <span class="badge-{{ $ferias->status }}">{{ ucfirst($ferias->status) }}</span>
            </div>
            <div class="card-mepi-body">

                {{-- Funcionário --}}
                <div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--off-white);border-radius:10px;margin-bottom:20px;">
                    <div style="width:42px;height:42px;border-radius:50%;background:var(--verde);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1rem;">
                        {{ strtoupper(substr($ferias->funcionario->nome, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:.9rem;">{{ $ferias->funcionario->nome }}</div>
                        <div style="font-size:.78rem;color:#888;">{{ $ferias->funcionario->cargo->nome }}</div>
                    </div>
                </div>

                {{-- Info somente leitura --}}
                @php
                $info = [
                    ['Período aquisitivo', $ferias->periodo_aquisitivo_inicio->format('d/m/Y').' → '.$ferias->periodo_aquisitivo_fim->format('d/m/Y')],
                    ['Abono pecuniário',   $ferias->abono_pecuniario ? $ferias->dias_abono.'d vendidos' : 'Não'],
                    ['Solicitado em',      $ferias->created_at->format('d/m/Y \à\s H:i')],
                ];
                @endphp

                @foreach($info as [$label, $valor])
                <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f0f0e8;">
                    <span style="font-size:.82rem;color:#888;">{{ $label }}</span>
                    <span style="font-size:.85rem;font-weight:600;">{{ $valor }}</span>
                </div>
                @endforeach

                <hr style="border-color:#f0f0e8;margin:20px 0;">

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

                <form method="POST" action="{{ route(auth()->user()->role.'.ferias.editarDatas', $ferias) }}">
                    @csrf @method('PUT')

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label" style="font-size:.83rem;font-weight:600;color:#444;">
                                Data Início <span style="color:red">*</span>
                            </label>
                            <input type="date" name="data_inicio" id="dataInicio"
                                   value="{{ old('data_inicio', $ferias->data_inicio->format('Y-m-d')) }}"
                                   class="form-control @error('data_inicio') is-invalid @enderror"
                                   style="border-radius:8px;border-color:#ddd;font-size:.85rem;">
                            @error('data_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label" style="font-size:.83rem;font-weight:600;color:#444;">
                                Data Fim <span style="color:red">*</span>
                            </label>
                            <input type="date" name="data_fim" id="dataFim"
                                   value="{{ old('data_fim', $ferias->data_fim->format('Y-m-d')) }}"
                                   class="form-control @error('data_fim') is-invalid @enderror"
                                   style="border-radius:8px;border-color:#ddd;font-size:.85rem;">
                            @error('data_fim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Contador de dias --}}
                    <div id="contadorDias" style="background:rgba(26,107,58,.07);border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:.83rem;color:var(--verde);font-weight:600;">
                        <i class="bi bi-calendar-check me-1"></i>
                        <span id="txtDias">{{ $ferias->dias_gozados }} dias selecionados</span>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" style="font-size:.83rem;font-weight:600;color:#444;">
                            Observação <span style="color:#aaa;font-weight:400;">(motivo da alteração)</span>
                        </label>
                        <textarea name="observacao" rows="3"
                                  class="form-control"
                                  placeholder="Justificativa, instruções..."
                                  style="border-radius:8px;border-color:#ddd;font-size:.85rem;resize:vertical;">{{ old('observacao', $ferias->observacao) }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-mepi flex-fill" style="justify-content:center;">
                            <i class="bi bi-check-lg"></i> Salvar alterações
                        </button>
                        <a href="{{ route(auth()->user()->role.'.ferias.index') }}"
                           style="padding:9px 18px;border-radius:8px;background:#f0f0e8;color:#666;text-decoration:none;font-size:.85rem;display:flex;align-items:center;gap:6px;">
                            <i class="bi bi-x-lg"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Info lateral --}}
    <div class="col-lg-5">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-info-circle me-2"></i>Período atual</h6>
            </div>
            <div class="card-mepi-body">
                @php
                $linhas2 = [
                    ['Início atual',  $ferias->data_inicio->format('d/m/Y')],
                    ['Fim atual',     $ferias->data_fim->format('d/m/Y')],
                    ['Dias atuais',   $ferias->dias_gozados.'d'],
                ];
                @endphp
                @foreach($linhas2 as [$label, $valor])
                <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid #f0f0e8;">
                    <span style="font-size:.82rem;color:#888;">{{ $label }}</span>
                    <span style="font-size:.85rem;font-weight:600;">{{ $valor }}</span>
                </div>
                @endforeach

                <div style="margin-top:16px;padding:12px;background:rgba(245,196,0,.08);border:1px solid rgba(245,196,0,.2);border-radius:8px;">
                    <p style="font-size:.78rem;color:#8a6d00;font-weight:700;margin-bottom:4px;">
                        <i class="bi bi-exclamation-triangle me-1"></i>Atenção
                    </p>
                    <p style="font-size:.82rem;color:#555;margin:0;">
                        Alterar as datas recalcula automaticamente os dias gozados. O abono pecuniário e o período aquisitivo não são alterados.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
function recalcularDias() {
    const ini = document.getElementById('dataInicio').value;
    const fim = document.getElementById('dataFim').value;
    const txt = document.getElementById('txtDias');

    if (ini && fim) {
        const d1 = new Date(ini), d2 = new Date(fim);
        const dias = Math.round((d2 - d1) / 86400000) + 1;
        txt.textContent = dias > 0
            ? dias + ' dia' + (dias > 1 ? 's' : '') + ' selecionado' + (dias > 1 ? 's' : '')
            : '—';
        document.getElementById('dataFim').min = ini;
    }
}

document.getElementById('dataInicio').addEventListener('change', recalcularDias);
document.getElementById('dataFim').addEventListener('change', recalcularDias);
</script>
@endpush