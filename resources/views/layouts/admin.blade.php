<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | OG Catalogue</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/og-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/og-logo.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Inter:wght@300;400;500;600;700&family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --admin-black: #111111;
            --admin-dark: #111111;
            --admin-white: #ffffff;
            --admin-bg: #fbfbfb;
            --admin-border: 1px solid #e9ecef;
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-mono: 'Courier Prime', monospace;
        }

        body {
            background-color: var(--admin-bg);
            color: var(--admin-dark);
            font-family: var(--font-sans);
            font-size: 0.9rem;
            letter-spacing: -0.01em;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-sans) !important;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* Sidebar Styling */
        .sidebar {
            background-color: #111111;
            min-height: 100vh;
            color: var(--admin-white);
            border-right: 1px solid #222222;
        }

        .sidebar .brand-text {
            font-family: var(--font-sans);
            font-size: 1.1rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--admin-white);
            text-decoration: none;
            padding: 1.8rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid #222;
        }

        .sidebar-menu {
            padding: 1.2rem 0.8rem;
            list-style: none;
            margin: 0;
        }

        .sidebar-item a {
            color: #a0a0a0;
            text-decoration: none;
            padding: 0.75rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            margin-bottom: 0.3rem;
        }

        .sidebar-item a:hover, .sidebar-item.active a {
            color: var(--admin-white);
            background-color: #222222;
        }

        /* Content Area */
        .main-content {
            padding: 2.5rem 3rem;
        }

        .admin-navbar {
            background-color: var(--admin-white);
            border-bottom: var(--admin-border);
            padding: 1rem 3rem;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1.25rem 1rem !important;
            }
            .admin-navbar {
                padding: 0.8rem 1rem !important;
            }
        }

        /* Cards */
        .card-stat {
            background-color: var(--admin-white);
            border: var(--admin-border);
            border-radius: 0.5rem;
            padding: 1.5rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.04);
        }

        .card-stat .stat-val {
            font-size: 2.2rem;
            font-weight: 800;
            font-family: var(--font-sans);
            letter-spacing: -0.02em;
            line-height: 1.2;
            margin-top: 0.5rem;
        }

        .card-admin {
            background-color: var(--admin-white);
            border: var(--admin-border);
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .card-admin .card-header {
            background-color: var(--admin-white);
            border-bottom: var(--admin-border);
            font-family: var(--font-sans);
            font-size: 1rem;
            font-weight: 700;
            padding: 1.2rem 1.5rem;
        }

        .card-admin .card-body {
            padding: 1.5rem;
        }

        /* Buttons (Consistent with user catalog buttons) */
        .btn-black, .btn-primary-admin {
            background-color: #111111;
            color: #ffffff;
            border: 1px solid #111111;
            border-radius: 0.5rem !important; /* rounded-3 */
            padding: 0.55rem 1.2rem;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-black:hover, .btn-primary-admin:hover {
            background-color: #2b2b2b;
            color: #ffffff;
            border-color: #2b2b2b;
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .btn-outline-black, .btn-outline-admin {
            background-color: #ffffff;
            color: #111111;
            border: 1px solid #111111;
            border-radius: 0.5rem !important; /* rounded-3 */
            padding: 0.55rem 1.2rem;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-outline-black:hover, .btn-outline-admin:hover {
            background-color: #111111;
            color: #ffffff;
            border-color: #111111;
        }

        /* Form Controls */
        .form-control, .form-select {
            border-radius: 0.5rem !important;
            border: 1px solid #dee2e6;
            padding: 0.6rem 0.9rem;
            font-size: 0.9rem;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(17, 17, 17, 0.08);
            border-color: #111111;
        }

        .input-group .form-control {
            border-top-right-radius: 0.5rem !important;
            border-bottom-right-radius: 0.5rem !important;
        }

        .input-group .input-group-text {
            border-top-left-radius: 0.5rem !important;
            border-bottom-left-radius: 0.5rem !important;
            border-color: #dee2e6;
        }

        /* Badges */
        .badge-admin {
            border-radius: 20px;
            padding: 0.35em 0.8em;
            font-weight: 600;
            font-size: 0.72rem;
            letter-spacing: 0.02em;
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-lg-2 d-none d-lg-block sidebar">
                <a href="{{ route('admin.dashboard') }}" class="brand-text">
                    <div style="background: #ffffff; border-radius: 6px; padding: 3px 6px; display: inline-flex; align-items: center; justify-content: center;">
                        <img src="{{ asset('images/og-logo-footer.png') }}" alt="OG Logo" style="height: 24px; width: auto; object-fit: contain;">
                    </div>
                    <span>OG CATALOGUE</span>
                </a>
                <ul class="sidebar-menu">
                    <li class="sidebar-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item {{ Route::is('products.*') ? 'active' : '' }}">
                        <a href="{{ route('products.index') }}">
                            <i class="bi bi-box-seam"></i> Produk CRUD
                        </a>
                    </li>
                    <li class="sidebar-item border-top border-secondary mt-3">
                        <a href="{{ route('home') }}" target="_blank">
                            <i class="bi bi-globe"></i> Halaman Depan
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-left"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>

            <!-- Content Area -->
            <div class="col-lg-10 col-12">
                <!-- Top Navbar -->
                <nav class="admin-navbar d-flex justify-content-between align-items-center">
                    <div class="d-lg-none">
                        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-dark fw-bold text-decoration-none">
                            <img src="{{ asset('images/og-logo.png') }}" alt="OG Logo" style="height: 28px; width: auto; object-fit: contain;">
                            <span style="font-size: 0.95rem; letter-spacing: 0.08em;">OG CATALOGUE</span>
                        </a>
                    </div>
                    <div class="d-none d-lg-block">
                        <span class="text-secondary">Dashboard Pengelolaan Katalog</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="fw-medium text-dark"><i class="bi bi-person-circle"></i> {{ Auth::user()->name }}</span>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-top').submit();" class="text-secondary text-decoration-none d-lg-none">
                            <i class="bi bi-box-arrow-left fs-5"></i>
                        </a>
                        <form id="logout-form-top" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </nav>

                <!-- Mobile Navigation Menu -->
                <nav class="navbar navbar-expand-lg d-lg-none bg-black navbar-dark py-2">
                    <div class="container-fluid">
                        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#adminMobileNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="adminMobileNav">
                            <ul class="navbar-nav py-2">
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::is('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Produk CRUD</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('home') }}" target="_blank">Lihat Website</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mob').submit();">Logout</a>
                                    <form id="logout-form-mob" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="main-content">
                    <!-- Session Flash Messages -->
                    @if (session('success'))
                        <div class="alert alert-success border-0 bg-white border-start border-success border-4 rounded-0 shadow-sm mb-4 py-3 px-4 d-flex justify-content-between align-items-center" role="alert">
                            <div>
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger border-0 bg-white border-start border-danger border-4 rounded-0 shadow-sm mb-4 py-3 px-4 d-flex justify-content-between align-items-center" role="alert">
                            <div>
                                <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                {{ session('error') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
