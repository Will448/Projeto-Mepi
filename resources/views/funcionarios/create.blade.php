@extends('layout.app')
@section('title', 'Novo Funcionário')
@section('page-title', 'Novo Funcionário')

@section('content')

<div class="mb-4">
    <a href="{{ route(auth()->user()->role.'.funcionarios.index') }}" style="color:#888;text-decoration:none;font-size:.85rem;">
        <i class="bi bi-arrow-left me-1"></i>Voltar para lista
    </a>
</div>

<div class="card-mepi" style="max-width:720px;">
    <div class="card-mepi-header">
        <h6><i class="bi bi-person-plus me-2"></i>Cadastrar Novo Funcionário</h6>
    </div>
    <div class="card-mepi-body">
        <form method="POST" action="{{ route(auth()->user()->role.'.funcionarios.store') }}">
            @csrf
            @include('funcionarios._form')

            {{-- Criar acesso ao sistema --}}
            <hr style="border-color:#f0f0e8;margin:24px 0;">
            <div class="mb-3">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.88rem;font-weight:600;color:#444;">
                    <input type="checkbox" name="criar_login" value="1" id="ckLogin"
                           {{ old('criar_login') ? 'checked' : '' }}
                           style="width:16px;height:16px;accent-color:var(--verde);">
                    Criar acesso ao sistema para este funcionário
                </label>
            </div>
            <div id="loginFields" style="{{ old('criar_login') ? '' : 'display:none;' }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">E-mail de acesso *</label>
                        <input type="email" name="login_email" value="{{ old('login_email') }}"
                               class="form-control @error('login_email') is-invalid @enderror"
                               placeholder="email@empresa.com"
                               style="border-radius:8px;border-color:#ddd;font-size:.88rem;">
                        @error('login_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:.85rem;font-weight:600;color:#444;">Senha *</label>
                        <input type="password" name="login_senha"
                               class="form-control @error('login_senha') is-invalid @enderror"
                               placeholder="Mínimo 6 caracteres"
                               style="border-radius:8px;border-color:#ddd;font-size:.88rem;">
                        @error('login_senha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn-mepi">
                    <i class="bi bi-check-lg"></i> Cadastrar Funcionário
                </button>
                <a href="{{ route(auth()->user()->role.'.funcionarios.index') }}"
                   style="padding:9px 20px;border-radius:8px;background:#f0f0e8;color:#555;text-decoration:none;font-size:.85rem;font-weight:600;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('ckLogin').addEventListener('change', function () {
    document.getElementById('loginFields').style.display = this.checked ? '' : 'none';
});
</script>
@endpush
