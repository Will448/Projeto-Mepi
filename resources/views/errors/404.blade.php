<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página não encontrada — MEPI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root { --verde:#1a6b3a; --verde-escuro:#0f3d21; --amarelo:#f5c400; }
        * { box-sizing:border-box; margin:0; padding:0; }
        body {
            font-family:'DM Sans',sans-serif;
            background:var(--verde-escuro);
            min-height:100vh;
            display:flex; align-items:center; justify-content:center;
        }
        .card {
            background:#fff; border-radius:20px; padding:48px 40px;
            text-align:center; max-width:420px; width:90%;
            box-shadow:0 24px 60px rgba(0,0,0,.3);
        }
        .icon-wrap {
            width:80px; height:80px; border-radius:50%;
            background:rgba(245,196,0,.12); border:2px solid rgba(245,196,0,.25);
            display:flex; align-items:center; justify-content:center;
            margin:0 auto 24px; font-size:2rem; color:#b08c00;
        }
        .code { font-family:'Syne',sans-serif; font-size:4rem; font-weight:800; color:#f0f0e8; line-height:1; margin-bottom:8px; }
        h2 { font-family:'Syne',sans-serif; font-weight:800; font-size:1.3rem; color:#1a1a1a; margin-bottom:10px; }
        p  { font-size:.9rem; color:#666; line-height:1.6; margin-bottom:28px; }
        .btns { display:flex; gap:10px; justify-content:center; flex-wrap:wrap; }
        .btn-primary {
            display:inline-flex; align-items:center; gap:8px;
            background:var(--verde); color:#fff; text-decoration:none;
            padding:11px 22px; border-radius:10px; font-weight:600; font-size:.88rem;
            transition:background .2s;
        }
        .btn-primary:hover { background:#2d9e58; color:#fff; }
        .btn-secondary {
            display:inline-flex; align-items:center; gap:8px;
            background:#f0f0e8; color:#555; text-decoration:none;
            padding:11px 22px; border-radius:10px; font-weight:600; font-size:.88rem;
            transition:background .2s;
        }
        .btn-secondary:hover { background:#e0e0d8; color:#333; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-wrap"><i class="bi bi-compass"></i></div>
        <div class="code">404</div>
        <h2>Página não encontrada</h2>
        <p>A página que você está procurando não existe ou foi movida.<br>Verifique o endereço e tente novamente.</p>
        <div class="btns">
            @auth
            <a href="{{ match(auth()->user()->role) {
                'admin' => route('admin.dashboard'),
                'rh'    => route('rh.dashboard'),
                default => route('funcionario.dashboard')
            } }}" class="btn-primary">
                <i class="bi bi-grid-1x2"></i> Ir ao Dashboard
            </a>
            @else
            <a href="{{ route('login') }}" class="btn-primary">
                <i class="bi bi-box-arrow-in-right"></i> Fazer login
            </a>
            @endauth
            <a href="javascript:history.back()" class="btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
</body>
</html>
