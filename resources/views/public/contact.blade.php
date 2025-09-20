{{-- resources/views/customer/contact.blade.php --}}
@extends('layouts.customer')

@section('content')
    <div class="container mt-4">
        <!-- Navbar -->
        <x-navbar />

        <!-- Hero Section -->
        <div class="bg-light p-5 rounded mb-4 text-center">
            <h1 class="display-5 fw-bold text-primary">Hubungi Kami</h1>
            <p class="lead">Butuh bantuan? Silakan hubungi kami melalui berbagai cara di bawah ini</p>
        </div>

        <!-- Contact Content -->
        <div class="row mb-5">
            <div class="col-md-6">
                <h3 class="mb-4">Informasi Kontak</h3>

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <i class="bi bi-geo-alt-fill text-primary me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-0">Alamat</h6>
                                <p class="mb-0">Pasar Baru, Bandung, Jawa Barat</p>
                            </div>
                        </div>

                        <div class="d-flex mb-3">
                            <i class="bi bi-telephone-fill text-primary me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-0">Telepon / WhatsApp</h6>
                                <p class="mb-0">+62 896-7775-4918</p>
                            </div>
                        </div>

                        <div class="d-flex mb-3">
                            <i class="bi bi-envelope-fill text-primary me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-0">Email</h6>
                                <p class="mb-0">ravazka963@gmail.com</p>
                            </div>
                        </div>

                        <div class="d-flex">
                            <i class="bi bi-clock-fill text-primary me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-0">Jam Operasional</h6>
                                <p class="mb-0">Senin - Jumat: 08.00 - 17.00<br>Sabtu - Minggu: 09.00 - 16.00<br></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Ikuti Kami di Media Sosial</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-center gap-3">
                            <a href="#" class="text-reset fs-3">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="#" class="text-reset fs-3">
                                <i class="bi bi-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <h3 class="mb-4">Form Kontak</h3>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Subjek</label>
                        <input type="text" class="form-control" id="subject" name="subject"
                            value="{{ old('subject') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Pesan</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                </form>
            </div>
        </div>

    </div>
@endsection
