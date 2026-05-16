<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro interno — MEPI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root { --verde:#1a6b3a; --verde-escuro:#0f3d21; }
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
            background:rgba(239,68,68,.1); border:2px solid rgba(239,68,68,.2);
            display:flex; align-items:center; justify-content:center;
            margin:0 auto 24px; font-size:2rem; color:#dc2626;
        }
        .code { font-family:'Syne',sans-serif; font-size:4rem; font-weight:800; color:#f0f0e8; line-height:1; margin-bottom:8px; }
        h2 { font-family:'Syne',sans-serif; font-weight:800; font-size:1.3rem; color:#1a1a1a; margin-bottom:10px; }
        p  { font-size:.9rem; color:#666; line-height:1.6; margin-bottom:28px; }
        .btn-back {
            display:inline-flex; align-items:center; gap:8px;
            background:var(--verde); color:#fff; text-decoration:none;
            padding:11px 24px; border-radius:10px; font-weight:600; font-size:.9rem;
        }
        .btn-back:hover { background:#2d9e58; color:#fff; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-wrap"><i class="bi bi-exclamation-triangle-fill"></i></div>
        <div class="code">500</div>
        <h2>Erro interno do servidor</h2>
        <p>Algo deu errado no servidor. Nossa equipe foi notificada.<br>Tente novamente em alguns instantes.</p>
        <a href="javascript:history.back()" class="btn-back">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</body>
</html>
