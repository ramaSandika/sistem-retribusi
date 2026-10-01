@extends('layouts.app')

@section('title', 'Login Admin')

@section('content')
    <div class="container py-5 my-md-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="border border-light-subtle bg-white rounded-3 p-4 p-md-5 shadow-sm">
                    <div class="text-center mb-4">
                        <span class="text-uppercase font-monospace text-secondary fs-8" style="letter-spacing: 0.15em;">Admin Portal</span>
                        <h2 class="fw-bold mt-2">Login Kelola</h2>
                        <div class="vintage-divider"></div>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 bg-white border-start border-danger border-4 rounded-3 shadow-sm py-2 px-3 mb-4" role="alert">
                            <ul class="list-unstyled mb-0 font-monospace" style="font-size: 0.75rem;">
                                @foreach ($errors->all() as $error)
                                    <li><i class="bi bi-exclamation-circle-fill text-danger me-1"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        
                        <!-- Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label font-monospace text-secondary text-uppercase" style="font-size: 0.75rem;">Username</label>
                            <input type="text" name="username" id="username" class="form-control rounded-3" required autofocus value="{{ old('username') }}" placeholder="Masukkan username">
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label font-monospace text-secondary text-uppercase mb-0" style="font-size: 0.75rem;">Kata Sandi</label>
                            </div>
                            <input type="password" name="password" id="password" class="form-control rounded-3" required placeholder="••••••••">
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input border-dark rounded-1" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label font-monospace text-secondary" for="remember" style="font-size: 0.75rem; cursor: pointer;">
                                    Ingat Saya
                                </label>
                            </div>
                            <a href="https://wa.me/6281775013485?text=Halo%20Owner,%20saya%20lupa%20password%20admin%20OG%20Catalogue" target="_blank" class="font-monospace text-decoration-none text-muted" style="font-size: 0.72rem;">
                                Lupa sandi?
                            </a>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid mb-2">
                            <button type="submit" class="btn btn-vintage py-3 fw-bold rounded-3">Masuk Panel</button>
                        </div>
                    </form>

                    <div class="text-center mt-4 border-top border-light-subtle pt-3">
                        <a href="{{ route('home') }}" class="text-secondary font-monospace text-decoration-none" style="font-size: 0.75rem;"><i class="bi bi-arrow-left"></i> Kembali ke Katalog Utama</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
