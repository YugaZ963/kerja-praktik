{{-- resources/views/customer/products.blade.php --}}
@extends('layouts.customer')

@section('content')
    <div class="container mt-4">
        <!-- Navbar -->
        <x-navbar />

        <!-- Hero Section -->
        <div class="bg-light p-5 rounded mb-4 text-center">
            <h1 class="display-5 fw-bold text-primary">Koleksi Seragam Sekolah</h1>
            <p class="lead">Pilih koleksi seragam sekolah terlengkap dengan kualitas terbaik dan harga kompetitif</p>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <select class="form-select" id="category">
                                    <option selected>Pilih Kategori</option>
                                    <option value="sma">Seragam SMA</option>
                                    <option value="smp">Seragam SMP</option>
                                    <option value="sd">Seragam SD</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="size">
                                    <option selected>Pilih Ukuran</option>
                                    <option value="s">S</option>
                                    <option value="m">M</option>
                                    <option value="l">L</option>
                                    <option value="xl">XL</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="sort">
                                    <option selected>Urutkan oleh</option>
                                    <option value="price-asc">Harga Terendah</option>
                                    <option value="price-desc">Harga Tertinggi</option>
                                    <option value="name-asc">Nama A-Z</option>
                                    <option value="name-desc">Nama Z-A</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary w-100" id="filter-btn">Terapkan Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row g-3" id="products-container">
            {{-- @foreach ($products as $product) --}}
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">
                    <div class="position-relative">
                        <img src="{{ asset('images/kemeja-sd-pdk.png') }}{{-- asset('storage/products/' . $product->main_image) --}}" class="card-img-top"
                            alt="{{-- $product->name --}}">
                        {{-- @if ($product->stock <= 5) --}}
                        <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">Stok
                            Rendah</span>
                        {{-- @endif --}}
                    </div>
                    <div class="card-body p-3">
                        <h5 class="card-title">{{-- Str::limit($product->name, 20) --}}</h5>
                        <p class="card-text text-muted">{{-- Str::limit($product->description, 30) --}}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-bold">Rp {{-- number_format($product->price) --}}</span>
                                <small class="text-muted"> · {{-- $product->size --}}</small>
                            </div>
                            <form action="{{-- route('cart.add', $product->id) --}}" method="POST" class="d-inline">
                                {{-- @csrf --}}
                                <button type="submit" class="btn btn-sm btn-primary">Tambah</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="{{-- route('products.show', $product->id) --}}" class="btn btn-sm btn-outline-primary w-100">Lihat Detail</a>
                    </div>
                </div>
            </div>
            {{-- @endforeach --}}
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{-- $products->links() --}}
        </div>
    </div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtn = document.getElementById('filter-btn');
            const productsContainer = document.getElementById('products-container');

            filterBtn.addEventListener('click', function() {
                const category = document.getElementById('category').value;
                const size = document.getElementById('size').value;
                const sort = document.getElementById('sort').value;

                // Simulasi permintaan AJAX untuk filter
                // Dalam implementasi sebenarnya, Anda akan mengirim permintaan ke server dan memperbarui kontainer produk
                console.log('Filter diterapkan:', {
                    category,
                    size,
                    sort
                });

                // Anda dapat menggunakan library seperti axios untuk permintaan AJAX
                // Contoh:
                // axios.get('/api/products', {
                //     params: {
                //         category: category,
                //         size: size,
                //         sort: sort
                //     }
                // }).then(response => {
                //     // Memperbarui kontainer produk dengan hasil filter
                //     productsContainer.innerHTML = '';
                //     response.data.products.forEach(product => {
                //         // Membuat elemen produk baru dan menambahkannya ke kontainer
                //     });
                // });
            });
        });
    </script>
@endsection
