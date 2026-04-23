<!DOCTYPE html>
<html>
<head>
    <title>Login - MEPI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #214921, #376f37);
            min-height: 100vh;
        }

        .login-card {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .login-title {
            font-weight: 700;
            color: #1f2937;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn-login {
            border-radius: 8px;
            font-weight: 600;
            padding: 10px;
        }

        .logo {
            font-size: 1.4rem;
            font-weight: bold;
            color: #4f46e5;
        }
    </style>
</head>
<body>

<div class="container d-flex align-items-center justify-content-center" style="min-height:100vh;">
    <div class="col-md-4">

        <div class="login-card">

            <div class="text-center mb-4">
                <div class="logo">MEPI</div>
                <div class="login-title mt-2">Acesso ao sistema</div>
                <small class="text-muted">Informe seus dados para continuar</small>
            </div>

            {{-- ERROS --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email"
                           class="form-control"
                           placeholder="Digite seu email"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Senha</label>
                    <input type="password" name="password"
                           class="form-control"
                           placeholder="Digite sua senha"
                           required>
                </div>

                <button class="btn btn-primary w-100 btn-login">
                    Entrar
                </button>
            </form>

        </div>

        <p class="text-center text-muted mt-3" style="font-size: 0.8rem;">
            © {{ date('Y') }} MEPI - Sistema de Gestão
        </p>

    </div>
</div>

</body>
</html>