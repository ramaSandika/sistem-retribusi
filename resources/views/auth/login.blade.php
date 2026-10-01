@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <!-- Kartu Login dengan Logo Kabupaten di Atas -->
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <!-- Header Kartu Berlogo Kabupaten -->
                <div class="text-center p-4 text-white" style="background: linear-gradient(135deg, #0b3d91 0%, #1e40af 100%);">
                    <div class="bg-white p-2 rounded-4 d-inline-block shadow-sm mb-3">
                        <img src="{{ asset('images/logo-kabupaten.png') }}" alt="Logo Kabupaten" style="height: 80px; object-fit: contain;">
                    </div>
                    <h5 class="fw-bold mb-1 text-uppercase tracking-wide">Pemerintah Kabupaten</h5>
                    <div class="text-warning fw-semibold fs-6">Badan Pendapatan Daerah (BAPENDA)</div>
                    <p class="small text-light text-opacity-75 mb-0 mt-1">Sistem Pengawasan & Realisasi Retribusi Daerah</p>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-dark mb-1">Masuk ke Sistem</h4>
                        <p class="text-muted small">Silakan masukkan email dan kata sandi kedinasan Anda</p>
                    </div>

                    @if(session('info'))
                        <div class="alert alert-info py-2 px-3 small border-0 shadow-sm mb-3">
                            <i class="bi bi-info-circle-fill me-1"></i> {{ session('info') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf

                        <!-- Input Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold text-secondary small">
                                <i class="bi bi-envelope-fill me-1 text-primary"></i> Alamat Email
                            </label>
                            <input id="email" type="email" 
                                   class="form-control form-control-lg fs-6 @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" 
                                   placeholder="nama@kabupaten.go.id / admin@gmail.com" 
                                   required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Input Kata Sandi -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label fw-bold text-secondary small mb-0">
                                    <i class="bi bi-lock-fill me-1 text-primary"></i> Kata Sandi (Password)
                                </label>
                            </div>
                            <input id="password" type="password" 
                                   class="form-control form-control-lg fs-6 @error('password') is-invalid @enderror" 
                                   name="password" 
                                   placeholder="Masukkan kata sandi akun"
                                   required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-4 d-flex align-items-center justify-content-between">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small text-muted user-select-none" for="remember">
                                    Ingat sesi saya di perangkat ini
                                </label>
                            </div>
                        </div>

                        <!-- Tombol Submit Besar & Kontras untuk Semua Kalangan -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg fw-bold text-dark shadow-sm py-2" style="font-size: 1.05rem;">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Masuk Sekarang
                            </button>
                        </div>
                    </form>

                    <!-- Informasi Akun Default untuk Kemudahan Pemakai -->
                    <div class="mt-4 pt-3 border-top text-center">
                        <div class="badge bg-light text-secondary border px-3 py-2 text-wrap" style="font-weight: normal; line-height: 1.5;">
                            <i class="bi bi-shield-check text-success me-1"></i>
                            <strong>Akun Demo Terdaftar:</strong><br>
                            Admin: <code>admin@gmail.com</code> &bull; Password: <code>password</code><br>
                            User OPD: <code>user@gmail.com</code> &bull; Password: <code>password</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
