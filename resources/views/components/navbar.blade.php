<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="{{ asset('images/logo3.jpg') }}" alt="Logo" height="40">
            {{-- <span class="text-primary fw-bold ms-2">{{ $titleShop ?? 'Toko Seragam Sekolah' }}</span> --}}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('product') ? 'active' : '' }}" href="/products">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('inventory') ? 'active' : '' }}"
                        href="/inventory">Inventaris</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="/about">Tentang Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="/contact">Kontak</a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <!-- Pencarian -->
                <div class="dropdown me-3">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-search"></i>
                    </a>
                    <div class="dropdown-menu p-2">
                        <input class="form-control" type="text" placeholder="Cari produk...">
                    </div>
                </div>

                <!-- Keranjang -->
                <a href="{{ route('cart.index') }}" class="nav-link position-relative me-3">
                    <i class="bi bi-cart3"></i>
                    <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                </a>

                <!-- Login/Register -->
                <div class="d-flex align-items-center">
                    <a href="/login" class="nav-link me-2">Masuk</a>
                    <a href="/register" class="btn btn-sm btn-outline-primary">Daftar</a>
                </div>
            </div>
        </div>
    </div>
</nav>
