{{-- resources/views/product.blade.php --}}
@extends('layouts.customer')

@section('content')
    <div class="container mt-4">
        <!-- Navbar -->
        <x-navbar />

        <!-- Hero Section -->
        <div class="bg-light p-5 rounded mb-4 text-center">
            <h1 class="display-5 fw-bold text-primary">Detail Produk</h1>
            <p class="lead">Informasi lengkap tentang produk kami</p>
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

        <!-- Product Detail -->
        <div class="row mb-5">
            <div class="col-md-5">
                <div class="card border-0 shadow-sm">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/kemeja-sd-pdk.png') }}" 
                         class="card-img-top" alt="{{ $product->name }}" style="height:400px;object-fit:cover;">
                </div>
            </div>
            <div class="col-md-7">
                <h2 class="mb-3">{{ $product->name }}</h2>
                <p class="badge bg-primary mb-3">{{ $product->category }}</p>
                
                <h3 class="text-primary mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>
                
                <div class="mb-3">
                    <h5>Ukuran</h5>
                    <p>{{ $product->size }}</p>
                </div>
                
                <div class="mb-4">
                    <h5>Stok</h5>
                    @if ($product->stock > 5)
                        <p class="text-success">Tersedia ({{ $product->stock }})</p>
                    @elseif ($product->stock <= 5 && $product->stock > 0)
                        <p class="text-warning">Stok Terbatas ({{ $product->stock }})</p>
                    @else
                        <p class="text-danger">Habis</p>
                    @endif
                </div>
                
                <div class="mb-4">
                    <h5>Deskripsi</h5>
                    <p>{{ $product->description }}</p>
                </div>
                
                <div class="d-grid gap-2 d-md-flex">
                    <a href="{{ route('customer.products') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Produk
                    </a>
                    @if ($product->stock > 0)
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                            </button>
                        </form>
                    @endif
                </div>
             </div>
        </div>
    </div>
    </div>

    <style>
        /* Product Detail Page Responsive Styles */
        @media (max-width: 576px) {
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }
            
            .bg-light.p-5 {
                padding: 2rem !important;
            }
            
            .display-5 {
                font-size: 1.8rem;
            }
            
            .lead {
                font-size: 1rem;
            }
            
            .row.mb-5 {
                margin-bottom: 2rem !important;
            }
            
            .col-md-5, .col-md-7 {
                margin-bottom: 1.5rem;
            }
            
            .card-img-top {
                height: 250px !important;
                object-fit: cover;
            }
            
            h2 {
                font-size: 1.5rem;
                margin-bottom: 1rem !important;
            }
            
            h3.text-primary {
                font-size: 1.3rem;
                margin-bottom: 1rem !important;
            }
            
            h5 {
                font-size: 1.1rem;
                margin-bottom: 0.5rem;
            }
            
            .badge {
                font-size: 0.8rem;
                padding: 0.5rem 0.75rem;
            }
            
            .btn {
                font-size: 0.9rem;
                padding: 0.75rem 1rem;
                width: 100%;
            }
            
            .btn-outline-secondary {
                margin-bottom: 0.5rem;
            }
            
            .mb-3 {
                margin-bottom: 1rem !important;
            }
            
            .mb-4 {
                margin-bottom: 1.5rem !important;
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
            
            .col-md-5, .col-md-7 {
                margin-bottom: 1rem;
            }
            
            .card-img-top {
                height: 300px !important;
                object-fit: cover;
            }
            
            h2 {
                font-size: 1.75rem;
            }
            
            h3.text-primary {
                font-size: 1.5rem;
            }
        }
        
        @media (min-width: 769px) and (max-width: 992px) {
            .container {
                max-width: 720px;
            }
            
            .col-md-5 {
                flex: 0 0 auto;
                width: 45%;
            }
            
            .col-md-7 {
                flex: 0 0 auto;
                width: 55%;
            }
            
            .card-img-top {
                height: 350px !important;
                object-fit: cover;
            }
        }
        
        @media (min-width: 993px) and (max-width: 1200px) {
            .container {
                max-width: 960px;
            }
            
            .card-img-top {
                height: 400px !important;
                object-fit: cover;
            }
        }
    </style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update cart count after adding product
    const addToCartForm = document.querySelector('form[action*="cart/add"]');
    
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            // Let the form submit normally, then update cart count after page reload
            setTimeout(() => {
                updateCartCount();
            }, 100);
        });
    }
});
</script>
@endpush
