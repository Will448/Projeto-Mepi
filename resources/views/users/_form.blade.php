@if($errors->any())
<div class="alert alert-danger mb-4">
    <ul class="mb-0">
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label fw-semibold">Nome *</label>
                <input type="text" name="name"
                       value="{{ old('name', $user->name ?? '') }}"
                       class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Email *</label>
                <input type="email" name="email"
                       value="{{ old('email', $user->email ?? '') }}"
                       class="form-control" required>
            </div>

        <div class="col-md-6">
    <label class="form-label fw-semibold">Tipo de Usuário</label>
    <select name="role" class="form-control">
        <option value="funcionario" {{ old('role', $user->role ?? '') == 'funcionario' ? 'selected' : '' }}>
            Funcionário
        </option>
        <option value="rh" {{ old('role', $user->role ?? '') == 'rh' ? 'selected' : '' }}>
            RH
        </option>
        <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>
            Admin
        </option>
    </select>
</div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    {{ isset($user) ? 'Nova senha' : 'Senha *' }}
                </label>
                <input type="password" name="password"
                       class="form-control"
                       {{ isset($user) ? '' : 'required' }}>
            </div>

        </div>

    </div>
</div>