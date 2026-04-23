<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MEPI') — Sistema de Gestão</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --verde:        #1a6b3a;
            --verde-claro:  #2d9e58;
            --verde-escuro: #0f3d21;
            --verde-hover:  #164f2c;
            --amarelo:      #f5c400;
            --amarelo-claro:#ffe566;
            --amarelo-bg:   rgba(245,196,0,0.1);
            --sidebar-w:    260px;
            --off-white:    #f7f5ee;
            --bg-page:      #f0f2ee;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg-page);
            margin: 0;
            color: #2c2c2c;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--verde-escuro);
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            margin-bottom: 8px;
        }
        .brand-icon {
            width: 38px; height: 38px;
            background: var(--amarelo);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--verde-escuro);
            flex-shrink: 0;
        }
        .brand-name {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.2rem;
            color: #fff;
            letter-spacing: -0.5px;
        }
        .brand-name span { color: var(--amarelo); }

        /* Nav groups */
        .nav-group {
            padding: 0 12px;
            margin-bottom: 4px;
        }
        .nav-group-label {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
            padding: 12px 10px 6px;
        }

        .nav-link-mepi {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.15s;
            margin-bottom: 2px;
        }
        .nav-link-mepi i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }
        .nav-link-mepi:hover {
            background: rgba(255,255,255,0.06);
            color: #fff;
        }
        .nav-link-mepi.active {
            background: var(--amarelo-bg);
            color: var(--amarelo);
            border: 1px solid rgba(245,196,0,0.2);
        }
        .nav-link-mepi.active i { color: var(--amarelo); }

        /* Sidebar footer (usuário) */
        .sidebar-user {
            margin-top: auto;
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }
        .user-box {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(255,255,255,0.05);
        }
        .user-avatar {
            width: 34px; height: 34px;
            background: var(--verde-claro);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }
        .user-info { flex: 1; min-width: 0; }
        .user-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-role {
            font-size: 0.7rem;
            color: var(--amarelo);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-logout {
            color: rgba(255,255,255,0.4);
            background: none;
            border: none;
            padding: 4px;
            cursor: pointer;
            transition: color 0.15s;
            font-size: 1rem;
        }
        .btn-logout:hover { color: #f87171; }

        /* ── TOPBAR ── */
        .topbar {
            position: fixed;
            top: 0; left: var(--sidebar-w); right: 0;
            height: 60px;
            background: #fff;
            border-bottom: 1px solid #e5e5dc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            z-index: 99;
        }
        .topbar-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            color: var(--verde-escuro);
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .topbar-date {
            font-size: 0.8rem;
            color: #888;
        }

        /* ── CONTEÚDO ── */
        .main-content {
            margin-left: var(--sidebar-w);
            padding-top: 60px;
            min-height: 100vh;
        }
        .page-body {
            padding: 32px 28px;
        }

        /* ── CARDS MÉTRICAS ── */
        .metric-card {
            background: #fff;
            border: 1px solid #e5e5dc;
            border-radius: 14px;
            padding: 22px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s;
        }
        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(26,107,58,0.08);
        }
        .metric-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        .icon-green  { background: rgba(26,107,58,0.1);  color: var(--verde); }
        .icon-yellow { background: rgba(245,196,0,0.15); color: #b08c00; }
        .icon-blue   { background: rgba(59,130,246,0.1); color: #3b82f6; }
        .icon-red    { background: rgba(239,68,68,0.1);  color: #ef4444; }

        .metric-val {
            font-family: 'Syne', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--verde-escuro);
            line-height: 1;
        }
        .metric-lbl {
            font-size: 0.8rem;
            color: #888;
            margin-top: 3px;
        }

        /* ── CARD GENÉRICO ── */
        .card-mepi {
            background: #fff;
            border: 1px solid #e5e5dc;
            border-radius: 14px;
            overflow: hidden;
        }
        .card-mepi-header {
            padding: 16px 22px;
            border-bottom: 1px solid #f0f0e8;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-mepi-header h6 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--verde-escuro);
            margin: 0;
        }
        .card-mepi-body { padding: 20px 22px; }

        /* ── BADGE STATUS ── */
        .badge-ativo     { background: rgba(26,107,58,0.1);  color: var(--verde);   font-size:0.72rem; padding:3px 10px; border-radius:20px; font-weight:600; }
        .badge-pendente  { background: rgba(245,196,0,0.15); color: #8a6d00;        font-size:0.72rem; padding:3px 10px; border-radius:20px; font-weight:600; }
        .badge-negado    { background: rgba(239,68,68,0.1);  color: #dc2626;        font-size:0.72rem; padding:3px 10px; border-radius:20px; font-weight:600; }
        .badge-aprovado  { background: rgba(26,107,58,0.1);  color: var(--verde);   font-size:0.72rem; padding:3px 10px; border-radius:20px; font-weight:600; }

        /* ── TABELA ── */
        .table-mepi { font-size: 0.875rem; }
        .table-mepi thead th {
            background: var(--off-white);
            color: #555;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            padding: 11px 16px;
        }
        .table-mepi tbody td {
            padding: 12px 16px;
            border-color: #f0f0e8;
            vertical-align: middle;
        }
        .table-mepi tbody tr:hover td { background: #fafaf5; }

        /* ── BTN PRIMÁRIO ── */
        .btn-mepi {
            background: var(--verde);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 9px 20px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }
        .btn-mepi:hover { background: var(--verde-claro); color: #fff; }
        .btn-mepi-amarelo {
            background: var(--amarelo);
            color: var(--verde-escuro);
        }
        .btn-mepi-amarelo:hover { background: var(--amarelo-claro); color: var(--verde-escuro); }

        /* Alerts flash */
        .alert-mepi-success {
            background: rgba(26,107,58,0.08);
            border: 1px solid rgba(26,107,58,0.2);
            color: var(--verde);
            border-radius: 10px;
            padding: 12px 18px;
            font-size: 0.88rem;
        }
        .alert-mepi-error {
            background: rgba(239,68,68,0.07);
            border: 1px solid rgba(239,68,68,0.2);
            color: #dc2626;
            border-radius: 10px;
            padding: 12px 18px;
            font-size: 0.88rem;
        }

        /* Toggle sidebar mobile */
        .sidebar-toggle { display: none; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .topbar { left: 0; }
            .sidebar-toggle { display: flex; }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- ═══ SIDEBAR ════════════════════════════════════════════════ -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <<img src="{{ asset('images/logo.jpg') }}" alt="Logo MEPI" style="height:40px;">
        <span class="brand-name">ME<span>PI</span></span>
    </div>

    <div class="nav-group">
        <div class="nav-group-label">Principal</div>
        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->role === 'rh' ? route('rh.dashboard') : route('funcionario.dashboard')) }}"
           class="nav-link-mepi {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
    </div>

    {{-- Admin e RH --}}
@if(in_array(auth()->user()->role, ['admin','rh']))
@php $role = auth()->user()->role; @endphp
<div class="nav-group">
    <div class="nav-group-label">Gestão</div>
    <a href="{{ route($role.'.funcionarios.index') }}"
       class="nav-link-mepi {{ request()->routeIs('*.funcionarios.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Funcionários
    </a>
    <a href="{{ route($role.'.cargos.index') }}"
       class="nav-link-mepi {{ request()->routeIs('*.cargos.*') ? 'active' : '' }}">
        <i class="bi bi-briefcase"></i> Cargos
    </a>
    <a href="{{ route($role.'.ferias.index') }}"
       class="nav-link-mepi {{ request()->routeIs('*.ferias.*') ? 'active' : '' }}">
        <i class="bi bi-calendar-heart"></i> Férias
    </a>
    <a href="{{ route($role.'.folha.index') }}"
       class="nav-link-mepi {{ request()->routeIs('*.folha.*') ? 'active' : '' }}">
        <i class="bi bi-receipt-cutoff"></i> Folha de Pagamento
    </a>
    <a href="{{ route($role.'.equipamentos.index') }}"
       class="nav-link-mepi {{ request()->routeIs('*.equipamentos.*') ? 'active' : '' }}">
        <i class="bi bi-shield-check"></i> Equipamentos
    </a>
    <a href="{{ route($role.'.entregas.index') }}"
       class="nav-link-mepi {{ request()->routeIs('*.entregas.*') ? 'active' : '' }}">
        <i class="bi bi-box-seam"></i> Entregas EPI
    </a>
</div>
@endif
    {{-- Somente Admin --}}
    @if(auth()->user()->role === 'admin')
    <div class="nav-group">
        <div class="nav-group-label">Administração</div>
        <a href="{{ route('admin.usuarios.index') }}"
           class="nav-link-mepi {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i> Usuários
        </a>
    </div>
    @endif

    {{-- Funcionário --}}
    @if(auth()->user()->role === 'funcionario')
    <div class="nav-group">
        <div class="nav-group-label">Minha Área</div>
        <a href="{{ route('funcionario.perfil') }}"
           class="nav-link-mepi {{ request()->routeIs('funcionario.perfil') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> Meu Perfil
        </a>
        <a href="{{ route('funcionario.ferias') }}"
           class="nav-link-mepi {{ request()->routeIs('funcionario.ferias') ? 'active' : '' }}">
            <i class="bi bi-calendar-heart"></i> Minhas Férias
        </a>
        <a href="{{ route('funcionario.holerite') }}"
           class="nav-link-mepi {{ request()->routeIs('funcionario.holerite') ? 'active' : '' }}">
            <i class="bi bi-receipt-cutoff"></i> Holerite
        </a>
        <a href="{{ route('funcionario.equipamentos') }}"
           class="nav-link-mepi {{ request()->routeIs('funcionario.equipamentos') ? 'active' : '' }}">
            <i class="bi bi-shield-check"></i> Meus EPIs
        </a>
    </div>
    @endif

    <!-- Usuário logado -->
    <div class="sidebar-user">
        <div class="user-box">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout" title="Sair">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- ═══ TOPBAR ══════════════════════════════════════════════════ -->
<header class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle btn btn-sm" onclick="document.getElementById('sidebar').classList.toggle('open')">
            <i class="bi bi-list fs-5"></i>
        </button>
        <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
    </div>
    <div class="topbar-right">
        <span class="topbar-date d-none d-md-block">
            <i class="bi bi-calendar3 me-1"></i>
            {{ \Carbon\Carbon::now()->locale('pt_BR')->isoFormat('D [de] MMMM [de] YYYY') }}
        </span>
    </div>
</header>

<!-- ═══ CONTEÚDO ════════════════════════════════════════════════ -->
<main class="main-content">
    <div class="page-body">

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="alert-mepi-success mb-4">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert-mepi-error mb-4">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
        @endif

        @yield('content')
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
