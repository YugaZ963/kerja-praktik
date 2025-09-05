{{-- resources/views/customer/about.blade.php --}}
@extends('layouts.customer')

@section('content')
    <div class="container mt-4">
        <!-- Navbar -->
        <x-navbar />

        <!-- Hero Section -->
        <div class="bg-gradient-primary text-white p-5 rounded-3 mb-5 text-center position-relative overflow-hidden">
            <div class="position-relative z-index-2">
                <h1 class="display-4 fw-bold mb-3">Tentang Kami</h1>
                <p class="lead mb-0 fs-5">Mengetahui lebih dekat tentang {{ $titleShop }}</p>
            </div>
            <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
                <div class="bg-pattern"></div>
            </div>
        </div>

        <!-- About Content -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="mb-5">
                    <h2 class="h3 mb-4 text-primary fw-bold">Tentang {{ $titleShop }}</h2>
                    <div class="content-text">
                        <p class="mb-4 lh-lg">
                            {{ $titleShop }} adalah usaha kecil menengah yang berfokus pada produksi dan distribusi seragam
                            sekolah berkualitas tinggi. Didirikan pada tahun 2010, kami telah melayani lebih dari 100 sekolah 
                            di wilayah Jakarta dan sekitarnya.
                        </p>
                        <p class="mb-4 lh-lg">
                            Kami berkomitmen untuk menyediakan seragam sekolah yang tidak hanya memenuhi standar kualitas, tetapi
                            juga nyaman digunakan dan terjangkau bagi semua kalangan. Dengan tim produksi yang berpengalaman dan 
                            bahan baku terbaik, kami selalu berusaha memberikan yang terbaik untuk para pelanggan setia kami.
                        </p>
                        <p class="mb-4 lh-lg">
                            Visi kami adalah menjadi pelopor dalam industri seragam sekolah yang mengutamakan kualitas, inovasi, dan
                            pelayanan prima. Kami percaya bahwa dengan dedikasi dan kerja keras, kami dapat memenuhi kebutuhan 
                            seragam sekolah di seluruh Indonesia.
                        </p>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-2">
                    <div class="card-header bg-gradient-primary text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-star-fill me-2"></i>Mengapa memilih kami?</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-lg-4 col-md-6">
                                <div class="feature-item text-center p-3">
                                    <div class="feature-icon mb-3">
                                        <i class="bi bi-award-fill text-primary fs-1"></i>
                                    </div>
                                    <h6 class="fw-bold mb-2">Kualitas Terbaik</h6>
                                    <p class="text-muted mb-0 small">Bahan berkualitas tinggi dan produksi yang teliti</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="feature-item text-center p-3">
                                    <div class="feature-icon mb-3">
                                        <i class="bi bi-palette-fill text-primary fs-1"></i>
                                    </div>
                                    <h6 class="fw-bold mb-2">Desain Modern</h6>
                                    <p class="text-muted mb-0 small">Desain seragam yang up-to-date dan modis</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="feature-item text-center p-3">
                                    <div class="feature-icon mb-3">
                                        <i class="bi bi-currency-dollar text-primary fs-1"></i>
                                    </div>
                                    <h6 class="fw-bold mb-2">Harga Terjangkau</h6>
                                    <p class="text-muted mb-0 small">Harga kompetitif dengan kualitas yang tidak kompromi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-2">
                    <div class="card-header bg-gradient-primary text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-building me-2"></i>Profil Perusahaan</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="company-info">
                                    <div class="info-item d-flex align-items-center mb-3 p-2 rounded bg-light">
                                        <i class="bi bi-calendar-event text-primary me-3 fs-5"></i>
                                        <div>
                                            <strong class="text-dark">Didirikan:</strong>
                                            <span class="ms-2">2000</span>
                                        </div>
                                    </div>
                                    <div class="info-item d-flex align-items-center mb-3 p-2 rounded bg-light">
                                        <i class="bi bi-geo-alt-fill text-primary me-3 fs-5"></i>
                                        <div>
                                            <strong class="text-dark">Alamat:</strong>
                                            <span class="ms-2">Pasar Baru, Bandung, Jawa Barat</span>
                                        </div>
                                    </div>
                                    <div class="info-item d-flex align-items-center mb-3 p-2 rounded bg-light">
                                        <i class="bi bi-envelope-fill text-primary me-3 fs-5"></i>
                                        <div>
                                            <strong class="text-dark">Email:</strong>
                                            <span class="ms-2">ravazka963@gmail.com</span>
                                        </div>
                                    </div>
                                    <div class="info-item d-flex align-items-center mb-0 p-2 rounded bg-light">
                                        <i class="bi bi-telephone-fill text-primary me-3 fs-5"></i>
                                        <div>
                                            <strong class="text-dark">Telepon:</strong>
                                            <span class="ms-2">+62 896-7775-4918</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom CSS for About Page */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
        
        .bg-pattern {
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 2px, transparent 2px);
            background-size: 50px 50px;
        }
        
        .feature-item {
            transition: all 0.3s ease;
            border-radius: 10px;
        }
        
        .feature-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,123,255,0.15);
            background-color: rgba(0,123,255,0.05);
        }
        
        .feature-icon i {
            transition: all 0.3s ease;
        }
        
        .feature-item:hover .feature-icon i {
            transform: scale(1.1);
            color: #0056b3 !important;
        }
        
        .info-item {
            transition: all 0.3s ease;
        }
        
        .info-item:hover {
            background-color: rgba(0,123,255,0.1) !important;
            transform: translateX(5px);
        }
        

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,123,255,0.3);
        }
        
        .card {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
        }
        
        .content-text p {
            text-align: justify;
        }
        
        /* Responsive adjustments */
        @media (max-width: 991px) {
            .hero-section {
                padding: 3rem 2rem !important;
            }
            
            .display-4 {
                font-size: 2.5rem;
            }
            
            .feature-item {
                margin-bottom: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .hero-section {
                padding: 2rem 1rem !important;
            }
            
            .display-4 {
                font-size: 2rem;
            }
            
            .card-body {
                padding: 1.5rem !important;
            }
            
            .feature-item {
                text-align: center;
                padding: 1.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero-section {
                padding: 1.5rem 1rem !important;
            }
            
            .display-4 {
                font-size: 1.75rem;
            }
            
            .card-header {
                padding: 1rem !important;
            }
            
            .card-body {
                padding: 1rem !important;
            }
        }
    </style>
@endsection
