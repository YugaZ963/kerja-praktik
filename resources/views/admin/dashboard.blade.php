@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                        <h4 class="mb-2 mb-sm-0">
                            <i class="bi bi-speedometer2 me-2"></i>
                            Dashboard
                        </h4>
                        <div class="d-flex align-items-center">
                            <span class="me-2">{{ Auth::user()->name }}</span>
                            @if(Auth::user()->isAdmin())
                                <span class="badge bg-light text-primary">Admin</span>
                            @else
                                <span class="badge bg-light text-secondary">User</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 col-md-12 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-3 p-md-4">
                        
                    @if(Auth::user()->isAdmin())
                        <div class="alert alert-info border-0 shadow-sm mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Akses Admin:</strong> Anda memiliki akses penuh ke sistem inventaris RAVAZKA.
                        </div>
                        
                        <!-- Admin Menu Cards -->
                        <div class="row g-3 g-md-4">
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="card bg-gradient-primary text-white border-0 shadow-sm h-100 admin-card">
                                    <div class="card-body text-center p-3 p-md-4">
                                        <i class="bi bi-grid-3x3-gap display-6 mb-3"></i>
                                        <h5 class="card-title mb-2">Manajemen Inventaris</h5>
                                        <p class="card-text small opacity-75 mb-3">Kelola stok dan inventaris produk</p>
                                        <a href="{{ route('inventory.index') }}" class="btn btn-light btn-sm">
                                            <i class="bi bi-arrow-right me-1"></i>
                                            Kelola Inventaris
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="card bg-gradient-warning text-white border-0 shadow-sm h-100 admin-card">
                                    <div class="card-body text-center p-3 p-md-4">
                                        <i class="bi bi-cart-check display-6 mb-3"></i>
                                        <h5 class="card-title mb-2">Manajemen Pesanan</h5>
                                        <p class="card-text small opacity-75 mb-3">Kelola pesanan dengan laporan</p>
                                        <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm">
                                            <i class="bi bi-arrow-right me-1"></i>
                                            Kelola Pesanan
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="card bg-gradient-success text-white border-0 shadow-sm h-100 admin-card">
                                    <div class="card-body text-center p-3 p-md-4">
                                        <i class="bi bi-bar-chart display-6 mb-3"></i>
                                        <h5 class="card-title mb-2">Laporan Inventaris</h5>
                                        <p class="card-text small opacity-75 mb-3">Analisis stok dan nilai inventaris</p>
                                        <a href="{{ route('inventory.report') }}" class="btn btn-light btn-sm">
                                            <i class="bi bi-arrow-right me-1"></i>
                                            Lihat Laporan
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="card bg-gradient-info text-white border-0 shadow-sm h-100 admin-card">
                                    <div class="card-body text-center p-3 p-md-4">
                                        <i class="bi bi-graph-up display-6 mb-3"></i>
                                        <h5 class="card-title mb-2">Laporan Penjualan</h5>
                                        <p class="card-text small opacity-75 mb-3">Analisis penjualan dan pendapatan</p>
                                        <a href="{{ route('admin.sales.index') }}" class="btn btn-light btn-sm">
                                            <i class="bi bi-arrow-right me-1"></i>
                                            Lihat Laporan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning border-0 shadow-sm mb-4">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Akses User:</strong> Anda dapat melihat produk dan melakukan pembelian.
                        </div>
                        
                        <!-- User Menu Cards -->
                        <div class="row g-3 g-md-4">
                            <div class="col-12 col-sm-6">
                                <div class="card bg-gradient-primary text-white border-0 shadow-sm h-100 admin-card">
                                    <div class="card-body text-center p-3 p-md-4">
                                        <i class="bi bi-shop display-6 mb-3"></i>
                                        <h5 class="card-title mb-2">Lihat Produk</h5>
                                        <p class="card-text small opacity-75 mb-3">Jelajahi koleksi seragam sekolah</p>
                                        <a href="{{ route('customer.products') }}" class="btn btn-light btn-sm">
                                            <i class="bi bi-arrow-right me-1"></i>
                                            Lihat Produk
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="card bg-gradient-info text-white border-0 shadow-sm h-100 admin-card">
                                    <div class="card-body text-center p-3 p-md-4">
                                        <i class="bi bi-info-circle display-6 mb-3"></i>
                                        <h5 class="card-title mb-2">Tentang Kami</h5>
                                        <p class="card-text small opacity-75 mb-3">Pelajari lebih lanjut tentang RAVAZKA</p>
                                        <a href="/about" class="btn btn-light btn-sm">
                                            <i class="bi bi-arrow-right me-1"></i>
                                            Tentang Kami
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar - Account Information -->
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-person-circle me-2 text-primary"></i>
                        Informasi Akun
                    </h6>
                </div>
                <div class="card-body p-3 p-md-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-circle display-6 text-primary mb-2"></i>
                        <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                        @if(Auth::user()->isAdmin())
                            <span class="badge bg-primary">Administrator</span>
                        @else
                            <span class="badge bg-secondary">User/Pelanggan</span>
                        @endif
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-center p-2 bg-light rounded">
                                <i class="bi bi-envelope me-2 text-muted"></i>
                                <div>
                                    <small class="text-muted d-block">Email</small>
                                    <span class="fw-medium">{{ Auth::user()->email }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center p-2 bg-light rounded">
                                <i class="bi bi-calendar-event me-2 text-muted"></i>
                                <div>
                                    <small class="text-muted d-block">Bergabung</small>
                                    <span class="fw-medium">{{ Auth::user()->created_at->format('d M Y') }}</span>
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
/* Custom Dashboard Styles */
.admin-card {
    border-radius: 15px;
    transition: all 0.3s ease;
    overflow: hidden;
}

.admin-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

/* Gradient Backgrounds */
.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #fcdf10 0%, #c9b012 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #0d6efd 0%, #cb2368 100%);
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #cb2368 0%, #a11d52 100%);
}

/* Icon Styles */
.display-6 {
    font-size: 2.5rem;
}

/* Button Styles */
.btn {
    border-radius: 10px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Card Styles */
.card {
    border-radius: 15px;
    transition: all 0.3s ease;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .card-body {
        padding: 1rem !important;
    }
    
    .display-6 {
        font-size: 2rem;
    }
    
    .admin-card .card-title {
        font-size: 1rem;
    }
    
    .admin-card .card-text {
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .d-flex.flex-column.flex-sm-row {
        text-align: center;
    }
    
    .d-flex.flex-column.flex-sm-row .badge {
        margin-top: 0.5rem;
    }
    
    .admin-card:hover {
        transform: translateY(-2px);
    }
}

/* Alert Styles */
.alert {
    border-radius: 10px;
}

/* Profile Section */
.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endsection