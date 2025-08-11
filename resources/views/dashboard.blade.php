@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </h4>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-8">
                        <h2 class="mb-3">
                            Selamat datang, {{ Auth::user()->name }}! 
                            @if(Auth::user()->isAdmin())
                                <span class="badge bg-primary">Admin</span>
                            @else
                                <span class="badge bg-secondary">User</span>
                            @endif
                        </h2>
                        
                        @if(Auth::user()->isAdmin())
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Akses Admin:</strong> Anda memiliki akses penuh ke sistem inventaris RAVAZKA.
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="bi bi-box-seam display-4 text-primary mb-3"></i>
                                            <h5>Kelola Inventaris</h5>
                                            <p class="text-muted">Tambah, edit, dan hapus item inventaris</p>
                                            <a href="{{ route('inventory.index') }}" class="btn btn-primary">
                                                <i class="bi bi-arrow-right me-1"></i>
                                                Buka Inventaris
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="bi bi-cart-check display-4 text-warning mb-3"></i>
                                            <h5>Kelola Pesanan</h5>
                                            <p class="text-muted">Pantau dan kelola pesanan pelanggan</p>
                                            <a href="{{ route('admin.orders.index') }}" class="btn btn-warning">
                                                <i class="bi bi-arrow-right me-1"></i>
                                                Buka Pesanan
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="bi bi-file-text display-4 text-success mb-3"></i>
                                            <h5>Laporan</h5>
                                            <p class="text-muted">Lihat laporan stok dan analisis</p>
                                            <a href="{{ route('inventory.reports.stock') }}" class="btn btn-success">
                                                <i class="bi bi-arrow-right me-1"></i>
                                                Buka Laporan
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Akses User:</strong> Anda dapat melihat produk dan melakukan pembelian.
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="bi bi-shop display-4 text-primary mb-3"></i>
                                            <h5>Lihat Produk</h5>
                                            <p class="text-muted">Jelajahi koleksi seragam sekolah</p>
                                            <a href="{{ route('customer.products') }}" class="btn btn-primary">
                                                <i class="bi bi-arrow-right me-1"></i>
                                                Lihat Produk
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="bi bi-info-circle display-4 text-info mb-3"></i>
                                            <h5>Tentang Kami</h5>
                                            <p class="text-muted">Pelajari lebih lanjut tentang RAVAZKA</p>
                                            <a href="/about" class="btn btn-info">
                                                <i class="bi bi-arrow-right me-1"></i>
                                                Tentang Kami
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-person-circle me-1"></i>
                                    Informasi Akun
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Nama:</strong><br>
                                    {{ Auth::user()->name }}
                                </div>
                                <div class="mb-3">
                                    <strong>Email:</strong><br>
                                    {{ Auth::user()->email }}
                                </div>
                                <div class="mb-3">
                                    <strong>Role:</strong><br>
                                    @if(Auth::user()->isAdmin())
                                        <span class="badge bg-primary">Administrator</span>
                                    @else
                                        <span class="badge bg-secondary">User/Pelanggan</span>
                                    @endif
                                </div>
                                <div class="mb-0">
                                    <strong>Bergabung:</strong><br>
                                    <small class="text-muted">{{ Auth::user()->created_at->format('d M Y') }}</small>
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
.card {
    border-radius: 10px;
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.display-4 {
    font-size: 3rem;
}

.btn {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
@endsection