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

                <!-- Login/Register atau User Menu -->
                @guest
                    <div class="d-flex align-items-center">
                        <a href="{{ route('login') }}" class="nav-link me-2">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-sm btn-outline-primary">Daftar</a>
                    </div>
                @else
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a></li>
                            @if(Auth::user()->role === 'admin')
                                <li><a class="dropdown-item" href="{{ route('inventory.index') }}">
                                    <i class="bi bi-box-seam me-2"></i>Inventaris
                                </a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>
