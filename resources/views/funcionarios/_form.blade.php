{{-- resources/views/funcionarios/_form.blade.php --}}
{{-- Usado por create.blade.php e edit.blade.php --}}

@if($errors->any())
<div class="alert-mepi-error mb-4">
    <i class="bi bi-exclamation-circle me-2"></i>
    <strong>Corrija os erros:</strong>
    <ul class="mb-0 mt-1 ps-3">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul>
</div>
@endif

<div class="row g-3">
    {{-- Nome --}}
    <div class="col-md-8">
        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Nome completo *</label>
        <input type="text" name="nome"
               value="{{ old('nome', $funcionario->nome ?? '') }}"
               class="form-control @error('nome') is-invalid @enderror"
               placeholder="Nome Sobrenome"
               style="border-radius:8px;border-color:#ddd;font-size:.88rem;">
        @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Status --}}
    <div class="col-md-4">
        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Status *</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror"
                style="border-radius:8px;border-color:#ddd;font-size:.88rem;">
            <option value="ativo"    {{ old('status', $funcionario->status ?? 'ativo') === 'ativo'    ? 'selected':'' }}>Ativo</option>
            <option value="inativo"  {{ old('status', $funcionario->status ?? '') === 'inativo'  ? 'selected':'' }}>Inativo</option>
            <option value="afastado" {{ old('status', $funcionario->status ?? '') === 'afastado' ? 'selected':'' }}>Afastado</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- CPF --}}
    <div class="col-md-4">
        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">CPF *</label>
        <input type="text" name="cpf" id="cpf"
               value="{{ old('cpf', $funcionario->cpf ?? '') }}"
               class="form-control @error('cpf') is-invalid @enderror"
               placeholder="000.000.000-00" maxlength="14"
               style="border-radius:8px;border-color:#ddd;font-size:.88rem;">
        @error('cpf') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- E-mail --}}
    <div class="col-md-8">
        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">E-mail *</label>
        <input type="email" name="email"
               value="{{ old('email', $funcionario->email ?? '') }}"
               class="form-control @error('email') is-invalid @enderror"
               placeholder="email@empresa.com"
               style="border-radius:8px;border-color:#ddd;font-size:.88rem;">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Telefone --}}
    <div class="col-md-4">
        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Telefone</label>
        <input type="text" name="telefone"
               value="{{ old('telefone', $funcionario->telefone ?? '') }}"
               class="form-control"
               placeholder="(00) 00000-0000" maxlength="20"
               style="border-radius:8px;border-color:#ddd;font-size:.88rem;">
    </div>

    {{-- Data Nascimento --}}
    <div class="col-md-4">
        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Data de Nascimento</label>
        <input type="date" name="data_nascimento"
               value="{{ old('data_nascimento', isset($funcionario->data_nascimento) ? $funcionario->data_nascimento->format('Y-m-d') : '') }}"
               class="form-control @error('data_nascimento') is-invalid @enderror"
               style="border-radius:8px;border-color:#ddd;font-size:.88rem;">
        @error('data_nascimento') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Data Admissão --}}
    <div class="col-md-4">
        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Data de Admissão *</label>
        <input type="date" name="data_admissao"
               value="{{ old('data_admissao', isset($funcionario->data_admissao) ? $funcionario->data_admissao->format('Y-m-d') : '') }}"
               class="form-control @error('data_admissao') is-invalid @enderror"
               style="border-radius:8px;border-color:#ddd;font-size:.88rem;">
        @error('data_admissao') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Cargo --}}
    <div class="col-md-6">
        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Cargo *</label>
        <select name="cargo_id" class="form-select @error('cargo_id') is-invalid @enderror"
                style="border-radius:8px;border-color:#ddd;font-size:.88rem;">
            <option value="">Selecione um cargo...</option>
            @foreach($cargos as $c)
            <option value="{{ $c->id }}"
                {{ old('cargo_id', $funcionario->cargo_id ?? '') == $c->id ? 'selected' : '' }}>
                {{ $c->nome }} — R$ {{ number_format($c->salario_base, 2, ',', '.') }}
            </option>
            @endforeach
        </select>
        @error('cargo_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Salário --}}
    <div class="col-md-6">
        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Salário (R$) *</label>
        <div class="input-group">
            <span class="input-group-text" style="background:#f7f5ee;border-color:#ddd;font-size:.85rem;color:#666;">R$</span>
            <input type="number" name="salario"
                   value="{{ old('salario', $funcionario->salario ?? '') }}"
                   class="form-control @error('salario') is-invalid @enderror"
                   placeholder="0,00" step="0.01" min="0"
                   style="border-radius:0 8px 8px 0;border-color:#ddd;font-size:.88rem;">
            @error('salario') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <small style="color:#888;font-size:.75rem;">
            <i class="bi bi-info-circle me-1"></i>Pode diferir do salário base do cargo
        </small>
    </div>
</div>

@push('scripts')
<script>
// Máscara CPF: 000.000.000-00
document.getElementById('cpf')?.addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '').substring(0, 11);
    if (v.length > 9) v = v.replace(/(\d{3})(\d{3})(\d{3})(\d{0,2})/, '$1.$2.$3-$4');
    else if (v.length > 6) v = v.replace(/(\d{3})(\d{3})(\d{0,3})/, '$1.$2.$3');
    else if (v.length > 3) v = v.replace(/(\d{3})(\d{0,3})/, '$1.$2');
    this.value = v;
});
</script>
@endpush
