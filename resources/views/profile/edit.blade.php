@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Banner Informasi Pengguna -->
        <div class="card shadow-sm border-0 mb-4 overflow-hidden rounded-3">
            <div class="card-body p-4 text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #0b3d91 0%, #1e40af 100%);">
                <div class="d-flex align-items-center">
                    <div class="bg-white text-primary rounded-circle p-3 me-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-person-gear fs-2"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning text-dark text-uppercase px-2 py-1">{{ $user->role }}</span>
                            <span class="text-light opacity-75 small"><i class="bi bi-building me-1"></i>{{ $user->unit_opd ?? 'BAPENDA / OPD Terkait' }}</span>
                        </div>
                    </div>
                </div>
                <div class="text-end d-none d-md-block">
                    <span class="badge bg-light text-dark px-3 py-2">
                        <i class="bi bi-envelope-fill me-1 text-primary"></i> {{ $user->email }}
                    </span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Form Ganti Password -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-shield-lock-fill text-warning me-2"></i>Ubah Password
                        </h5>
                        <small class="text-muted">Perbarui kata sandi akun Anda secara berkala</small>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Kata Sandi Saat Ini</label>
                                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Masukkan password lama" required>
                                @error('current_password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Kata Sandi Baru</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required>
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary">Ulangi Kata Sandi Baru</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi kata sandi baru" required>
                            </div>

                            <button type="submit" class="btn btn-warning fw-bold text-dark w-100 py-2 shadow-sm">
                                <i class="bi bi-check-circle-fill me-1"></i> Simpan Password Baru
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Form Edit Profil & Instansi -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-person-lines-fill text-primary me-2"></i>Ubah Data Profil
                        </h5>
                        <small class="text-muted">Perbarui nama pengguna dan unit kerja OPD</small>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Nama Lengkap / Jabatan</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Alamat Email (Tetap)</label>
                                <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly disabled>
                                <small class="text-muted" style="font-size: 0.75rem;">Email login dikelola oleh Administrator.</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-secondary">Instansi / Unit OPD</label>
                                <input type="text" name="unit_opd" class="form-control @error('unit_opd') is-invalid @enderror" value="{{ old('unit_opd', $user->unit_opd) }}" placeholder="Contoh: Dinas Perhubungan">
                                @error('unit_opd')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary fw-bold w-100 py-2 shadow-sm" style="background-color: #0b3d91;">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan Profil
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
