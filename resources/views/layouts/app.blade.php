<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Realisasi Retribusi - BAPENDA</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-navy: #0b3d91;
            --secondary-navy: #082b66;
        }
        .bg-navy {
            background-color: var(--primary-navy) !important;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .nav-link.active {
            font-weight: 600;
            border-bottom: 2px solid #ffc107;
        }
    </style>
</head>
<body class="bg-light min-vh-100 d-flex flex-column">
    <nav class="navbar navbar-expand-lg navbar-dark bg-navy shadow-sm">
        <div class="container-fluid px-lg-4">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/logo-kabupaten.png') }}" alt="Logo Kabupaten" class="me-3 bg-white p-1 rounded shadow-sm" style="height: 48px; object-fit: contain;">
                <div>
                    <div class="fw-bold fs-5 tracking-wide" style="line-height: 1.1;">PEMERINTAH KABUPATEN</div>
                    <div class="text-warning small fw-semibold" style="letter-spacing: 0.5px;">BADAN PENDAPATAN DAERAH (BAPENDA)</div>
                    <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.9;">Sistem Pengawasan & Realisasi Retribusi Daerah</div>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto ms-lg-4 fs-6">
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 {{ request()->routeIs('upload.*') ? 'active' : '' }}" href="{{ route('upload.create') }}">
                            <i class="bi bi-file-earmark-arrow-up-fill me-1 text-info"></i> Upload & OCR PDF
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-1 text-warning"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-2 {{ request()->routeIs('retribusi.*') || request()->routeIs('realisasi.*') ? 'active' : '' }}" href="{{ route('retribusi.index') }}">
                            <i class="bi bi-table me-1 text-light"></i> Data Realisasi
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <!-- Dropdown Menu Administrator -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle btn btn-sm btn-outline-warning text-warning px-3 py-2 fw-semibold border-warning-subtle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-shield-lock-fill me-1"></i> Panel Admin
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                    <li>
                                        <a class="dropdown-item py-2 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                            <i class="bi bi-people-fill me-2 text-primary"></i> Kelola Pengguna (Admin & OPD)
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 {{ request()->routeIs('admin.login_histories.*') ? 'active' : '' }}" href="{{ route('admin.login_histories.index') }}">
                                            <i class="bi bi-clock-history me-2 text-success"></i> Riwayat Login (Audit Trail)
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        <!-- Akun Pengguna & Ubah Password -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center text-white bg-white bg-opacity-10 px-3 py-2 rounded-pill border border-light border-opacity-25" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle fs-5 me-2 text-warning"></i>
                                <div class="text-start me-1">
                                    <div class="fw-bold small" style="line-height: 1;">{{ auth()->user()->name }}</div>
                                    <span class="badge bg-warning text-dark text-uppercase" style="font-size: 0.65rem;">{{ auth()->user()->role }}</span>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="min-width: 230px;">
                                <li class="px-3 py-2 border-bottom">
                                    <div class="small text-muted">Masuk sebagai:</div>
                                    <div class="fw-bold">{{ auth()->user()->email }}</div>
                                    <div class="small text-secondary">{{ auth()->user()->unit_opd ?? 'BAPENDA' }}</div>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-key-fill me-2 text-warning"></i> Ubah Password & Profil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger py-2 fw-semibold">
                                            <i class="bi bi-box-arrow-right me-2"></i> Keluar (Logout)
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-warning fw-bold px-4 py-2 shadow-sm text-dark">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk (Login)
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container-fluid px-lg-4 my-4 flex-grow-1">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-white border-top py-3 text-center text-muted small mt-auto">
        &copy; {{ date('Y') }} Badan Pendapatan Daerah (BAPENDA) &bull; Sistem Monitoring Realisasi Retribusi Daerah
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>