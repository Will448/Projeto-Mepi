{{-- resources/views/equipamentos/_form.blade.php --}}

@if($errors->any())
<div class="alert-mepi-error mb-4">
    <i class="bi bi-exclamation-circle me-2"></i>
    <strong>Corrija os erros:</strong>
    <ul class="mb-0 mt-1 ps-3">
        @foreach($errors->all() as $e)<li style="font-size:.83rem;">{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<div class="row g-3">

    {{-- Nome --}}
    <div class="col-md-8">
        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Nome do Equipamento / EPI *</label>
        <input type="text" name="nome"
               value="{{ old('nome', $equipamento->nome ?? '') }}"
               class="form-control @error('nome') is-invalid @enderror"
               placeholder="Ex: Capacete de Segurança, Notebook Dell i5..."
               style="border-radius:8px;border-color:#ddd;font-size:.88rem;">
        @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Status --}}
    <div class="col-md-4">
        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Status *</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror"
                style="border-radius:8px;border-color:#ddd;font-size:.88rem;">
            <option value="disponivel" {{ old('status', $equipamento->status ?? 'disponivel') === 'disponivel' ? 'selected':'' }}>Disponível</option>
            <option value="entregue"   {{ old('status', $equipamento->status ?? '') === 'entregue'   ? 'selected':'' }}>Em uso</option>
            <option value="manutencao" {{ old('status', $equipamento->status ?? '') === 'manutencao' ? 'selected':'' }}>Manutenção</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Nº de série --}}
    <div class="col-md-6">
        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Número de Série *</label>
        <input type="text" name="numero_serie"
               value="{{ old('numero_serie', $equipamento->numero_serie ?? '') }}"
               class="form-control @error('numero_serie') is-invalid @enderror"
               placeholder="CAP-001, NB-2024-003..."
               style="border-radius:8px;border-color:#ddd;font-size:.88rem;font-family:monospace;">
        @error('numero_serie') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Tipo --}}
    <div class="col-md-6">
        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Tipo *</label>
        <input type="text" name="tipo"
               value="{{ old('tipo', $equipamento->tipo ?? '') }}"
               class="form-control @error('tipo') is-invalid @enderror"
               placeholder="EPI, Eletrônico, Ferramenta..."
               list="tipos-sugeridos"
               style="border-radius:8px;border-color:#ddd;font-size:.88rem;">
        <datalist id="tipos-sugeridos">
            <option value="EPI">
            <option value="Eletrônico">
            <option value="Ferramenta">
            <option value="Veículo">
            <option value="Mobiliário">
        </datalist>
        @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <small style="color:#888;font-size:.73rem;">Pode digitar um novo tipo ou escolher da lista</small>
    </div>

    {{-- Validade --}}
    <div class="col-md-4">
        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Validade</label>
        <input type="date" name="validade"
               value="{{ old('validade', isset($equipamento->validade) ? $equipamento->validade->format('Y-m-d') : '') }}"
               class="form-control @error('validade') is-invalid @enderror"
               style="border-radius:8px;border-color:#ddd;font-size:.88rem;">
        @error('validade') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <small style="color:#888;font-size:.73rem;">Deixe em branco para equipamentos sem validade</small>
    </div>

</div>
