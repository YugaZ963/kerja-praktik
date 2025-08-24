@extends('layouts.customer')

@section('content')
    <div class="container-fluid px-0">
        <!-- Navbar -->
        <x-navbar />
    </div>
    
    <div class="container mt-4">
        <!-- Hero Section -->
        <div class="hero-section bg-gradient-primary text-white rounded-3 mb-5 shadow-lg overflow-hidden">
            <div class="row align-items-center g-0 min-vh-50">
                <div class="col-lg-6">
                    <div class="hero-content p-5">
                        <h1 class="display-4 fw-bold mb-4">Seragam Sekolah Berkualitas</h1>
                        <p class="lead mb-4 opacity-90 pe-3">Pilih koleksi seragam sekolah terlengkap dengan kualitas terbaik dan harga kompetitif untuk kebutuhan pendidikan Anda</p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="/products" class="btn btn-light btn-lg px-4 py-3 fw-semibold shadow-sm">
                                <i class="bi bi-shop me-2"></i>Lihat Koleksi
                            </a>
                            <a href="#categories" class="btn btn-outline-light btn-lg px-4 py-3">
                                <i class="bi bi-arrow-down me-2"></i>Jelajahi
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image d-flex align-items-center justify-content-center p-4" style="min-height: 450px;">
                        <div class="logo-container position-relative">
                            <img src="{{ asset('images/ravazka.jpg') }}" alt="RAVAZKA Logo" class="img-fluid rounded-3 shadow-lg" style="max-width: 350px; max-height: 350px; object-fit: contain; transform: scale(1.05);">
                            <div class="logo-glow position-absolute top-50 start-50 translate-middle" style="width: 380px; height: 380px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%; z-index: -1;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Categories Section -->
        <section id="categories" class="py-5">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="section-header mb-4">
                        <h2 class="h3 fw-bold text-dark mb-2">
                            <i class="bi bi-grid-3x3-gap text-primary me-2"></i>
                            Kategori Populer
                        </h2>
                        <p class="text-muted mb-0">Pilih seragam sekolah sesuai jenjang pendidikan</p>
                    </div>
                    
                    <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="category-card card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="category-icon mb-3">
                                <i class="bi bi-mortarboard display-4 text-white"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-2">Seragam SMA</h5>
                            <p class="text-muted mb-3">SMA/SMK/SMAK dan sejenisnya</p>
                            <a href="{{ route('customer.products', ['search' => 'SMA']) }}" class="btn btn-outline-primary btn-sm px-4">
                                <i class="bi bi-arrow-right me-1"></i>Lihat Produk
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="category-card card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="category-icon mb-3">
                                <i class="bi bi-people display-4 text-white"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-2">Seragam SMP</h5>
                            <p class="text-muted mb-3">SMP/MTs dan sejenisnya</p>
                            <a href="{{ route('customer.products', ['search' => 'SMP']) }}" class="btn btn-outline-primary btn-sm px-4">
                                <i class="bi bi-arrow-right me-1"></i>Lihat Produk
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="category-card card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="category-icon mb-3">
                                <i class="bi bi-patch-check display-4 text-white"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-2">Seragam SD</h5>
                            <p class="text-muted mb-3">SD/MI dan sejenisnya</p>
                            <a href="{{ route('customer.products', ['search' => 'SD']) }}" class="btn btn-outline-primary btn-sm px-4">
                                <i class="bi bi-arrow-right me-1"></i>Lihat Produk
                            </a>
                        </div>
                    </div>
                </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="col-lg-4">
                    <div class="section-header mb-4">
                        <h2 class="h3 fw-bold text-dark mb-2">
                            <i class="bi bi-clock-history text-primary me-2"></i>
                            Pesanan Terbaru
                        </h2>
                        <p class="text-muted mb-0">Aktivitas pesanan terkini</p>
                    </div>
                    
                    <div class="recent-orders-card card border-0 shadow-sm h-100 d-flex flex-column">
                        <div class="card-header bg-light border-0 py-3">
                            <h6 class="mb-0 fw-semibold text-dark">
                                <i class="bi bi-list-ul me-2"></i>Daftar Pesanan
                            </h6>
                        </div>
                        <div class="card-body p-0 flex-grow-1 d-flex flex-column">
                            @if($recentOrders && $recentOrders->count() > 0)
                                <div class="flex-grow-1">
                                    @foreach($recentOrders as $index => $order)
                                        <div class="order-item p-3 {{ $index < $recentOrders->count() - 1 ? 'border-bottom' : '' }}">
                                            <div class="d-flex align-items-start">
                                                <!-- Product Images -->
                                                <div class="order-images me-3">
                                                    <div class="d-flex flex-wrap" style="max-width: 80px;">
                                                        @foreach($order->items->take(2) as $item)
                                                            @if($item->product && $item->product->image)
                                                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                                     alt="{{ $item->product_name }}" 
                                                                     class="rounded-2 border me-1 mb-1 shadow-sm" 
                                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                            @else
                                                                <div class="bg-light rounded-2 border me-1 mb-1 d-flex align-items-center justify-content-center" 
                                                                     style="width: 40px; height: 40px;">
                                                                    <i class="bi bi-image text-muted" style="font-size: 14px;"></i>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        @if($order->items->count() > 2)
                                                            <div class="bg-primary rounded-2 border d-flex align-items-center justify-content-center text-white fw-bold" 
                                                                 style="width: 40px; height: 40px; font-size: 11px;">
                                                                +{{ $order->items->count() - 2 }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Order Info -->
                                                <div class="order-info flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                                        <small class="text-muted fw-medium">
                                                            <i class="bi bi-calendar3 me-1"></i>{{ $order->created_at->format('d M Y') }}
                                                        </small>
                                                        <span class="badge rounded-pill 
                                                            @if($order->status == 'completed') bg-success
                                                            @elseif($order->status == 'delivered') bg-info
                                                            @elseif($order->status == 'cancelled') bg-danger
                                                            @elseif($order->status == 'shipped') bg-info
                                                            @elseif($order->status == 'processing' || $order->status == 'packaged') bg-warning
                                                            @else bg-secondary
                                                            @endif
                                                        ">{{ $order->getStatusLabel() }}</span>
                                                    </div>
                                                    <h6 class="mb-1 fw-semibold text-dark">{{ $order->user ? $order->user->name : $order->customer_name }}</h6>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">
                                                            <i class="bi bi-box me-1"></i>{{ $order->items->count() }} item
                                                        </small>
                                                        <span class="fw-bold text-success">
                                                            <i class="bi bi-currency-dollar me-1"></i>Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="d-flex align-items-center justify-content-center flex-grow-1" style="min-height: 300px;">
                                    <div class="empty-state text-center">
                                        <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                        <h6 class="text-muted mb-2">Belum ada pesanan</h6>
                                        <p class="text-muted small mb-0">Pesanan akan muncul di sini setelah ada aktivitas</p>
                                    </div>
                                </div>
                            @endif
                    </div>
                </div>
            </div>
        </div>



    </div>

    <style>
        .hero-section {
            background: linear-gradient(135deg, #0d6efd 0%, #cb2368 100%);
            min-height: 500px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
            opacity: 0.3;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .min-vh-50 {
            min-height: 50vh;
        }
        
        .logo-container {
            transition: transform 0.3s ease;
        }
        
        .logo-container:hover {
            transform: scale(1.02);
        }
        
        .logo-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }
        
        @keyframes pulse-glow {
            0%, 100% {
                opacity: 0.3;
                transform: translate(-50%, -50%) scale(1);
            }
            50% {
                opacity: 0.5;
                transform: translate(-50%, -50%) scale(1.05);
            }
        }
        
        .section-header h2 {
            position: relative;
            display: inline-block;
        }
        
        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #0d6efd, #cb2368);
            border-radius: 2px;
        }
        
        .category-card {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            background: #fff;
        }
        
        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(13, 110, 253, 0.15);
            border-color: #0d6efd;
        }
        
        .category-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #0d6efd, #cb2368);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    }
    
    .category-card:hover .category-icon {
        transform: scale(1.1);
        background: linear-gradient(135deg, #cb2368, #0d6efd);
        box-shadow: 0 6px 20px rgba(203, 35, 104, 0.4);
    }
    
    /* Ensure equal height for all sections */
    .row.g-4 {
        align-items: stretch;
    }
    
    .col-lg-8, .col-lg-4 {
        display: flex;
        flex-direction: column;
    }
    
    .col-lg-8 > div, .col-lg-4 > div {
        flex: 1;
    }
    
    /* Minimum height for symmetry */
    .category-card, .recent-orders-card {
        min-height: 400px;
    }
    
    .category-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .category-card .card-body {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
        
        .btn-outline-primary {
            border-width: 2px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }
        
        /* Recent Orders Styling */
        .recent-orders-card {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .recent-orders-card .card-header {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef) !important;
            border-bottom: 1px solid #dee2e6;
        }
        
        .order-item {
            transition: all 0.2s ease;
            position: relative;
        }
        
        .order-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }
        
        .order-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(135deg, #0d6efd, #cb2368);
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        
        .order-item:hover::before {
            opacity: 1;
        }
        
        .order-images img {
            transition: transform 0.2s ease;
        }
        
        .order-images img:hover {
            transform: scale(1.1);
        }
        
        .empty-state {
            padding: 2rem 1rem;
        }
        
        .badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.4em 0.8em;
        }
        
        /* Welcome Page Responsive Styles */
        @media (max-width: 991px) {
            .hero-section {
                margin-bottom: 3rem;
            }
            
            .min-vh-50 {
                min-height: auto;
            }
            
            .hero-content {
                padding: 3rem 2rem !important;
                text-align: center;
            }
            
            .hero-image {
                padding: 2rem !important;
                min-height: 300px !important;
            }
            
            .logo-container img {
                max-width: 280px !important;
                max-height: 280px !important;
            }
            
            .col-lg-8, .col-lg-4 {
                margin-bottom: 2rem;
            }
            
            .row.g-4 {
                align-items: normal;
            }
            
            .category-card, .recent-orders-card {
                min-height: 350px;
            }
        }
        
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2rem;
            }
            
            .hero-section p {
                font-size: 1rem;
            }
            
            .hero-content {
                padding: 2.5rem 1.5rem !important;
            }
            
            .hero-image {
                min-height: 250px !important;
            }
            
            .logo-container img {
                max-width: 220px !important;
                max-height: 220px !important;
            }
            
            .btn-lg {
                padding: 0.75rem 2rem !important;
                font-size: 1rem !important;
            }
            
            .category-icon {
                width: 70px;
                height: 70px;
            }
            
            .category-icon i {
                font-size: 2rem !important;
            }
            
            .category-card, .recent-orders-card {
                min-height: 300px;
            }
        }
        
        @media (max-width: 576px) {
            .container {
                padding: 0 15px;
            }
            
            .hero-section h1 {
                font-size: 1.75rem;
            }
            
            .hero-section p {
                font-size: 0.9rem;
            }
            
            .hero-content {
                padding: 2rem 1rem !important;
            }
            
            .hero-image {
                min-height: 200px !important;
                padding: 1.5rem !important;
            }
            
            .logo-container img {
                max-width: 180px !important;
                max-height: 180px !important;
            }
            
            .btn-lg {
                padding: 0.625rem 1.5rem !important;
                font-size: 0.9rem !important;
            }
            
            .d-flex.gap-3 {
                flex-direction: column;
                align-items: center;
            }
            
            .d-flex.gap-3 .btn {
                width: 100%;
                max-width: 250px;
            }
            
            .category-card {
                margin-bottom: 1rem;
            }
            
            .category-icon {
                width: 60px;
                height: 60px;
            }
            
            .category-icon i {
                font-size: 1.75rem !important;
            }
            
            .category-card, .recent-orders-card {
                min-height: 280px;
            }
            
            .col-lg-8, .col-lg-4 {
                display: block;
            }
            
            .bg-light.p-5 {
                padding: 2rem !important;
            }
            
            .display-5 {
                font-size: 1.8rem;
            }
            
            .lead {
                font-size: 1rem;
                margin-bottom: 1rem;
            }
            
            .row.align-items-center {
                text-align: center;
            }
            
            .col-md-6 {
                margin-bottom: 1.5rem;
            }
            
            .img-fluid {
                max-width: 80%;
                height: auto;
            }
            
            .col-md-4 {
                margin-bottom: 1rem;
            }
            
            .card-body.p-4 {
                padding: 1.5rem !important;
            }
            
            .fs-1 {
                font-size: 2rem !important;
            }
            
            .h5, h5 {
                font-size: 1.1rem;
            }
            
            .text-muted {
                font-size: 0.9rem;
            }
            
            .btn-sm {
                font-size: 0.8rem;
                padding: 0.375rem 0.75rem;
            }
            
            .col-md-8, .col-md-4 {
                margin-bottom: 1.5rem;
            }
            
            .card {
                margin-bottom: 1rem;
            }
        }
        
        @media (min-width: 577px) and (max-width: 768px) {
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            .bg-light.p-5 {
                padding: 3rem !important;
            }
            
            .display-5 {
                font-size: 2.2rem;
            }
            
            .col-md-4 {
                flex: 0 0 auto;
                width: 50%;
                margin-bottom: 1rem;
            }
            
            .col-md-6 {
                margin-bottom: 1rem;
            }
            
            .col-md-8 {
                flex: 0 0 auto;
                width: 100%;
                margin-bottom: 1.5rem;
            }
        }
        
        @media (min-width: 769px) and (max-width: 992px) {
            .container {
                max-width: 720px;
            }
            
            .col-md-4 {
                flex: 0 0 auto;
                width: 33.333333%;
            }
            
            .col-md-6 {
                flex: 0 0 auto;
                width: 50%;
            }
            
            .col-md-8 {
                flex: 0 0 auto;
                width: 66.666667%;
            }
        }
        
        @media (min-width: 993px) and (max-width: 1200px) {
            .container {
                max-width: 960px;
            }
        }
    </style>
@endsection
