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
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 50%, #450a0a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
            max-width: 880px;
            width: 100%;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        .login-banner {
            background: url('{{ asset("bapenda_gedung.jpg") }}') center center / cover no-repeat;
            position: relative;
            min-height: 480px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 36px 30px;
            color: #ffffff;
        }
        .login-banner::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(180deg, rgba(127, 29, 29, 0.45) 0%, rgba(153, 27, 27, 0.88) 75%, rgba(69, 10, 10, 0.96) 100%);
        }
        .banner-content {
            position: relative;
            z-index: 2;
        }
        .btn-red {
            background-color: #991b1b;
            color: #ffffff;
            font-weight: 700;
            padding: 12px;
            border-radius: 12px;
            transition: all 0.2s ease;
            border: none;
        }
        .btn-red:hover {
            background-color: #7f1d1d;
            color: white;
            box-shadow: 0 4px 15px rgba(153, 27, 27, 0.4);
        }
        .badge-instansi {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="row g-0">
            <!-- Kolom Kiri: Foto Kantor BAPENDA Pasedahan Agung -->
            <div class="col-lg-6 col-12 d-none d-lg-flex login-banner">
                <div class="banner-content">
                    <span class="badge-instansi">
                        <i class="fas fa-building-columns me-1"></i> Kantor BAPENDA / Pasedahan Agung
                    </span>
                    <h4 class="fw-bold mb-1">Sistem Realisasi Retribusi Daerah</h4>
                    <p class="small text-white-50 mb-0">
                        Platform pencatatan, verifikasi dokumen laporan, dan rekapitulasi retribusi daerah terpadu bagi seluruh instansi OPD.
                    </p>
                </div>
            </div>

            <!-- Kolom Kanan: Form Login Resmi -->
            <div class="col-lg-6 col-12 p-4 p-md-5 d-flex flex-column justify-content-center">
                <!-- Header Mobile jika di layar kecil -->
                <div class="d-lg-none text-center mb-4 pb-3 border-bottom">
                    <img src="{{ asset('bapenda_gedung.jpg') }}" alt="Kantor BAPENDA" class="img-fluid rounded-4 mb-3 shadow-sm" style="max-height: 160px; width: 100%; object-fit: cover;">
                    <h5 class="fw-bold text-danger mb-0">Sistem Realisasi Retribusi</h5>
                    <small class="text-muted">BAPENDA / Pasedahan Agung</small>
                </div>

                <div class="d-none d-lg-block mb-4">
                    <h4 class="fw-bold text-danger mb-1">Masuk ke Sistem</h4>
                    <p class="text-muted small mb-0">Silakan masukkan identitas akun resmi Anda.</p>
                </div>

                @if(session('info'))
                    <div class="alert alert-info rounded-3 small py-2">
                        <i class="fas fa-info-circle me-1"></i> {{ session('info') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger rounded-3 small py-2">
                        <i class="fas fa-exclamation-triangle me-1"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Email / Username Resmi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control bg-light border-start-0" placeholder="admin@retribusi.go.id" value="{{ old('email') }}" autofocus>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted">Kata Sandi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-red w-100 mb-3 shadow-sm">
                        <i class="fas fa-sign-in-alt me-2"></i> Masuk ke Sistem
                    </button>
                </form>

                <div class="border-top pt-3 text-center">
                    <span class="small text-muted">Belum memiliki akun resmi?</span>
                    <a href="{{ route('register') }}" class="small fw-bold text-danger ms-1 text-decoration-none">Daftar Akun Operator</a>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
