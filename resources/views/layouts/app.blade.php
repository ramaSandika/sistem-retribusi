<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>@yield('title', 'Sistem Realisasi Retribusi') - BAPENDA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --primary-red: #dc2626;
            --dark-red: #991b1b;
            --deep-burgundy: #7f1d1d;
            --sidebar-width: 260px;
            --glass-bg: rgba(255, 255, 255, 0.10);
            --glass-border: rgba(255, 255, 255, 0.18);
            --glass-blur: blur(18px);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            color: #fff;
        }

        /* ===== BACKGROUND SLIDESHOW CROSSFADE ===== */
        .bg-photo {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
        }
        .bg-photo-1 {
            background-image: url('{{ asset("bapenda_gedung.jpg") }}');
            animation: bgFade 14s ease-in-out infinite;
        }
        .bg-photo-2 {
            background-image: url('{{ asset("bapenda_interior.jpg") }}');
            animation: bgFade 14s ease-in-out infinite;
            animation-delay: -7s;
        }
        @keyframes bgFade {
            0%, 45%  { opacity: 1; }
            50%, 95% { opacity: 0; }
            100%     { opacity: 1; }
        }
        .bg-overlay {
            position: fixed;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(12, 8, 8, 0.72) 0%,
                rgba(20, 10, 10, 0.60) 50%,
                rgba(8, 5, 5, 0.75) 100%
            );
            z-index: 1;
            pointer-events: none;
        }

        /* ===== SIDEBAR GLASS ===== */
        .sidebar-desktop {
            width: var(--sidebar-width);
            background: rgba(60, 10, 10, 0.55);
            backdrop-filter: blur(22px);
            -webkit-backdrop-filter: blur(22px);
            border-right: 1px solid rgba(255, 255, 255, 0.10);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            z-index: 200;
            color: white;
            transition: all 0.3s ease;
        }

        .brand-box {
            padding: 22px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.10);
        }
        .brand-logo-icon {
            width: 44px; height: 44px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }

        .nav-section-title {
            font-size: 0.70rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255,255,255,0.40);
            padding: 18px 24px 6px;
            font-weight: 700;
        }

        .sidebar-desktop .nav-link {
            color: rgba(255, 255, 255, 0.78);
            font-weight: 600;
            padding: 11px 18px;
            margin: 4px 12px;
            border-radius: 12px;
            display: flex; align-items: center; gap: 12px;
            transition: all 0.35s cubic-bezier(0.25, 1, 0.5, 1);
            text-decoration: none;
            font-size: 0.90rem;
            position: relative;
            overflow: hidden;
        }
        .sidebar-desktop .nav-link i {
            transition: transform 0.35s cubic-bezier(0.25, 1, 0.5, 1);
        }
        .sidebar-desktop .nav-link:hover {
            background: rgba(255, 255, 255, 0.14);
            color: #ffffff;
            transform: translateX(4px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }
        .sidebar-desktop .nav-link:hover i {
            transform: scale(1.15);
        }
        .sidebar-desktop .nav-link.active {
            background: linear-gradient(135deg, rgba(220,38,38,0.35), rgba(153,27,27,0.25)) !important;
            border: 1px solid rgba(220,38,38,0.50);
            color: #ffffff !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 20px rgba(220,38,38,0.25);
        }
        .sidebar-desktop .nav-link.active i {
            color: #fca5a5;
        }

        /* ===== MAIN WRAPPER ===== */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            padding: 28px 30px;
            min-height: 100vh;
            position: relative;
            z-index: 10;
        }

        /* ===== CARD GLASS UNTUK KONTEN (KONTRAS TINGGI, RAMAH MATA) ===== */
        .card-custom {
            background: rgba(30, 8, 8, 0.72);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1.5px solid rgba(255, 255, 255, 0.18);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.30);
            color: #ffffff;
            transition: all 0.25s ease;
        }
        .card-custom:hover {
            background: rgba(40, 10, 10, 0.80);
            border-color: rgba(255, 255, 255, 0.28);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.40);
        }

        /* Teks di dalam card dengan tingkat keterbacaan tinggi */
        .card-custom .text-muted { color: rgba(255,255,255,0.78) !important; font-size: 0.90rem; }
        .card-custom .text-danger { color: #fca5a5 !important; font-weight: 700; }
        .card-custom h5 { color: #ffffff; font-size: 1.25rem; font-weight: 800; }
        .card-custom h6 { color: #ffffff; font-size: 1.05rem; font-weight: 700; }
        .card-custom p { color: rgba(255,255,255,0.92); font-size: 0.95rem; line-height: 1.5; }

        /* ===== TOPBAR ===== */
        .topbar-glass {
            background: rgba(35, 10, 10, 0.75);
            backdrop-filter: blur(16px);
            border: 1.5px solid rgba(255,255,255,0.18);
            border-radius: 18px;
            padding: 16px 26px;
            margin-bottom: 26px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.25);
        }
        .topbar-glass h4 { color: #ffffff; font-weight: 800; font-size: 1.35rem; margin: 0; }
        .topbar-glass p { color: rgba(255,255,255,0.80); font-size: 0.90rem; font-weight: 500; margin-top: 2px; }

        /* ===== BADGE & BUTTONS ===== */
        .badge-red {
            background: rgba(220,38,38,0.30);
            border: 1.5px solid rgba(220,38,38,0.55);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 6px 12px;
        }
        .btn-red {
            background: linear-gradient(135deg, #b91c1c, #991b1b);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            padding: 11px 24px;
            border-radius: 12px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(185,28,28,0.40);
        }
        .btn-red:hover {
            background: linear-gradient(135deg, #991b1b, #7f1d1d);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(185,28,28,0.50);
        }

        /* ===== STAT ICON ===== */
        .stat-icon {
            width: 58px; height: 58px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
        }

        /* ===== TABLE GLASS (HURUF LEBIH BESAR, PADDING NYAMAN, BEBAS DARI BOOTSTRAP WHITE OVERLAY) ===== */
        .table {
            --bs-table-bg: transparent !important;
            --bs-table-color: #ffffff !important;
            --bs-table-hover-bg: rgba(255, 255, 255, 0.08) !important;
            --bs-table-hover-color: #ffffff !important;
            color: #ffffff !important;
            background-color: transparent !important;
            font-size: 0.95rem;
            margin-bottom: 0;
        }
        .table > :not(caption) > * > * {
            background-color: transparent !important;
            color: #ffffff !important;
            border-bottom-color: rgba(255, 255, 255, 0.12);
        }
        .table th {
            color: #ffffff !important;
            font-size: 0.88rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border-bottom: 2px solid rgba(220, 38, 38, 0.50) !important;
            padding: 14px 16px;
            background: rgba(0, 0, 0, 0.40) !important;
        }
        .table td {
            border-color: rgba(255, 255, 255, 0.10) !important;
            vertical-align: middle;
            padding: 14px 16px;
            color: rgba(255, 255, 255, 0.95) !important;
        }
        .table-hover tbody tr:hover { background: rgba(255,255,255,0.10) !important; }
        .table-light, .table-light thead tr, .table-light th, .table-light td {
            background: transparent !important;
            --bs-table-bg: transparent !important;
            color: #ffffff !important;
        }

        /* ===== FORM CONTROLS GLASS (RAMAH DIKLIK & DIBACA) ===== */
        .form-control, .form-select {
            background: rgba(255,255,255,0.15) !important;
            border: 1.5px solid rgba(255,255,255,0.28) !important;
            color: #ffffff !important;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 0.95rem;
            font-weight: 600;
        }
        .form-control::placeholder { color: rgba(255,255,255,0.55) !important; font-weight: 400; }
        .form-control:focus, .form-select:focus {
            background: rgba(255,255,255,0.22) !important;
            border-color: #fca5a5 !important;
            box-shadow: 0 0 0 4px rgba(220,38,38,0.25) !important;
            color: #ffffff !important;
        }
        .form-select option { background: #260a0a; color: #ffffff; }
        .input-group-text {
            background: rgba(255,255,255,0.12) !important;
            border: 1.5px solid rgba(255,255,255,0.28) !important;
            color: #ffffff !important;
            border-radius: 12px;
            font-size: 0.95rem;
        }
        .form-label { color: #ffffff !important; font-size: 0.92rem; font-weight: 700; margin-bottom: 6px; }

        /* ===== ALERT GLASS ===== */
        .alert-success {
            background: rgba(22,163,74,0.20) !important;
            border: 1px solid rgba(22,163,74,0.35) !important;
            color: #bbf7d0 !important;
            border-radius: 14px;
        }
        .alert-danger {
            background: rgba(220,38,38,0.20) !important;
            border: 1px solid rgba(220,38,38,0.35) !important;
            color: #fecaca !important;
            border-radius: 14px;
        }
        .alert-info {
            background: rgba(59,130,246,0.20) !important;
            border: 1px solid rgba(59,130,246,0.35) !important;
            color: #bfdbfe !important;
            border-radius: 14px;
        }
        .btn-close { filter: invert(1); }

        /* ===== PAGINATION ===== */
        .page-link {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.80);
            border-radius: 8px !important;
        }
        .page-link:hover { background: rgba(255,255,255,0.18); color: #fff; }
        .page-item.active .page-link {
            background: #dc2626;
            border-color: #dc2626;
            color: #fff;
        }

        /* ===== MODAL GLASS & Z-INDEX FIX ===== */
        body.modal-open .main-wrapper {
            z-index: 1055 !important;
        }
        .modal-backdrop {
            z-index: 1050 !important;
            background-color: rgba(0, 0, 0, 0.65) !important;
        }
        .modal {
            z-index: 1060 !important;
        }
        .modal-content {
            background: rgba(25, 10, 10, 0.92) !important;
            backdrop-filter: blur(25px) !important;
            -webkit-backdrop-filter: blur(25px) !important;
            border: 1px solid rgba(255,255,255,0.22) !important;
            color: #fff !important;
            border-radius: 20px !important;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.6) !important;
        }
        .modal-header { border-color: rgba(255,255,255,0.12) !important; }
        .modal-footer { border-color: rgba(255,255,255,0.12) !important; }

        /* ===== MOBILE HEADER ===== */
        .mobile-header {
            display: none;
            background: rgba(40, 10, 10, 0.70);
            backdrop-filter: blur(16px);
            color: white;
            padding: 14px 20px;
            position: sticky;
            top: 0;
            z-index: 1050;
            border-bottom: 1px solid rgba(255,255,255,0.10);
        }

        /* ===== MOBILE & TABLET RESPONSIVE ===== */
        @media (max-width: 991.98px) {
            .sidebar-desktop { display: none; }
            .main-wrapper {
                margin-left: 0;
                padding: 14px 14px 80px;
            }
            .mobile-header { display: flex; align-items: center; justify-content: space-between; }
            .topbar-glass {
                padding: 12px 16px;
                border-radius: 14px;
                margin-bottom: 16px;
            }
            .topbar-glass h4 { font-size: 1.05rem; }
            .topbar-glass p { font-size: 0.75rem; }
            .card-custom { border-radius: 14px; }
        }

        /* Small phone */
        @media (max-width: 575.98px) {
            .main-wrapper { padding: 12px 12px 70px; }
            .topbar-glass h4 { font-size: 0.95rem; }
            .stat-icon { width: 42px; height: 42px; font-size: 18px; }
            .card-custom { padding: 14px !important; }
            .table { font-size: 0.78rem; }
            .table td, .table th { padding: 8px 6px; }
            .btn-red { padding: 8px 14px; font-size: 0.82rem; }
            .badge { font-size: 0.70rem; }
            /* Sembunyikan badge topbar di HP sangat kecil */
            .topbar-glass .badge { display: none; }
        }

        /* ===== BOTTOM NAV BAR untuk HP ===== */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            z-index: 1100;
            background: rgba(25, 8, 8, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255,255,255,0.12);
            padding: 10px 0 14px;
        }
        .mobile-bottom-nav .nav-item {
            flex: 1;
            text-align: center;
        }
        .mobile-bottom-nav .nav-item a {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-size: 0.65rem;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        }
        .mobile-bottom-nav .nav-item a i {
            font-size: 1.10rem;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .mobile-bottom-nav .nav-item a.active,
        .mobile-bottom-nav .nav-item a:hover {
            color: #fca5a5;
        }
        .mobile-bottom-nav .nav-item a.active i {
            color: #ef4444;
            transform: translateY(-2px) scale(1.18);
        }
        @media (max-width: 991.98px) {
            .mobile-bottom-nav { display: flex; }
            /* Sembunyikan hamburger header karena ada bottom nav */
            .mobile-header button { display: none; }
        }

        /* ===== BADGE STATUS ===== */
        .badge.bg-success-subtle { background: rgba(22,163,74,0.20) !important; border: 1px solid rgba(22,163,74,0.40); }
        .badge.bg-warning-subtle { background: rgba(234,179,8,0.20) !important; border: 1px solid rgba(234,179,8,0.40); }
        .badge.bg-danger-subtle  { background: rgba(220,38,38,0.20) !important; border: 1px solid rgba(220,38,38,0.40); }
        .badge.text-success { color: #86efac !important; }
        .badge.text-warning { color: #fde68a !important; }
        .badge.text-danger  { color: #fca5a5 !important; }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Background slideshow BAPENDA -->
    <div class="bg-photo bg-photo-1"></div>
    <div class="bg-photo bg-photo-2"></div>
    <div class="bg-overlay"></div>

    <!-- MOBILE HEADER -->
    <header class="mobile-header">
        <div class="d-flex align-items-center gap-2">
            <div style="width:36px;height:36px;background:rgba(255,255,255,0.15);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-building-columns text-white"></i>
            </div>
            <div>
                <h6 class="fw-bold m-0 text-white" style="line-height:1.2;">Sistem Retribusi</h6>
                <small style="color:rgba(255,255,255,0.50);font-size:0.70rem;">BAPENDA Kab. Badung</small>
            </div>
        </div>
        <button class="btn btn-outline-light rounded-circle p-2" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
            <i class="fas fa-bars fa-lg"></i>
        </button>
    </header>

    <!-- MOBILE OFFCANVAS SIDEBAR -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar"
         style="background: rgba(40,8,8,0.90); backdrop-filter:blur(22px); border-right:1px solid rgba(255,255,255,0.10); color:white;">
        <div class="offcanvas-header" style="border-bottom:1px solid rgba(255,255,255,0.10);">
            <h5 class="offcanvas-title fw-bold text-white d-flex align-items-center gap-2">
                <i class="fas fa-building-columns"></i> SIRETRIBUSI
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0 pt-2">
            <div class="nav-section-title">Menu Utama</div>
            <a href="{{ route('dashboard') }}"
               class="d-flex align-items-center gap-3 px-4 py-3 text-white text-decoration-none fw-600
                      {{ request()->routeIs('dashboard') ? 'bg-white bg-opacity-10 rounded-3 mx-2' : '' }}"
               style="font-size:0.90rem;">
                <i class="fas fa-chart-pie"></i> Dashboard Overview
            </a>
            <a href="{{ route('upload.index') }}"
               class="d-flex align-items-center gap-3 px-4 py-3 text-white text-decoration-none fw-600
                      {{ request()->routeIs('upload.*') ? 'bg-white bg-opacity-10 rounded-3 mx-2' : '' }}"
               style="font-size:0.90rem;">
                <i class="fas fa-file-upload"></i> Upload PDF & Parsing
            </a>
            <a href="{{ route('realisasi.index') }}"
               class="d-flex align-items-center gap-3 px-4 py-3 text-white text-decoration-none fw-600
                      {{ request()->routeIs('realisasi.*') ? 'bg-white bg-opacity-10 rounded-3 mx-2' : '' }}"
               style="font-size:0.90rem;">
                <i class="fas fa-table"></i> Data Realisasi
            </a>
            @if(Auth::check() && Auth::user()->isAdmin())
            <a href="{{ route('master.index') }}"
               class="d-flex align-items-center gap-3 px-4 py-3 text-white text-decoration-none fw-600
                      {{ request()->routeIs('master.*') ? 'bg-white bg-opacity-10 rounded-3 mx-2' : '' }}"
               style="font-size:0.90rem;">
                <i class="fas fa-list-check"></i> Master Retribusi
            </a>
            <a href="{{ route('audit.index') }}"
               class="d-flex align-items-center gap-3 px-4 py-3 text-white text-decoration-none fw-600
                      {{ request()->routeIs('audit.*') ? 'bg-white bg-opacity-10 rounded-3 mx-2' : '' }}"
               style="font-size:0.90rem;">
                <i class="fas fa-history"></i> Audit Trail Log
            </a>
            @endif
            <a href="{{ route('ocr.index') }}"
               class="d-flex align-items-center gap-3 px-4 py-3 text-white text-decoration-none fw-600
                      {{ request()->routeIs('ocr.*') ? 'bg-white bg-opacity-10 rounded-3 mx-2' : '' }}"
               style="font-size:0.90rem;">
                <i class="fas fa-wand-magic-sparkles"></i> OCR Gemini AI
            </a>
            <hr style="border-color:rgba(255,255,255,0.10); margin: 8px 16px;">
            <form action="{{ route('logout') }}" method="POST" class="px-3 pb-4">
                @csrf
                <button type="submit" class="btn btn-outline-light w-100">
                    <i class="fas fa-sign-out-alt me-2"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    <!-- DESKTOP SIDEBAR GLASS -->
    <aside class="sidebar-desktop">
        <div class="brand-box d-flex align-items-center gap-3">
            <div class="brand-logo-icon">
                <i class="fas fa-building-columns text-white"></i>
            </div>
            <div>
                <h6 class="fw-bold m-0 text-white" style="font-size:0.88rem;">BAPENDA</h6>
                <small style="color:rgba(255,255,255,0.45);font-size:0.70rem;">Kab. Badung</small>
            </div>
        </div>

        <div class="nav-section-title">Navigasi Utama</div>

        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> Overview
        </a>
        <a href="{{ route('upload.index') }}"
           class="nav-link {{ request()->routeIs('upload.*') ? 'active' : '' }}">
            <i class="fas fa-file-upload"></i> Upload PDF & Parsing
        </a>
        <a href="{{ route('realisasi.index') }}"
           class="nav-link {{ request()->routeIs('realisasi.*') ? 'active' : '' }}">
            <i class="fas fa-table"></i> Data Realisasi
        </a>

        <a href="{{ route('profile.index') }}"
           class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="fas fa-user-gear"></i> Profil & Password
        </a>

        @if(Auth::check() && Auth::user()->isAdmin())
        <div class="nav-section-title">Administrasi</div>
        <a href="{{ route('master.index') }}"
           class="nav-link {{ request()->routeIs('master.*') ? 'active' : '' }}">
            <i class="fas fa-list-check"></i> Master Retribusi
        </a>
        <a href="{{ route('users.index') }}"
           class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="fas fa-users-gear"></i> Kelola User OPD
        </a>
        <a href="{{ route('audit.index') }}"
           class="nav-link {{ request()->routeIs('audit.*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Audit Log
        </a>
        @endif

        <a href="{{ route('ocr.index') }}"
           class="nav-link {{ request()->routeIs('ocr.*') ? 'active' : '' }}">
            <i class="fas fa-wand-magic-sparkles"></i> OCR Gemini AI
        </a>

        <!-- User info bottom -->
        <div class="position-absolute bottom-0 start-0 w-100 p-3"
             style="border-top: 1px solid rgba(255,255,255,0.10);">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="bg-white rounded-circle text-danger d-flex align-items-center justify-content-center fw-bold"
                     style="width:34px;height:34px;font-size:14px;flex-shrink:0;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div style="line-height:1.2; overflow:hidden; flex:1;">
                    <span class="d-block text-white small fw-bold text-truncate">
                        {{ Auth::user()->name ?? 'Pengguna' }}
                    </span>
                    <small style="color:rgba(255,255,255,0.45);font-size:0.68rem;">
                        {{ Auth::user()->opd_name ?? 'OPD' }}
                    </small>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light w-100" style="font-size:0.80rem;">
                    <i class="fas fa-sign-out-alt me-1"></i> Log Out
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-wrapper">

        <!-- TOPBAR -->
        <div class="topbar-glass d-flex align-items-center justify-content-between">
            <div>
                <h4 class="fw-bold">@yield('page_heading', 'Dashboard')</h4>
                <p>Sistem Informasi Realisasi Retribusi Daerah — BAPENDA Kab. Badung</p>
            </div>
        </div>

        <!-- ALERTS -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')

    </main>

    <!-- MOBILE BOTTOM NAVIGATION BAR -->
    <nav class="mobile-bottom-nav">
        <div class="nav-item">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('upload.index') }}" class="{{ request()->routeIs('upload.*') ? 'active' : '' }}">
                <i class="fas fa-file-upload"></i>
                <span>Upload</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('realisasi.index') }}" class="{{ request()->routeIs('realisasi.*') ? 'active' : '' }}">
                <i class="fas fa-table"></i>
                <span>Realisasi</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('profile.index') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="fas fa-user-gear"></i>
                <span>Profil</span>
            </a>
        </div>
        @if(Auth::check() && Auth::user()->isAdmin())
        <div class="nav-item">
            <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="fas fa-users-gear"></i>
                <span>Users</span>
            </a>
        </div>
        @endif
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
