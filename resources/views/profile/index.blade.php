@extends('layouts.app')

@section('title', 'Profil Saya & Keamanan')
@section('page_heading', 'Pengaturan Akun & Keamanan')

@section('content')
<div class="row g-4">
    <!-- User Information Card -->
    <div class="col-lg-5 col-12">
        <div class="card-custom p-4 text-center">
            <div class="mx-auto mb-3 bg-white rounded-circle text-danger d-flex align-items-center justify-content-center fw-bold shadow"
                 style="width:72px; height:72px; font-size:28px;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h5 class="fw-bold mb-1" style="color:#fff;">{{ $user->name }}</h5>
            <span class="badge rounded-pill px-3 py-1 mb-3"
                  style="background:rgba(220,38,38,0.20);border:1px solid rgba(220,38,38,0.40);color:#fca5a5;font-size:0.75rem;">
                <i class="fas fa-shield-halved me-1"></i>{{ $user->isAdmin() ? 'ADMINISTRATOR BAPENDA' : 'OPERATOR OPD' }}
            </span>

            <div class="text-start mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,0.10);">
                <div class="mb-3">
                    <small style="color:rgba(255,255,255,0.45);font-size:0.75rem;display:block;">Email Resmi</small>
                    <span class="fw-semibold" style="color:#fff;font-size:0.90rem;">{{ $user->email }}</span>
                </div>
                <div class="mb-3">
                    <small style="color:rgba(255,255,255,0.45);font-size:0.75rem;display:block;">Instansi / Unit OPD</small>
                    <span class="fw-semibold" style="color:#fff;font-size:0.90rem;">{{ $user->opd_name }}</span>
                </div>
                <div>
                    <small style="color:rgba(255,255,255,0.45);font-size:0.75rem;display:block;">Terdaftar Sejak</small>
                    <span class="fw-semibold" style="color:#fff;font-size:0.90rem;">{{ $user->created_at ? $user->created_at->format('d F Y') : '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Ganti Password -->
    <div class="col-lg-7 col-12">
        <div class="card-custom p-4 position-relative" style="z-index: 10;">
            <h6 class="fw-bold mb-3" style="color:#fde68a;">
                <i class="fas fa-key me-2"></i>Ubah Kata Sandi Akun
            </h6>
            <p class="small mb-4" style="color:rgba(255,255,255,0.55);">
                Pastikan Anda menggunakan kata sandi yang kuat (minimal 6 karakter) untuk menjaga keamanan akun.
            </p>

            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="current_password" class="form-label" style="cursor:pointer;">Kata Sandi Saat Ini</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock text-white-50"></i></span>
                        <input type="password" id="current_password" name="current_password"
                               class="form-control rounded-end" placeholder="Masukkan kata sandi saat ini"
                               required style="pointer-events: auto; cursor: text;">
                    </div>
                    @error('current_password')
                        <small class="text-danger mt-1 d-block" style="color:#fca5a5 !important;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label" style="cursor:pointer;">Kata Sandi Baru</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-key text-white-50"></i></span>
                        <input type="password" id="new_password" name="new_password"
                               class="form-control rounded-end" placeholder="Minimal 6 karakter"
                               required minlength="6" style="pointer-events: auto; cursor: text;">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="new_password_confirmation" class="form-label" style="cursor:pointer;">Konfirmasi Kata Sandi Baru</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-check-double text-white-50"></i></span>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                               class="form-control rounded-end" placeholder="Ulangi kata sandi baru"
                               required minlength="6" style="pointer-events: auto; cursor: text;">
                    </div>
                </div>

                <button type="submit" class="btn btn-red fw-bold w-100 py-2 shadow-sm"
                        style="pointer-events: auto; cursor: pointer;">
                    <i class="fas fa-save me-2"></i> Simpan Kata Sandi Baru
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
