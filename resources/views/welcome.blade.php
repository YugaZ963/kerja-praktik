@extends('layouts.customer')

@section('content')
    <div class="container mt-4">
        <!-- Navbar -->
        <x-navbar />


        <!-- Hero Section -->
        <div class="bg-light p-5 rounded mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="display-5 fw-bold text-primary">Seragam Sekolah Berkualitas</h1>
                    <p class="lead">Pilih koleksi seragam sekolah terlengkap dengan kualitas terbaik dan harga kompetitif
                    </p>
                    <a href="/products" class="btn btn-lg btn-primary">Lihat Koleksi</a>
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('images/logo2.jpeg') }}" alt="Hero Image" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>

        <!-- Featured Products -->
        <h3 class="mb-4">Produk Terbaru</h3>
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    <div class="position-relative">
                        <img src="{{ asset('images/kemeja-sd-pdk.png') }}" class="card-img-top" alt="kemeja-sd-pdk">
                    </div>
                    <div class="card-body p-3">
                        <h5 class="card-title">Kemeja SD Putih</h5>
                        <p class="card-text text-muted">Kemeja sekolah SD berwarna putih polos</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-bold">Rp 150.000</span>
                                <small class="text-muted"> · S</small>
                            </div>
                            <form action="/cart/add/1" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-3">
            <a href="/products" class="btn btn-outline-primary">Lihat Lebih Banyak</a>
        </div>

        <!-- Categories -->
        <div class="row mt-5">
            <div class="col-md-8">
                <h3 class="mb-4">Kategori Populer</h3>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-mortarboard fs-1 text-primary"></i>
                                <h5 class="mt-3">Seragam SMA</h5>
                                <p class="text-muted">SMA/SMK/SMAK dan sejenisnya</p>
                                <a href="{{ route('customer.products', ['category' => 'sma']) }}" class="btn btn-sm btn-outline-primary">Lihat Produk</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-people fs-1 text-primary"></i>
                                <h5 class="mt-3">Seragam SMP</h5>
                                <p class="text-muted">SMP/MTs dan sejenisnya</p>
                                <a href="{{ route('customer.products', ['category' => 'smp']) }}" class="btn btn-sm btn-outline-primary">Lihat Produk</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-patch-check fs-1 text-primary"></i>
                                <h5 class="mt-3">Seragam SD</h5>
                                <p class="text-muted">SD/MI dan sejenisnya</p>
                                <a href="{{ route('customer.products', ['category' => 'sd']) }}" class="btn btn-sm btn-outline-primary">Lihat Produk</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="col-md-4">
                <h3 class="mb-4">Pesanan Terbaru</h3>
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">10 Mei 2023</small>
                            <p class="mb-0">SMK Negeri 1 Jakarta</p>
                            <small class="text-success">Pesanan Selesai</small>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">08 Mei 2023</small>
                            <p class="mb-0">SMA Katolik St. Yoseph</p>
                            <small class="text-warning">Dalam Proses</small>
                        </div>

                        <div class="mb-0">
                            <small class="text-muted">05 Mei 2023</small>
                            <p class="mb-0">MI Al-Azhar 3 Jakarta</p>
                            <small class="text-success">Pesanan Selesai</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Newsletter -->
        <div class="mt-5 bg-light p-4 rounded">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4>Langganan Newsletter</h4>
                    <p class="mb-0">Dapatkan promo dan update terbaru langsung ke email Anda</p>
                </div>
                <div class="col-md-4">
                    <form class="d-flex">
                        <input class="form-control me-2" type="email" placeholder="Masukkan Email Anda">
                        <button class="btn btn-primary" type="submit">Berlangganan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
