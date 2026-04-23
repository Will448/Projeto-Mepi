@extends('layout.app')
@section('title', 'Reservar Equipamento')
@section('page-title', 'Reservar Equipamento')

@section('content')

<div class="row g-4">

    {{-- ── Formulário de solicitação ──────────────────────── --}}
    <div class="col-lg-5">
        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-bookmark-plus me-2"></i>Nova Solicitação</h6>
            </div>
            <div class="card-mepi-body">

                @if($errors->any())
                <div class="alert-mepi-error mb-3">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <ul class="mb-0 ps-3 mt-1">
                        @foreach($errors->all() as $e)
                            <li style="font-size:.83rem;">{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if($equipamentos->isEmpty())
                <div style="text-align:center;padding:28px 0;">
                    <i class="bi bi-shield-check" style="font-size:2rem;color:#ccc;display:block;margin-bottom:8px;"></i>
                    <p style="color:#aaa;font-size:.85rem;margin:0;">
                        Nenhum equipamento disponível para reserva no momento.
                    </p>
                </div>
                @else

                <form method="POST" action="{{ route('funcionario.reservas.solicitar') }}">
                    @csrf

                    {{-- Equipamento --}}
                    <div class="mb-3">
                        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">
                            Equipamento *
                        </label>
                        <select name="equipamento_id" id="selEq"
                                class="form-select @error('equipamento_id') is-invalid @enderror"
                                style="border-radius:8px;border-color:#ddd;font-size:.85rem;">
                            <option value="">Selecione o equipamento...</option>
                            @foreach($equipamentos as $eq)
                            <option value="{{ $eq->id }}"
                                    data-tipo="{{ $eq->tipo }}"
                                    data-serie="{{ $eq->numero_serie }}"
                                    data-validade="{{ $eq->validade ? $eq->validade->format('d/m/Y') : '' }}"
                                    {{ old('equipamento_id') == $eq->id ? 'selected':'' }}>
                                {{ $eq->nome }} ({{ $eq->tipo }})
                            </option>
                            @endforeach
                        </select>
                        @error('equipamento_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Info do equipamento selecionado --}}
                    <div id="infoEq" style="display:none;background:rgba(26,107,58,.06);border-radius:8px;padding:10px 14px;margin-bottom:14px;font-size:.82rem;">
                        <div style="display:flex;gap:16px;flex-wrap:wrap;">
                            <span><i class="bi bi-tag me-1"></i>Tipo: <strong id="eqTipo"></strong></span>
                            <span><i class="bi bi-upc me-1"></i>Série: <strong id="eqSerie"></strong></span>
                            <span id="eqValidadeWrap" style="display:none;">
                                <i class="bi bi-calendar me-1"></i>Validade: <strong id="eqValidade"></strong>
                            </span>
                        </div>
                    </div>

                    {{-- Datas --}}
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label" style="font-size:.83rem;font-weight:600;color:#444;">Data de uso *</label>
                            <input type="date" name="data_inicio" id="dataInicio"
                                   value="{{ old('data_inicio') }}"
                                   min="{{ now()->toDateString() }}"
                                   class="form-control @error('data_inicio') is-invalid @enderror"
                                   style="border-radius:8px;border-color:#ddd;font-size:.85rem;">
                            @error('data_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label" style="font-size:.83rem;font-weight:600;color:#444;">Previsão devolução</label>
                            <input type="date" name="data_fim"
                                   value="{{ old('data_fim') }}"
                                   class="form-control @error('data_fim') is-invalid @enderror"
                                   style="border-radius:8px;border-color:#ddd;font-size:.85rem;">
                            @error('data_fim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small style="color:#aaa;font-size:.72rem;">Opcional</small>
                        </div>
                    </div>

                    {{-- Justificativa --}}
                    <div class="mb-4">
                        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">
                            Justificativa *
                        </label>
                        <textarea name="justificativa" rows="4"
                                  class="form-control @error('justificativa') is-invalid @enderror"
                                  placeholder="Explique para qual atividade ou projeto você precisa deste equipamento... (mínimo 10 caracteres)"
                                  style="border-radius:8px;border-color:#ddd;font-size:.85rem;resize:vertical;">{{ old('justificativa') }}</textarea>
                        @error('justificativa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small style="color:#aaa;font-size:.72rem;">
                            <span id="contadorChars">0</span>/500 caracteres
                        </small>
                    </div>

                    <button type="submit" class="btn-mepi w-100" style="justify-content:center;">
                        <i class="bi bi-send"></i> Enviar Solicitação
                    </button>
                </form>
                @endif

            </div>
        </div>
    </div>

    {{-- ── Minhas reservas ────────────────────────────────── --}}
    <div class="col-lg-7">

        {{-- Cards de status --}}
        @php
            $pendentes  = $reservas->where('status','pendente')->count();
            $aprovadas  = $reservas->where('status','aprovado')->where('reserva_convertida',false)->count();
            $convertidas= $reservas->where('reserva_convertida',true)->count();
            $negadas    = $reservas->where('status','negado')->count();
        @endphp
        <div class="row g-2 mb-3">
            <div class="col-6 col-md-3">
                <div style="background:#fff;border:1px solid #e5e5dc;border-radius:10px;padding:12px;text-align:center;">
                    <div style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:#b08c00;">{{ $pendentes }}</div>
                    <div style="font-size:.72rem;color:#888;">Pendentes</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div style="background:#fff;border:1px solid #e5e5dc;border-radius:10px;padding:12px;text-align:center;">
                    <div style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--verde);">{{ $aprovadas }}</div>
                    <div style="font-size:.72rem;color:#888;">Aprovadas</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div style="background:#fff;border:1px solid #e5e5dc;border-radius:10px;padding:12px;text-align:center;">
                    <div style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:#3b82f6;">{{ $convertidas }}</div>
                    <div style="font-size:.72rem;color:#888;">Entregues</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div style="background:#fff;border:1px solid #e5e5dc;border-radius:10px;padding:12px;text-align:center;">
                    <div style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:#dc2626;">{{ $negadas }}</div>
                    <div style="font-size:.72rem;color:#888;">Negadas</div>
                </div>
            </div>
        </div>

        <div class="card-mepi">
            <div class="card-mepi-header">
                <h6><i class="bi bi-clock-history me-2"></i>Minhas Solicitações</h6>
            </div>
            <div class="card-mepi-body p-0">
                <table class="table table-mepi mb-0">
                    <thead>
                        <tr>
                            <th>Equipamento</th>
                            <th>Data solicitada</th>
                            <th>Status</th>
                            <th>Resposta do RH</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservas as $r)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:.86rem;">{{ $r->equipamento->nome }}</div>
                                <div style="font-size:.72rem;color:#aaa;">{{ $r->equipamento->tipo }}</div>
                            </td>
                            <td style="font-size:.83rem;">
                                {{ $r->data_inicio->format('d/m/Y') }}
                                @if($r->data_fim)
                                    <div style="font-size:.72rem;color:#aaa;">até {{ $r->data_fim->format('d/m/Y') }}</div>
                                @endif
                            </td>
                            <td>
                                @if($r->reserva_convertida)
                                    <span class="badge-ativo">Entregue</span>
                                @else
                                    <span class="badge-{{ $r->status }}">{{ ucfirst($r->status) }}</span>
                                @endif
                            </td>
                            <td style="font-size:.78rem;color:#888;max-width:160px;">
                                {{ $r->observacao_rh ? \Illuminate\Support\Str::limit($r->observacao_rh, 50) : '—' }}
                            </td>
                            <td>
                                {{-- Cancelar pendente --}}
                                @if($r->isPendente())
                                <form method="POST"
                                      action="{{ route('funcionario.reservas.cancelar', $r) }}"
                                      onsubmit="return confirm('Cancelar esta solicitação?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm"
                                            style="background:rgba(239,68,68,.1);color:#dc2626;border:none;border-radius:7px;padding:4px 8px;font-size:.75rem;"
                                            title="Cancelar">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="bi bi-bookmark" style="font-size:1.8rem;color:#ccc;display:block;margin-bottom:6px;"></i>
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
// Info do equipamento selecionado
document.getElementById('selEq')?.addEventListener('change', function () {
    const opt  = this.options[this.selectedIndex];
    const info = document.getElementById('infoEq');
    if (!this.value) { info.style.display = 'none'; return; }

    document.getElementById('eqTipo').textContent  = opt.dataset.tipo  || '—';
    document.getElementById('eqSerie').textContent = opt.dataset.serie || '—';

    const valWrap = document.getElementById('eqValidadeWrap');
    if (opt.dataset.validade) {
        document.getElementById('eqValidade').textContent = opt.dataset.validade;
        valWrap.style.display = '';
    } else {
        valWrap.style.display = 'none';
    }
    info.style.display = '';
});

// Contador de caracteres na justificativa
const textarea = document.querySelector('textarea[name="justificativa"]');
const contador  = document.getElementById('contadorChars');
if (textarea && contador) {
    const atualizar = () => { contador.textContent = textarea.value.length; };
    textarea.addEventListener('input', atualizar);
    atualizar();
}

// Trava data_fim >= data_inicio
document.getElementById('dataInicio')?.addEventListener('change', function () {
    const fim = document.querySelector('input[name="data_fim"]');
    if (fim) fim.min = this.value;
});
</script>
@endpush
