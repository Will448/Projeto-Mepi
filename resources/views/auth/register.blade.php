<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">

<div class="container mt-5">
    <div class="col-md-5 offset-md-3">

        <h3 class="text-center mb-4">Cadastrar Usuário</h3>

        {{-- Mensagens de erro --}}
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $erro)
                    <div>{{ $erro }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <input type="text" name="name" class="form-control mb-2" placeholder="Nome" required>

            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>

            <input type="password" name="password" class="form-control mb-2" placeholder="Senha" required>

            <input type="password" name="password_confirmation" class="form-control mb-3" placeholder="Confirmar senha" required>

            {{-- SOMENTE ADMIN DEVE DEFINIR ROLE --}}
            <select name="role" class="form-control mb-3">
                <option value="funcionario">Funcionário</option>
                <option value="rh">RH</option>
                <option value="admin">Admin</option>
            </select>

            <button class="btn btn-success w-100">Cadastrar</button>
        </form>

    </div>
</div>

</body>
</html>