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
                                <a href="{{ route('customer.products', ['search' => 'SMA']) }}" class="btn btn-sm btn-outline-primary">Lihat Produk</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-people fs-1 text-primary"></i>
                                <h5 class="mt-3">Seragam SMP</h5>
                                <p class="text-muted">SMP/MTs dan sejenisnya</p>
                                <a href="{{ route('customer.products', ['search' => 'SMP']) }}" class="btn btn-sm btn-outline-primary">Lihat Produk</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-patch-check fs-1 text-primary"></i>
                                <h5 class="mt-3">Seragam SD</h5>
                                <p class="text-muted">SD/MI dan sejenisnya</p>
                                <a href="{{ route('customer.products', ['search' => 'SD']) }}" class="btn btn-sm btn-outline-primary">Lihat Produk</a>
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
                        @if($recentOrders && $recentOrders->count() > 0)
                            @foreach($recentOrders as $index => $order)
                                <div class="{{ $index < 2 ? 'mb-3' : 'mb-0' }}">
                                    <small class="text-muted">{{ $order->created_at->format('d M Y') }}</small>
                                    <p class="mb-0">{{ $order->user ? $order->user->name : $order->customer_name }}</p>
                                    <small class="
                                        @if($order->status == 'completed') text-success
                                        @elseif($order->status == 'delivered') text-info
                                        @elseif($order->status == 'cancelled') text-danger
                                        @elseif($order->status == 'shipped') text-info
                                        @elseif($order->status == 'processing' || $order->status == 'packaged') text-warning
                                        @else text-secondary
                                        @endif
                                    ">{{ $order->getStatusLabel() }}</small>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted">
                                <i class="bi bi-inbox fs-1 mb-3"></i>
                                <p>Belum ada pesanan</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
