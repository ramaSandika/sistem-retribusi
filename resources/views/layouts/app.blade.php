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
                <i class="bi bi-bank fs-4 me-2 text-warning"></i>
                <div>
                    <div>RETRIBUSI BAPENDA</div>
                    <div style="font-size: 0.65rem; font-weight: normal; opacity: 0.85;">Sistem Monitoring & Realisasi APBD</div>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto ms-lg-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('upload.*') ? 'active' : '' }}" href="{{ route('upload.create') }}">
                            <i class="bi bi-file-earmark-arrow-up me-1"></i> Upload & OCR PDF
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('retribusi.*') ? 'active' : '' }}" href="{{ route('retribusi.index') }}">
                            <i class="bi bi-table me-1"></i> Data Realisasi
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <span class="badge bg-warning text-dark px-3 py-2 fw-semibold">
                            <i class="bi bi-shield-check me-1"></i> Mode Langsung (Fokus OCR AI)
                        </span>
                    </li>
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