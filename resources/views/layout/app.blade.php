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
        body { font-family: 'DM Sans', sans-serif; background: var(--bg-page); margin: 0; color: #2c2c2c; }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--verde-escuro);
            display: flex; flex-direction: column;
            z-index: 100; overflow-y: auto;
            transition: transform .25s;
        }
        .sidebar-brand {
            padding: 22px 20px 18px;
            display: flex; align-items: center; gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,.07);
            margin-bottom: 6px;
        }
        .brand-icon {
            width: 36px; height: 36px;
            background: var(--amarelo); border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; overflow: hidden;
        }
        .brand-icon img { width: 100%; height: 100%; object-fit: cover; }
        .brand-icon span {
            font-family: 'Syne', sans-serif; font-weight: 800;
            font-size: 1rem; color: var(--verde-escuro);
        }
        .brand-name { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.15rem; color: #fff; letter-spacing: -.5px; }
        .brand-name span { color: var(--amarelo); }

        .nav-group { padding: 0 12px; margin-bottom: 4px; }
        .nav-group-label {
            font-size: .63rem; font-weight: 700; letter-spacing: 1.5px;
            text-transform: uppercase; color: rgba(255,255,255,.28);
            padding: 10px 10px 5px;
        }
        .nav-link-mepi {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 8px;
            color: rgba(255,255,255,.6); text-decoration: none;
            font-size: .87rem; font-weight: 500;
            transition: all .15s; margin-bottom: 2px;
        }
        .nav-link-mepi i { font-size: .98rem; width: 18px; text-align: center; flex-shrink: 0; }
        .nav-link-mepi:hover { background: rgba(255,255,255,.06); color: #fff; }
        .nav-link-mepi.active {
            background: var(--amarelo-bg); color: var(--amarelo);
            border: 1px solid rgba(245,196,0,.2);
        }
        .nav-link-mepi.active i { color: var(--amarelo); }

        /* Badge notificação no nav */
        .nav-badge {
            margin-left: auto;
            background: var(--amarelo); color: var(--verde-escuro);
            font-size: .62rem; font-weight: 800;
            padding: 1px 6px; border-radius: 10px;
            min-width: 18px; text-align: center;
        }

        .sidebar-user {
            margin-top: auto; padding: 14px;
            border-top: 1px solid rgba(255,255,255,.07);
        }
        .user-box {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 10px;
            background: rgba(255,255,255,.05);
        }
        .user-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--verde-claro);
            display: flex; align-items: center; justify-content: center;
            font-size: .82rem; font-weight: 700; color: #fff; flex-shrink: 0;
        }
        .user-info { flex: 1; min-width: 0; }
        .user-name { font-size: .81rem; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: .68rem; color: var(--amarelo); font-weight: 500; text-transform: uppercase; letter-spacing: .5px; }
        .btn-logout { color: rgba(255,255,255,.35); background: none; border: none; padding: 3px; cursor: pointer; transition: color .15s; font-size: .98rem; }
        .btn-logout:hover { color: #f87171; }

        /* ── TOPBAR ── */
        .topbar {
            position: fixed; top: 0; left: var(--sidebar-w); right: 0;
            height: 58px; background: #fff;
            border-bottom: 1px solid #e5e5dc;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 28px; z-index: 99;
        }
        .topbar-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: .98rem; color: var(--verde-escuro); }
        .topbar-right { display: flex; align-items: center; gap: 14px; }
        .topbar-date { font-size: .78rem; color: #999; }

        /* ── CONTEÚDO ── */
        .main-content { margin-left: var(--sidebar-w); padding-top: 58px; min-height: 100vh; }
        .page-body { padding: 28px 28px; }

        /* ── CARDS ── */
        .metric-card {
            background: #fff; border: 1px solid #e5e5dc; border-radius: 13px;
            padding: 20px 22px; display: flex; align-items: center; gap: 14px;
            transition: all .2s;
        }
        .metric-card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(26,107,58,.08); }
        .metric-icon { width: 46px; height: 46px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
        .icon-green  { background: rgba(26,107,58,.1);  color: var(--verde); }
        .icon-yellow { background: rgba(245,196,0,.15); color: #b08c00; }
        .icon-blue   { background: rgba(59,130,246,.1); color: #3b82f6; }
        .icon-red    { background: rgba(239,68,68,.1);  color: #ef4444; }
        .metric-val  { font-family: 'Syne', sans-serif; font-size: 1.7rem; font-weight: 800; color: var(--verde-escuro); line-height: 1; }
        .metric-lbl  { font-size: .78rem; color: #888; margin-top: 3px; }

        .card-mepi { background: #fff; border: 1px solid #e5e5dc; border-radius: 13px; overflow: hidden; }
        .card-mepi-header {
            padding: 14px 20px; border-bottom: 1px solid #f0f0e8;
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-mepi-header h6 { font-family: 'Syne', sans-serif; font-weight: 700; font-size: .88rem; color: var(--verde-escuro); margin: 0; }
        .card-mepi-body { padding: 18px 20px; }

        /* ── STATUS BADGES ── */
        .badge-ativo     { background: rgba(26,107,58,.1);  color: var(--verde);  font-size:.72rem; padding:3px 10px; border-radius:20px; font-weight:600; white-space:nowrap; }
        .badge-pendente  { background: rgba(245,196,0,.15); color: #8a6d00;       font-size:.72rem; padding:3px 10px; border-radius:20px; font-weight:600; white-space:nowrap; }
        .badge-negado    { background: rgba(239,68,68,.1);  color: #dc2626;       font-size:.72rem; padding:3px 10px; border-radius:20px; font-weight:600; white-space:nowrap; }
        .badge-aprovado  { background: rgba(26,107,58,.1);  color: var(--verde);  font-size:.72rem; padding:3px 10px; border-radius:20px; font-weight:600; white-space:nowrap; }
        .badge-inativo   { background: #f0f0e8;             color: #999;          font-size:.72rem; padding:3px 10px; border-radius:20px; font-weight:600; white-space:nowrap; }
        .badge-afastado  { background: rgba(59,130,246,.1); color: #3b82f6;       font-size:.72rem; padding:3px 10px; border-radius:20px; font-weight:600; white-space:nowrap; }

        /* ── TABELA ── */
        .table-mepi { font-size: .875rem; }
        .table-mepi thead th {
            background: var(--off-white); color: #555; font-weight: 600;
            font-size: .72rem; text-transform: uppercase; letter-spacing: .5px;
            border: none; padding: 10px 16px;
        }
        .table-mepi tbody td { padding: 11px 16px; border-color: #f0f0e8; vertical-align: middle; }
        .table-mepi tbody tr:hover td { background: #fafaf5; }

        /* ── BOTÕES ── */
        .btn-mepi {
            background: var(--verde); color: #fff; border: none; border-radius: 8px;
            padding: 8px 18px; font-size: .84rem; font-weight: 600;
            transition: all .2s; text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px; cursor: pointer;
        }
        .btn-mepi:hover { background: var(--verde-claro); color: #fff; }
        .btn-mepi-amarelo { background: var(--amarelo); color: var(--verde-escuro); }
        .btn-mepi-amarelo:hover { background: var(--amarelo-claro); color: var(--verde-escuro); }
        .btn-mepi-outline {
            background: transparent; color: var(--verde);
            border: 1.5px solid var(--verde); border-radius: 8px;
            padding: 7px 17px; font-size: .84rem; font-weight: 600;
            text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
            transition: all .2s; cursor: pointer;
        }
        .btn-mepi-outline:hover { background: var(--verde); color: #fff; }

        /* Paginação customizada */
        .pagination .page-link { color: var(--verde); border-color: #e5e5dc; font-size: .82rem; }
        .pagination .page-item.active .page-link { background: var(--verde); border-color: var(--verde); }
        .pagination .page-link:hover { background: var(--off-white); color: var(--verde-escuro); }

        /* ── MOBILE SIDEBAR ── */
        .sidebar-toggle { display: none; }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 99; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }
            .main-content { margin-left: 0; }
            .topbar { left: 0; }
            .sidebar-toggle { display: flex; }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Overlay mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- ═══ SIDEBAR ════════════════════════════════════════════════ -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <img src="{{ asset('images/mepi-icon-64.png') }}" alt="MEPI"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
            <span style="display:none;">M</span>
        </div>
        <span class="brand-name">ME<span>PI</span></span>
    </div>

    <div class="nav-group">
        <div class="nav-group-label">Principal</div>
        @php
            $dashRoute = match(auth()->user()->role) {
                'admin' => route('admin.dashboard'),
                'rh'    => route('rh.dashboard'),
                default => route('funcionario.dashboard'),
            };
            $pendentes = auth()->user()->role !== 'funcionario'
                ? \App\Models\Ferias::where('status','pendente')->count()
                : 0;
        @endphp
        <a href="{{ $dashRoute }}"
           class="nav-link-mepi {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
    </div>

    @if(in_array(auth()->user()->role, ['admin','rh']))
    <div class="nav-group">
        <div class="nav-group-label">Gestão de Pessoas</div>
        <a href="{{ route(auth()->user()->role.'.funcionarios.index') }}"
           class="nav-link-mepi {{ request()->routeIs('*.funcionarios.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Funcionários
        </a>
        <a href="{{ route(auth()->user()->role.'.cargos.index') }}"
           class="nav-link-mepi {{ request()->routeIs('*.cargos.*') ? 'active' : '' }}">
            <i class="bi bi-briefcase"></i> Cargos
        </a>
        <a href="{{ route(auth()->user()->role.'.ferias.index') }}"
           class="nav-link-mepi {{ request()->routeIs('*.ferias.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-heart"></i> Férias
            @if($pendentes > 0)
                <span class="nav-badge">{{ $pendentes }}</span>
            @endif
        </a>
        <a href="{{ route(auth()->user()->role.'.folha.index') }}"
           class="nav-link-mepi {{ request()->routeIs('*.folha.*') ? 'active' : '' }}">
            <i class="bi bi-receipt-cutoff"></i> Folha de Pagamento
        </a>
    </div>
    <div class="nav-group">
        <div class="nav-group-label">Equipamentos</div>
        <a href="{{ route(auth()->user()->role.'.equipamentos.index') }}"
           class="nav-link-mepi {{ request()->routeIs('*.equipamentos.*') ? 'active' : '' }}">
            <i class="bi bi-shield-check"></i> Equipamentos
        </a>
        <a href="{{ route(auth()->user()->role.'.entregas.index') }}"
           class="nav-link-mepi {{ request()->routeIs('*.entregas.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Entregas EPI
        </a>
       <a href="{{ route(auth()->user()->role.'.reservas.index') }}"
            class="nav-link-mepi {{ request()->routeIs('*.reservas.*') ? 'active' : '' }}">
                <i class="bi bi-bookmark-check"></i> Reservas EPI
                @php $pendentesReserva = \App\Models\ReservaEquipamento::where('status','pendente')->count(); @endphp
                @if($pendentesReserva > 0)
                    <span class="nav-badge">{{ $pendentesReserva }}</span>
                @endif
        </a>
    </div>
    @endif

    @if(auth()->user()->role === 'admin')
    <div class="nav-group">
        <div class="nav-group-label">Administração</div>
        <a href="{{ route('admin.usuarios.index') }}"
           class="nav-link-mepi {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i> Usuários
        </a>
        
        <a href="{{ route('admin.auditoria.index') }}"
        class="nav-link-mepi {{ request()->routeIs('admin.auditoria.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Auditoria
        </a>
    </div>
    
    @endif

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
            <a href="{{ route('funcionario.reservas') }}"
            class="nav-link-mepi {{ request()->routeIs('funcionario.reservas.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Minhas Reservas
            </a>
    </div>
    @endif

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
        <button class="sidebar-toggle btn btn-sm p-1" onclick="toggleSidebar()">
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
        {{-- Flash messages via componente --}}
        <x-flash />

        {{-- Breadcrumb (opcional por página) --}}
        @hasSection('breadcrumb')
            @yield('breadcrumb')
        @endif

        @yield('content')
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
}
</script>
@stack('scripts')
</body>
</html>
