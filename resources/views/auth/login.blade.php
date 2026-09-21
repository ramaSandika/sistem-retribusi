<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Realisasi Retribusi BAPENDA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* === BACKGROUND FOTO FULL SCREEN === */
        .bg-photo {
            position: fixed;
            inset: 0;
            background: url('{{ asset("kantor_bupati.jpg") }}') center center / cover no-repeat;
            z-index: 0;
        }

        /* === OVERLAY GELAP DI ATAS FOTO === */
        .bg-overlay {
            position: fixed;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(15, 10, 10, 0.55) 0%,
                rgba(30, 15, 15, 0.45) 50%,
                rgba(10, 5, 5, 0.62) 100%
            );
            z-index: 1;
        }

        /* === JUDUL INSTANSI DI ATAS CARD === */
        .page-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 460px;
            padding: 24px 16px;
        }

        .instansi-header {
            text-align: center;
            color: #ffffff;
            margin-bottom: 24px;
        }
        .instansi-header .logo-badge {
            width: 64px;
            height: 64px;
            background: rgba(255,255,255,0.15);
            border: 2px solid rgba(255,255,255,0.35);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px;
            backdrop-filter: blur(6px);
        }
        .instansi-header h3 {
            font-size: 1.45rem;
            font-weight: 800;
            text-shadow: 0 2px 12px rgba(0,0,0,0.5);
            letter-spacing: 0.3px;
        }
        .instansi-header p {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.70);
            font-weight: 500;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        /* === GLASS CARD LOGIN === */
        .glass-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 24px;
            padding: 36px 32px;
            box-shadow:
                0 8px 40px rgba(0, 0, 0, 0.35),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .glass-card .form-label {
            color: rgba(255,255,255,0.85);
            font-size: 0.82rem;
            font-weight: 600;
        }

        .glass-card .input-group-text {
            background: rgba(255,255,255,0.10);
            border: 1px solid rgba(255,255,255,0.20);
            border-right: none;
            color: rgba(255,255,255,0.70);
        }

        .glass-card .form-control {
            background: rgba(255,255,255,0.10);
            border: 1px solid rgba(255,255,255,0.20);
            border-left: none;
            color: #ffffff;
            font-weight: 500;
        }
        .glass-card .form-control::placeholder {
            color: rgba(255,255,255,0.40);
        }
        .glass-card .form-control:focus {
            background: rgba(255,255,255,0.18);
            border-color: rgba(255,255,255,0.45);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.30);
            color: #ffffff;
            outline: none;
        }

        .btn-login {
            background: linear-gradient(135deg, #dc2626, #991b1b);
            color: #ffffff;
            font-weight: 700;
            padding: 13px;
            border-radius: 14px;
            border: none;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            box-shadow: 0 4px 20px rgba(220, 38, 38, 0.45);
            letter-spacing: 0.3px;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #b91c1c, #7f1d1d);
            box-shadow: 0 6px 28px rgba(220, 38, 38, 0.60);
            transform: translateY(-1px);
            color: #fff;
        }

        .divider-line {
            border-top: 1px solid rgba(255,255,255,0.18);
            margin: 20px 0 16px;
        }

        .register-link {
            text-align: center;
            color: rgba(255,255,255,0.65);
            font-size: 0.83rem;
        }
        .register-link a {
            color: #fca5a5;
            font-weight: 700;
            text-decoration: none;
        }
        .register-link a:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        .alert-glass {
            background: rgba(220,38,38,0.25);
            border: 1px solid rgba(220,38,38,0.45);
            color: #fecaca;
            border-radius: 12px;
            font-size: 0.83rem;
            padding: 10px 14px;
            margin-bottom: 18px;
        }
        .alert-glass-info {
            background: rgba(59,130,246,0.2);
            border: 1px solid rgba(59,130,246,0.35);
            color: #bfdbfe;
            border-radius: 12px;
            font-size: 0.83rem;
            padding: 10px 14px;
            margin-bottom: 18px;
        }

        /* ===== MOBILE RESPONSIVE ===== */
        @media (max-width: 576px) {
            body { padding: 0; align-items: flex-end; }
            .page-wrapper {
                max-width: 100%;
                padding: 16px 16px 24px;
            }
            .glass-card {
                padding: 28px 22px;
                border-radius: 28px 28px 0 0;
                border-bottom: none;
            }
            .instansi-header h3 { font-size: 1.15rem; }
            .instansi-header { margin-bottom: 16px; }
            .btn-login { font-size: 1rem; padding: 14px; }
        }
        @media (min-width: 577px) and (max-width: 768px) {
            .page-wrapper { max-width: 480px; }
        }

        /* Dekorasi blur bubbles latar belakang */
        .deco-circle {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.07;
            z-index: 2;
            pointer-events: none;
        }
        .deco-1 { width: 380px; height: 380px; background: #dc2626; top: -100px; right: -80px; }
        .deco-2 { width: 300px; height: 300px; background: #991b1b; bottom: -80px; left: -60px; }
    </style>
</head>
<body>

    <!-- Background foto kantor -->
    <div class="bg-photo"></div>
    <div class="bg-overlay"></div>

    <!-- Dekorasi lingkaran blur -->
    <div class="deco-circle deco-1"></div>
    <div class="deco-circle deco-2"></div>

    <!-- Konten Login -->
    <div class="page-wrapper">
        <!-- Header Instansi -->
        <div class="instansi-header">
            <div class="logo-badge">
                <i class="fas fa-building-columns fa-lg text-white"></i>
            </div>
            <h3>Badan Pendapatan Daerah</h3>
            <p>Kabupaten Badung</p>
        </div>

        <!-- Glass Card Login -->
        <div class="glass-card">

            @if(session('info'))
                <div class="alert-glass-info">
                    <i class="fas fa-info-circle me-1"></i> {{ session('info') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-glass">
                    <i class="fas fa-exclamation-triangle me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <div class="mb-4">
                <h5 class="fw-bold text-white mb-1">Masuk ke Sistem</h5>
                <small style="color:rgba(255,255,255,0.55);">Silakan masukkan identitas akun resmi Anda</small>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email / Username Resmi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email"
                               class="form-control"
                               placeholder="admin@retribusi.go.id"
                               value="{{ old('email') }}"
                               autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password"
                               class="form-control"
                               placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="btn btn-login w-100">
                    <i class="fas fa-sign-in-alt me-2"></i> Masuk ke Sistem
                </button>
            </form>

            <div class="divider-line"></div>

            <div class="register-link">
                Belum memiliki akun resmi?
                <a href="{{ route('register') }}">Daftar Akun Operator</a>
            </div>
        </div>

        <!-- Footer kecil -->
        <p class="text-center mt-4" style="color:rgba(255,255,255,0.35); font-size:0.75rem;">
            &copy; {{ date('Y') }} BAPENDA Kabupaten Badung. Hak cipta dilindungi.
        </p>
    </div>

</body>
</html>
