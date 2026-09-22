<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Login - Sistem Realisasi Retribusi BAPENDA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background-color: #1a0808;
            color: #1e293b;
        }

        /* === BACKGROUND FOTO KANTOR BUPATI === */
        .bg-photo {
            position: fixed;
            inset: 0;
            background: url('{{ asset("kantor_bupati.jpg") }}') center center / cover no-repeat;
            z-index: 0;
            filter: brightness(0.90) saturate(1.1);
        }

        /* === OVERLAY ELEGAN: Menjaga kontras tinggi & kehangatan khas Bapenda === */
        .bg-overlay {
            position: fixed;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(127, 29, 29, 0.88) 0%,
                rgba(153, 27, 27, 0.82) 45%,
                rgba(15, 23, 42, 0.90) 100%
            );
            z-index: 1;
        }

        /* === CONTAINER HALAMAN === */
        .page-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 480px;
            padding: 32px 20px;
        }

        /* === HEADER INSTANSI: TEKS BESAR, JELAS, & MUDAH DIBACA SEGALA USIA === */
        .instansi-header {
            text-align: center;
            color: #ffffff;
            margin-bottom: 26px;
        }
        .instansi-header .logo-badge {
            width: 76px;
            height: 76px;
            background: #ffffff;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
            color: #b91c1c;
            font-size: 32px;
        }
        .instansi-header h3 {
            font-size: 1.60rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.35);
        }
        .instansi-header p {
            font-size: 0.92rem;
            color: rgba(255, 255, 255, 0.90);
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        /* === CARD LOGIN: BERSIH, TERANG, RAMAH MATA (ERGONOMIS) === */
        .login-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 40px 36px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .login-card-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .login-card-subtitle {
            font-size: 0.92rem;
            color: #64748b;
            font-weight: 500;
            line-height: 1.4;
            margin-bottom: 24px;
        }

        /* LABEL FORM: BESAR, TEBAL, SANGAT JELAS DIBACA ORANG TUA/SEMUA USIA */
        .form-label-friendly {
            color: #1e293b;
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 8px;
            display: block;
        }

        /* INPUT FIELD: TINGGI 54px, FONT 16px (TIDAK ZOOM OTOMATIS DI HP) */
        .input-group-friendly {
            border: 2px solid #cbd5e1;
            border-radius: 14px;
            overflow: hidden;
            background: #f8fafc;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
        }
        .input-group-friendly:focus-within {
            border-color: #b91c1c;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(185, 28, 28, 0.15);
        }

        .input-group-friendly .input-icon {
            padding: 0 16px;
            color: #64748b;
            font-size: 1.15rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .input-group-friendly .form-control {
            border: none !important;
            background: transparent !important;
            padding: 14px 16px 14px 0;
            font-size: 1.02rem;
            font-weight: 600;
            color: #0f172a;
            box-shadow: none !important;
        }
        .input-group-friendly .form-control::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .btn-show-password {
            background: transparent;
            border: none;
            color: #64748b;
            padding: 0 16px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: color 0.2s;
        }
        .btn-show-password:hover {
            color: #b91c1c;
        }

        /* TOMBOL MASUK: BESAR, WARNA MERAH KHAS BAPENDA, EMPUK DIKLIK */
        .btn-login-friendly {
            background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 1.08rem;
            padding: 15px;
            border-radius: 14px;
            border: none;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 6px 20px rgba(185, 28, 28, 0.35);
            transition: all 0.2s ease;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn-login-friendly:hover {
            background: linear-gradient(135deg, #991b1b 0%, #7f1d1d 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(185, 28, 28, 0.45);
            color: #ffffff;
        }
        .btn-login-friendly:active {
            transform: translateY(0);
        }

        /* BANTUAN TEKS & LINK */
        .divider-friendly {
            height: 1px;
            background: #e2e8f0;
            margin: 24px 0 20px;
        }

        .register-link-friendly {
            text-align: center;
            font-size: 0.95rem;
            color: #475569;
            font-weight: 500;
        }
        .register-link-friendly a {
            color: #b91c1c;
            font-weight: 800;
            text-decoration: none;
            padding: 4px 8px;
            border-radius: 8px;
            transition: background 0.15s;
        }
        .register-link-friendly a:hover {
            background: #fee2e2;
            text-decoration: underline;
        }

        /* ALERT NOTIFIKASI RAMAH BACA */
        .alert-friendly-danger {
            background: #fef2f2;
            border: 1.5px solid #fecaca;
            color: #991b1b;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 0.93rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-friendly-info {
            background: #eff6ff;
            border: 1.5px solid #bfdbfe;
            color: #1e40af;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 0.93rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* MOBILE OPTIMIZATION */
        @media (max-width: 576px) {
            .page-wrapper {
                padding: 16px;
            }
            .login-card {
                padding: 30px 22px;
                border-radius: 20px;
            }
            .instansi-header h3 {
                font-size: 1.35rem;
            }
            .instansi-header .logo-badge {
                width: 64px;
                height: 64px;
                font-size: 26px;
            }
        }
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

        <!-- Card Login Bersih & Ramah Mata -->
        <div class="login-card">

            @if(session('info'))
                <div class="alert-friendly-info">
                    <i class="fas fa-info-circle fa-lg"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-friendly-danger">
                    <i class="fas fa-circle-exclamation fa-lg"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <div>
                <h4 class="login-card-title">Masuk ke Akun Anda</h4>
                <p class="login-card-subtitle">Silakan ketik email resmi dan kata sandi untuk mengelola data retribusi.</p>
            </div>

            <form action="/login" method="POST">
                @csrf
                <!-- Field Email -->
                <div class="mb-3">
                    <label class="form-label-friendly" for="input-email">
                        <i class="fas fa-envelope me-1 text-danger"></i> Alamat Email Resmi
                    </label>
                    <div class="input-group-friendly">
                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input type="email" id="input-email" name="email"
                               class="form-control"
                               placeholder="Contoh: admin@retribusi.go.id"
                               value="{{ old('email') }}"
                               required autofocus>
                    </div>
                </div>

                <!-- Field Password -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label-friendly mb-0" for="input-password">
                            <i class="fas fa-lock me-1 text-danger"></i> Kata Sandi
                        </label>
                    </div>
                    <div class="input-group-friendly">
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" id="input-password" name="password"
                               class="form-control"
                               placeholder="Masukkan kata sandi Anda"
                               required>
                        <button type="button" class="btn-show-password" onclick="togglePasswordVisibility()" title="Lihat/Sembunyikan Kata Sandi">
                            <i class="fas fa-eye" id="eye-icon"></i>
                        </button>
                    </div>
                </div>

                <!-- Tombol Submit Besar & Jelas -->
                <button type="submit" class="btn-login-friendly">
                    <i class="fas fa-arrow-right-to-bracket fa-lg"></i>
                    <span>Masuk ke Sistem</span>
                </button>
            </form>

            <div class="divider-friendly"></div>

            <div class="register-link-friendly">
                Belum memiliki akun resmi?
                <a href="{{ route('register') }}">Daftar Akun Baru</a>
            </div>
        </div>

        <!-- Script Toggle Lihat Sandi -->
        <script>
            function togglePasswordVisibility() {
                const passwordInput = document.getElementById('input-password');
                const eyeIcon = document.getElementById('eye-icon');
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                }
            }
        </script>

        <!-- Footer kecil -->
        <p class="text-center mt-4" style="color:rgba(255,255,255,0.35); font-size:0.75rem;">
            &copy; {{ date('Y') }} BAPENDA Kabupaten Badung. Hak cipta dilindungi.
        </p>
    </div>

</body>
</html>
