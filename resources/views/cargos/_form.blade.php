{{-- resources/views/cargos/_form.blade.php --}}
{{-- Incluído por create.blade.php e edit.blade.php --}}

@if($errors->any())
<div class="alert-mepi-error mb-3">
    <i class="bi bi-exclamation-circle me-2"></i>
    <strong>Corrija os erros abaixo:</strong>
    <ul class="mb-0 mt-1">
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Nome --}}
<div class="mb-3">
    <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Nome do Cargo *</label>
    <input type="text"
           name="nome"
           value="{{ old('nome', $cargo->nome ?? '') }}"
           class="form-control @error('nome') is-invalid @enderror"
           placeholder="Ex: Analista de Sistemas"
           style="border-radius:8px;border-color:#ddd;font-size:.88rem;">
    @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Descrição --}}
<div class="mb-3">
    <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Descrição</label>
    <textarea name="descricao"
              rows="3"
              class="form-control @error('descricao') is-invalid @enderror"
              placeholder="Descreva as responsabilidades do cargo..."
              style="border-radius:8px;border-color:#ddd;font-size:.88rem;resize:vertical;">{{ old('descricao', $cargo->descricao ?? '') }}</textarea>
    @error('descricao') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Salário base --}}
<div class="mb-3">
    <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Salário Base (R$) *</label>
    <div class="input-group">
        <span class="input-group-text" style="background:#f7f5ee;border-color:#ddd;font-size:.85rem;color:#666;">R$</span>
        <input type="number"
               name="salario_base"
               value="{{ old('salario_base', $cargo->salario_base ?? '') }}"
               class="form-control @error('salario_base') is-invalid @enderror"
               placeholder="0,00"
               step="0.01"
               min="0"
               style="border-radius:0 8px 8px 0;border-color:#ddd;font-size:.88rem;">
        @error('salario_base') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
