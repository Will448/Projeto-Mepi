<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEPI — Monitoramento de Pessoas e EPIs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --verde:        #1a6b3a;
            --verde-claro:  #2d9e58;
            --verde-escuro: #0f3d21;
            --amarelo:      #f5c400;
            --amarelo-claro:#ffe566;
            --amarelo-escuro:#b08c00;
            --off-white:    #f7f5ee;
            --cinza:        #3a3a3a;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--verde-escuro);
            color: var(--off-white);
            overflow-x: hidden;
        }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            background:
                radial-gradient(ellipse 80% 60% at 70% 50%, rgba(45,158,88,0.25) 0%, transparent 70%),
                radial-gradient(ellipse 50% 40% at 20% 80%, rgba(245,196,0,0.12) 0%, transparent 60%),
                var(--verde-escuro);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        /* Padrão geométrico de fundo */
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                repeating-linear-gradient(
                    45deg,
                    rgba(245,196,0,0.04) 0px,
                    rgba(245,196,0,0.04) 1px,
                    transparent 1px,
                    transparent 60px
                ),
                repeating-linear-gradient(
                    -45deg,
                    rgba(45,158,88,0.04) 0px,
                    rgba(45,158,88,0.04) 1px,
                    transparent 1px,
                    transparent 60px
                );
            pointer-events: none;
        }

        /* ── NAVBAR ── */
        .nav-mepi {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 48px;
            position: relative;
            z-index: 10;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .logo-icon {
            width: 44px; height: 44px;
            background: var(--amarelo);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            color: var(--verde-escuro);
            font-weight: 800;
        }
        .logo-text {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.4rem;
            color: #fff;
            letter-spacing: -0.5px;
        }
        .logo-text span { color: var(--amarelo); }

        .btn-login-nav {
            background: var(--amarelo);
            color: var(--verde-escuro);
            font-weight: 700;
            font-size: 0.9rem;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s;
            border: 2px solid var(--amarelo);
        }
        .btn-login-nav:hover {
            background: transparent;
            color: var(--amarelo);
        }

        /* ── HERO CONTENT ── */
        .hero-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 24px;
            position: relative;
            z-index: 5;
        }

        .badge-topo {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(245,196,0,0.12);
            border: 1px solid rgba(245,196,0,0.3);
            color: var(--amarelo-claro);
            font-size: 0.78rem;
            font-weight: 500;
            padding: 6px 16px;
            border-radius: 20px;
            margin-bottom: 28px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        h1.hero-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(2.8rem, 7vw, 5.5rem);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -2px;
            margin-bottom: 24px;
            color: #fff;
        }
        h1.hero-title em {
            font-style: normal;
            color: var(--amarelo);
        }

        .hero-sub {
            font-size: 1.1rem;
            color: rgba(247,245,238,0.65);
            max-width: 520px;
            line-height: 1.7;
            margin-bottom: 48px;
            font-weight: 300;
        }

        /* ── CARDS DE PERFIL ── */
        .perfis-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            max-width: 680px;
            width: 100%;
        }

        .perfil-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 28px 20px;
            text-decoration: none;
            color: #fff;
            transition: all 0.25s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            position: relative;
            overflow: hidden;
        }
        .perfil-card::before {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 3px;
            background: var(--amarelo);
            transform: scaleX(0);
            transition: transform 0.25s;
        }
        .perfil-card:hover {
            background: rgba(245,196,0,0.1);
            border-color: rgba(245,196,0,0.4);
            transform: translateY(-4px);
            color: #fff;
        }
        .perfil-card:hover::before { transform: scaleX(1); }

        .perfil-icon {
            width: 52px; height: 52px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }
        .icon-admin    { background: rgba(239,68,68,0.15);  color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
        .icon-rh       { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.3); }
        .icon-func     { background: rgba(45,158,88,0.2);   color: #6ee7b7; border: 1px solid rgba(45,158,88,0.4); }

        .perfil-nome {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
        }
        .perfil-desc {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.45);
            text-align: center;
            line-height: 1.4;
        }
        .perfil-arrow {
            color: var(--amarelo);
            font-size: 0.8rem;
            margin-top: 4px;
        }

        .label-ou {
            margin-bottom: 16px;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.35);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* ── FEATURES ── */
        .features-section {
            background: var(--off-white);
            color: var(--cinza);
            padding: 80px 24px;
        }
        .features-section h2 {
            font-family: 'Syne', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 8px;
            color: var(--verde-escuro);
            letter-spacing: -1px;
        }
        .features-section p.sub {
            text-align: center;
            color: #888;
            margin-bottom: 56px;
            font-size: 1rem;
        }

        .feat-card {
            background: #fff;
            border: 1px solid #e8e8e0;
            border-radius: 16px;
            padding: 32px 24px;
            height: 100%;
            transition: all 0.2s;
        }
        .feat-card:hover {
            border-color: var(--verde-claro);
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(26,107,58,0.1);
        }
        .feat-icon {
            width: 48px; height: 48px;
            background: var(--verde-escuro);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: var(--amarelo);
        }
        .feat-card h5 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            color: var(--verde-escuro);
            margin-bottom: 10px;
        }
        .feat-card p {
            font-size: 0.87rem;
            color: #666;
            line-height: 1.6;
        }

        /* ── FOOTER ── */
        footer {
            background: var(--verde-escuro);
            padding: 24px;
            text-align: center;
            color: rgba(255,255,255,0.3);
            font-size: 0.8rem;
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        /* Animação entrada */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .anim-1 { animation: fadeUp 0.6s ease both; }
        .anim-2 { animation: fadeUp 0.6s ease 0.15s both; }
        .anim-3 { animation: fadeUp 0.6s ease 0.3s both; }
        .anim-4 { animation: fadeUp 0.6s ease 0.45s both; }

        @media (max-width: 600px) {
            .perfis-grid { grid-template-columns: 1fr; max-width: 300px; }
            .nav-mepi { padding: 20px 24px; }
        }
    </style>
</head>
<body>

<!-- ═══ HERO ═══════════════════════════════════════════════════ -->
<section class="hero">
    <nav class="nav-mepi">
        <a href="/" class="logo">
            <div class="logo-icon">M</div>
            <span class="logo-text">ME<span>PI</span></span>
        </a>
        <a href="{{ route('login') }}" class="btn-login-nav">
            <i class="bi bi-box-arrow-in-right me-1"></i> Entrar
        </a>
    </nav>

    <div class="hero-content">
        <span class="badge-topo anim-1">
            <i class="bi bi-shield-check"></i>
            Gestão de Pessoas &amp; EPIs
        </span>

        <h1 class="hero-title anim-2">
            Segurança e<br><em>pessoas</em> em<br>um só lugar
        </h1>

        <p class="hero-sub anim-3">
            Gerencie funcionários, controle EPIs, processe folhas de pagamento
            e acompanhe férias com agilidade e conformidade CLT.
        </p>

        <p class="label-ou anim-3">Acesse como</p>

        <div class="perfis-grid anim-4">
            <a href="{{ route('login') }}" class="perfil-card">
                <div class="perfil-icon icon-admin"><i class="bi bi-shield-lock"></i></div>
                <span class="perfil-nome">Admin</span>
                <span class="perfil-desc">Controle total do sistema</span>
                <span class="perfil-arrow"><i class="bi bi-arrow-right"></i></span>
            </a>
            <a href="{{ route('login') }}" class="perfil-card">
                <div class="perfil-icon icon-rh"><i class="bi bi-people"></i></div>
                <span class="perfil-nome">RH</span>
                <span class="perfil-desc">Gestão de pessoas e folha</span>
                <span class="perfil-arrow"><i class="bi bi-arrow-right"></i></span>
            </a>
            <a href="{{ route('login') }}" class="perfil-card">
                <div class="perfil-icon icon-func"><i class="bi bi-person-badge"></i></div>
                <span class="perfil-nome">Funcionário</span>
                <span class="perfil-desc">Férias, holerite e EPIs</span>
                <span class="perfil-arrow"><i class="bi bi-arrow-right"></i></span>
            </a>
        </div>
    </div>
</section>

<!-- ═══ FEATURES ════════════════════════════════════════════════ -->
<section class="features-section">
    <div class="container">
        <h2>Tudo que você precisa</h2>
        <p class="sub">Módulos integrados para a gestão completa do seu time</p>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="feat-card">
                    <div class="feat-icon"><i class="bi bi-people-fill"></i></div>
                    <h5>Gestão de Funcionários</h5>
                    <p>Cadastro completo, vínculos de cargo, histórico de admissão e controle de status.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feat-card">
                    <div class="feat-icon"><i class="bi bi-calendar-heart"></i></div>
                    <h5>Controle de Férias CLT</h5>
                    <p>Cálculo automático de períodos aquisitivos, fracionamento e abono pecuniário.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feat-card">
                    <div class="feat-icon"><i class="bi bi-receipt-cutoff"></i></div>
                    <h5>Folha de Pagamento</h5>
                    <p>Simulação com INSS progressivo e IRRF por faixa, conforme tabelas 2024.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feat-card">
                    <div class="feat-icon"><i class="bi bi-shield-check"></i></div>
                    <h5>Controle de EPIs</h5>
                    <p>Cadastro de equipamentos, entregas, devoluções e validade dos itens.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feat-card">
                    <div class="feat-icon"><i class="bi bi-newspaper"></i></div>
                    <h5>Painel de Notícias</h5>
                    <p>Dashboard com notícias em tempo real sobre RH, segurança do trabalho e legislação.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feat-card">
                    <div class="feat-icon"><i class="bi bi-bar-chart-line"></i></div>
                    <h5>Relatórios e Métricas</h5>
                    <p>Visão geral da equipe, EPIs em campo e histórico de folhas por competência.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<h2>👥 Notícias de RH</h2>

@if(!empty($rh['articles'] ?? []))
    @foreach($rh['articles'] as $article)
        <p>
            <a href="{{ $article['url'] }}" target="_blank">
                {{ $article['title'] }}
            </a><br>
            <small>{{ $article['source']['name'] ?? 'Fonte' }}</small>
        </p>
        <hr>
    @endforeach
@else
    <p>Sem notícias de RH no momento.</p>
@endif

<hr>


<!-- ═══ FOOTER ═══════════════════════════════════════════════════ -->
<footer>
    <span>MEPI © {{ date('Y') }} — Projeto Final Programação Web II</span>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
