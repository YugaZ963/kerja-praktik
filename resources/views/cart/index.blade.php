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
                                <div class="border-bottom p-3" data-item-id="{{ $item->id }}">
                                    <div class="cart-item-row">
                                        <div class="cart-item-image">
                                            <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('images/kemeja-sd-pdk.png') }}" 
                                                 class="img-fluid rounded" alt="{{ $item->product->name }}">
                                        </div>
                                        <div class="cart-item-details">
                                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                                            <small class="text-muted">{{ $item->product->category }} - {{ $item->product->size }}</small>
                                            <div class="mt-1">
                                                <span class="text-primary fw-bold item-price" data-price="{{ $item->price }}">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="cart-item-quantity">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="quantity-controls">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="decreaseQuantity({{ $item->id }})">-</button>
                                                    <input type="number" name="quantity" id="quantity-{{ $item->id }}" value="{{ $item->quantity }}" 
                                                           min="1" max="{{ $item->product->stock }}" class="form-control form-control-sm" onchange="autoUpdateQuantity({{ $item->id }})">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="increaseQuantity({{ $item->id }}, {{ $item->product->stock }})">+</button>
                                                </div>
                                            </form>
                                            <small class="text-muted d-block text-center">Stok: {{ $item->product->stock }}</small>
                                        </div>
                                        <div class="cart-item-total">
                                            <div class="fw-bold mb-2 item-total">Rp {{ number_format($item->total, 0, ',', '.') }}</div>
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Hapus produk dari keranjang?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
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
                                <span>Subtotal (<span id="cart-item-count">{{ $itemCount }}</span> item)</span>
                                <span id="cart-subtotal">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Ongkos Kirim</span>
                                <span class="text-muted">Di lihat dalam Resi</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total</strong>
                                <strong class="text-primary" id="cart-total">Rp {{ number_format($total, 0, ',', '.') }}</strong>
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

    <style>
        /* Cart Responsive Styles */
        .cart-item-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 1rem;
        }
        
        .cart-item-image {
            flex: 0 0 auto;
        }
        
        .cart-item-details {
            flex: 1;
            min-width: 200px;
        }
        
        .cart-item-quantity {
            flex: 0 0 auto;
            min-width: 180px;
        }
        
        .cart-item-total {
            flex: 0 0 auto;
            min-width: 120px;
            text-align: right;
        }
        
        .quantity-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
            margin-bottom: 0.5rem;
            flex-wrap: wrap;
        }
        
        .quantity-controls .btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
            font-weight: 500;
        }
        
        .quantity-controls input {
            width: 60px;
            text-align: center;
            border-radius: 0.375rem;
            border: 1px solid #ced4da;
            height: 32px;
        }
        

        
        .quantity-controls .btn-outline-secondary {
            border-color: #6c757d;
            color: #6c757d;
        }
        
        .quantity-controls .btn-outline-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
        }
        
        /* Mobile Styles (< 768px) */
        @media (max-width: 767.98px) {
            .container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            .bg-light.p-4 {
                padding: 1.5rem !important;
                margin-bottom: 1rem !important;
            }
            
            .card-body {
                padding: 0.75rem;
            }
            
            .border-bottom.p-3 {
                padding: 1rem !important;
            }
            
            .cart-item-row {
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
            }
            
            .cart-item-image,
            .cart-item-details,
            .cart-item-quantity,
            .cart-item-total {
                width: 100%;
                text-align: center;
            }
            
            .cart-item-image img {
                max-width: 80px;
                height: 80px;
                object-fit: cover;
            }
            
            .cart-item-details h6 {
                margin-bottom: 0.25rem;
                font-size: 0.95rem;
            }
            
            .cart-item-details small {
                display: block;
                margin-bottom: 0.25rem;
            }
            
            .quantity-controls {
                justify-content: center;
                margin-bottom: 0.75rem;
                gap: 0.25rem;
            }
            
            .quantity-controls .btn {
                width: 30px;
                height: 30px;
                font-size: 0.875rem;
            }
            
            .quantity-controls input {
                width: 50px;
                height: 30px;
                font-size: 0.875rem;
            }
            

            
            .cart-item-total {
                border-top: 1px solid #dee2e6;
                padding-top: 0.75rem;
                margin-top: 0.5rem;
            }
            
            .h3 {
                font-size: 1.5rem;
            }
            
            .display-1 {
                font-size: 3rem;
            }
            
            .col-lg-8,
            .col-lg-4 {
                margin-bottom: 1rem;
            }
            
            .btn-outline-danger {
                width: 100%;
                margin-top: 1rem;
            }
        }
        
        /* Tablet Styles (768px - 991.98px) */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .bg-light.p-4 {
                padding: 2rem !important;
            }
            
            .cart-item-row {
                align-items: center;
            }
            
            .cart-item-image {
                flex: 0 0 100px;
            }
            
            .cart-item-image img {
                width: 90px;
                height: 90px;
                object-fit: cover;
            }
            
            .cart-item-details {
                flex: 1;
                padding-right: 1rem;
            }
            
            .cart-item-quantity {
                flex: 0 0 200px;
            }
            
            .cart-item-total {
                flex: 0 0 140px;
            }
            
            .quantity-controls {
                justify-content: flex-start;
                gap: 0.25rem;
            }
            
            .quantity-controls .btn {
                width: 32px;
                height: 32px;
            }
            
            .quantity-controls input {
                width: 55px;
                height: 32px;
            }
            

        }
        
        /* Desktop Styles (≥ 992px) */
        @media (min-width: 992px) {
            .cart-item-row {
                align-items: center;
            }
            
            .cart-item-image {
                flex: 0 0 120px;
            }
            
            .cart-item-image img {
                width: 100px;
                height: 100px;
                object-fit: cover;
            }
            
            .cart-item-details {
                flex: 1;
                padding-right: 1rem;
            }
            
            .cart-item-quantity {
                flex: 0 0 220px;
            }
            
            .cart-item-total {
                flex: 0 0 150px;
            }
            
            .quantity-controls {
                justify-content: flex-start;
                gap: 0.25rem;
            }
            
            .quantity-controls .btn {
                width: 34px;
                height: 34px;
            }
            
            .quantity-controls input {
                width: 60px;
                height: 34px;
            }
            

        }
        
        /* Additional improvements */
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }
        
        .btn-outline-secondary:hover {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        .alert {
            border-radius: 0.375rem;
        }
    </style>

    <script>
        function increaseQuantity(itemId, maxStock) {
            const input = document.getElementById('quantity-' + itemId);
            const currentValue = parseInt(input.value);
            if (currentValue < maxStock) {
                input.value = currentValue + 1;
                autoUpdateQuantity(itemId);
            }
        }

        function decreaseQuantity(itemId) {
            const input = document.getElementById('quantity-' + itemId);
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
                autoUpdateQuantity(itemId);
            }
        }

        function updateItemTotal(itemId) {
            const quantityInput = document.getElementById('quantity-' + itemId);
            const quantity = parseInt(quantityInput.value);
            const priceElement = document.querySelector(`[data-item-id="${itemId}"] .item-price`);
            const totalElement = document.querySelector(`[data-item-id="${itemId}"] .item-total`);
            
            if (priceElement && totalElement) {
                const price = parseInt(priceElement.getAttribute('data-price'));
                const total = price * quantity;
                totalElement.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
            }
        }

        function autoUpdateQuantity(itemId) {
            const quantityInput = document.getElementById('quantity-' + itemId);
            const quantity = parseInt(quantityInput.value);
            
            // Update quantity via AJAX
            fetch(`/cart/update/${itemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateItemTotal(itemId);
                    updateCartSummary();
                } else {
                    alert('Gagal mengupdate kuantitas');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate kuantitas');
            });
        }

        function updateCartSummary() {
            let totalItems = 0;
            let totalAmount = 0;
            
            // Hitung total dari semua item
            document.querySelectorAll('[data-item-id]').forEach(function(item) {
                const itemId = item.getAttribute('data-item-id');
                const quantityInput = document.getElementById('quantity-' + itemId);
                const priceElement = item.querySelector('.item-price');
                
                if (quantityInput && priceElement) {
                    const quantity = parseInt(quantityInput.value);
                    const price = parseInt(priceElement.getAttribute('data-price'));
                    totalItems += quantity;
                    totalAmount += (price * quantity);
                }
            });
            
            // Update tampilan ringkasan
            const subtotalElement = document.getElementById('cart-subtotal');
            const itemCountElement = document.getElementById('cart-item-count');
            const totalElement = document.getElementById('cart-total');
            
            if (subtotalElement) {
                subtotalElement.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalAmount);
            }
            if (itemCountElement) {
                itemCountElement.textContent = totalItems;
            }
            if (totalElement) {
                totalElement.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalAmount);
            }
        }

        // Event listener untuk input quantity manual
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[name="quantity"]').forEach(function(input) {
                input.addEventListener('input', function() {
                    const itemId = this.id.replace('quantity-', '');
                    updateItemTotal(itemId);
                    updateCartSummary();
                });
            });
        });
    </script>
@endsection