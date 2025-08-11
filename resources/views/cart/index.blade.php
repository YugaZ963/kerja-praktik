@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

@section('content')
    <div class="container mt-4">
        <x-navbar />

        <!-- Hero Section -->
        <div class="bg-light p-4 rounded mb-4 text-center">
            <h1 class="h3 fw-bold text-primary">Keranjang Belanja</h1>
            <p class="mb-0">Kelola produk yang akan Anda beli</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($cartItems->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-cart-x display-1 text-muted"></i>
                <h3 class="mt-3 text-muted">Keranjang Kosong</h3>
                <p class="text-muted">Belum ada produk di keranjang Anda</p>
                <a href="{{ route('customer.products') }}" class="btn btn-primary">
                    <i class="bi bi-shop"></i> Mulai Belanja
                </a>
            </div>
        @else
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Produk di Keranjang ({{ $itemCount }} item)</h5>
                        </div>
                        <div class="card-body p-0">
                            @foreach($cartItems as $item)
                                <div class="border-bottom p-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('images/kemeja-sd-pdk.png') }}" 
                                                 class="img-fluid rounded" alt="{{ $item->product->name }}" style="height: 80px; object-fit: cover;">
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                                            <small class="text-muted">{{ $item->product->category }} - {{ $item->product->size }}</small>
                                            <br>
                                            <span class="text-primary fw-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center">
                                                @csrf
                                                @method('PUT')
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="decreaseQuantity({{ $item->id }})">-</button>
                                                <input type="number" name="quantity" id="quantity-{{ $item->id }}" value="{{ $item->quantity }}" 
                                                       min="1" max="{{ $item->product->stock }}" class="form-control form-control-sm mx-2 text-center" style="width: 60px;">
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="increaseQuantity({{ $item->id }}, {{ $item->product->stock }})">+</button>
                                                <button type="submit" class="btn btn-sm btn-primary ms-2">Update</button>
                                            </form>
                                            <small class="text-muted">Stok: {{ $item->product->stock }}</small>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="text-end">
                                                <div class="fw-bold">Rp {{ number_format($item->total, 0, ',', '.') }}</div>
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger mt-1" 
                                                            onclick="return confirm('Hapus produk dari keranjang?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-3">
                        <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" 
                                    onclick="return confirm('Kosongkan semua keranjang?')">
                                <i class="bi bi-trash"></i> Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Ringkasan Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal ({{ $itemCount }} item)</span>
                                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Ongkos Kirim</span>
                                <span class="text-muted">Di lihat dalam Resi</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total</strong>
                                <strong class="text-primary">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                            </div>
                            <a href="{{ route('cart.checkout') }}" class="btn btn-primary w-100">
                                <i class="bi bi-credit-card"></i> Checkout
                            </a>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-body">
                            <h6><i class="bi bi-shield-check text-success"></i> Jaminan Kualitas</h6>
                            <small class="text-muted">Produk berkualitas tinggi dengan garansi kepuasan</small>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        function increaseQuantity(itemId, maxStock) {
            const input = document.getElementById('quantity-' + itemId);
            const currentValue = parseInt(input.value);
            if (currentValue < maxStock) {
                input.value = currentValue + 1;
            }
        }

        function decreaseQuantity(itemId) {
            const input = document.getElementById('quantity-' + itemId);
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }
    </script>
@endsection