@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-success text-white text-center py-4">
                <h3 class="mb-0">
                    <i class="bi bi-person-plus me-2"></i>
                    Register
                </h3>
                <p class="mb-0 mt-2 opacity-75">Buat akun baru</p>
            </div>
            <div class="card-body p-5">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="form-label">
                            <i class="bi bi-person me-1"></i>
                            Nama Lengkap
                        </label>
                        <input id="name" type="text" 
                               class="form-control form-control-lg @error('name') is-invalid @enderror" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autocomplete="name" 
                               autofocus
                               placeholder="Masukkan nama lengkap Anda">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i>
                            Email
                        </label>
                        <input id="email" type="email" 
                               class="form-control form-control-lg @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email"
                               placeholder="Masukkan email Anda">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>



                    <div class="mb-4">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock me-1"></i>
                            Password
                        </label>
                        <input id="password" type="password" 
                               class="form-control form-control-lg @error('password') is-invalid @enderror" 
                               name="password" 
                               required 
                               autocomplete="new-password"
                               placeholder="Masukkan password (min. 6 karakter)">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password-confirm" class="form-label">
                            <i class="bi bi-lock-fill me-1"></i>
                            Konfirmasi Password
                        </label>
                        <input id="password-confirm" type="password" 
                               class="form-control form-control-lg" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               placeholder="Ulangi password Anda">
                    </div>

                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-person-check me-2"></i>
                            Daftar
                        </button>
                    </div>

                    <div class="text-center">
                        <p class="mb-0">
                            Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-decoration-none fw-bold">
                                Login sekarang
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border-bottom: none;
}

.form-control-lg, .form-select-lg {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control-lg:focus, .form-select-lg:focus {
    border-color: #0d9d17;
    box-shadow: 0 0 0 0.2rem rgba(13, 157, 23, 0.25);
}

.btn-lg {
    border-radius: 10px;
    padding: 12px 24px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 157, 23, 0.3);
}
</style>
@endsection