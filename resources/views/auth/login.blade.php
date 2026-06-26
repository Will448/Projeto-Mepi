<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - MEPI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:linear-gradient(135deg,#1d4d2d,#2f6b41,#4a8a5d);
            overflow:hidden;
            font-family:'Segoe UI',sans-serif;
        }

        /* círculos decorativos */

        body::before{
            content:'';
            position:absolute;
            width:400px;
            height:400px;
            border-radius:50%;
            background:rgba(255,255,255,.08);
            top:-120px;
            right:-120px;
        }

        body::after{
            content:'';
            position:absolute;
            width:300px;
            height:300px;
            border-radius:50%;
            background:rgba(255,255,255,.05);
            bottom:-100px;
            left:-80px;
        }

        .login-card{

            position:relative;
            z-index:10;

            width:100%;
            padding:40px;

            border-radius:20px;

            background:rgba(255,255,255,.95);

            backdrop-filter:blur(12px);

            box-shadow:0 20px 50px rgba(0,0,0,.25);

        }

        .logo{

            width:85px;
            height:85px;

            margin:auto;

            border-radius:50%;

            background:#2f6b41;

            color:white;

            display:flex;
            justify-content:center;
            align-items:center;

            font-size:34px;
            font-weight:bold;

            box-shadow:0 8px 20px rgba(47,107,65,.4);

        }

        .login-title{

            font-weight:700;
            color:#2d3748;

        }

        .subtitle{

            color:#718096;
            font-size:.95rem;

        }

        .input-group-text{

            background:#f3f5f7;
            border-right:none;

        }

        .form-control{

            border-left:none;
            padding:12px;

        }

        .form-control:focus{

            box-shadow:none;
            border-color:#198754;

        }

        .input-group:focus-within{

            border-radius:10px;

        }

        .btn-login{

            background:#2f6b41;
            border:none;
            padding:12px;
            font-weight:600;
            border-radius:10px;
            transition:.3s;

        }

        .btn-login:hover{

            background:#245333;
            transform:translateY(-2px);

        }

        .footer{

            color:white;
            text-align:center;
            margin-top:20px;
            font-size:.85rem;

        }

    </style>

</head>

<body>

<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-5 col-lg-4">

            <div class="login-card">

                <div class="text-center mb-4">

                    <div class="logo">
                        M
                    </div>

                    <h3 class="login-title mt-3">
                        Bem-vindo ao MEPI
                    </h3>

                    <p class="subtitle">
                        Faça login para acessar o sistema.
                    </p>

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

                        <label class="form-label fw-semibold">
                            Email
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="Digite seu email"
                                required>

                        </div>

                    </div>

                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Senha
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Digite sua senha"
                                required>

                        </div>

                    </div>

                    <button class="btn btn-success w-100 btn-login">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Entrar
                    </button>

                </form>

            </div>

            <div class="footer">
                © {{ date('Y') }} MEPI - Sistema de Gestão
            </div>

        </div>

    </div>

</div>

</body>
</html>