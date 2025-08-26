<nav class="navbar navbar-expand-lg navbar-light bg-white mb-3 shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="{{ asset('images/ravazka.jpg') }}" alt="Logo" height="20">
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
                @auth
                    @if(Auth::user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('inventory') ? 'active' : '' }}"
                                href="/inventory">Inventaris</a>
                        </li>

                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('orders*') ? 'active' : '' }}" href="/orders">
                                <i class="bi bi-bag-check me-1"></i>Pesanan
                            </a>
                        </li>
                    @endif
                @endauth
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
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" id="searchDropdown">
                        <i class="bi bi-search"></i>
                    </a>
                    <div class="dropdown-menu p-2" style="min-width: 300px;">
                        <form action="{{ route('customer.products') }}" method="GET" id="searchForm">
                            <div class="input-group">
                                <input class="form-control" type="text" name="search" id="searchInput" 
                                       placeholder="Cari produk..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                        <div class="mt-2">
                            <small class="text-muted">Tekan Enter atau klik tombol untuk mencari</small>
                        </div>
                    </div>
                </div>

                <!-- Keranjang -->
                @auth
                    <a href="{{ route('cart.index') }}" class="nav-link position-relative me-3">
                        <i class="bi bi-cart3"></i>
                        <span id="cart-count"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="nav-link position-relative me-3" 
                       title="Silakan login untuk mengakses keranjang" data-bs-toggle="tooltip">
                        <i class="bi bi-cart3 text-muted"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">0</span>
                    </a>
                @endauth

                <!-- Login/Register atau User Menu -->
                @guest
                    <div class="d-flex align-items-center">
                        <a href="{{ route('login') }}" class="nav-link me-2">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-sm btn-outline-primary">Daftar</a>
                    </div>
                @else
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width: 280px;">
                            <!-- Profile Information -->
                            <li class="px-3 py-2">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-person-circle me-2 text-primary" style="font-size: 2rem;"></i>
                                    <div>
                                        <div class="fw-bold" style="font-size: 1.1rem;">{{ Auth::user()->name }}</div>
                                        <small class="text-muted">{{ Auth::user()->email }}</small>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    @if(Auth::user()->isAdmin())
                                        <span class="badge bg-primary">Administrator</span>
                                    @else
                                        <span class="badge bg-secondary">User/Pelanggan</span>
                                    @endif
                                </div>
                                <div class="mb-0">
                                    <small class="text-muted">Bergabung: {{ Auth::user()->created_at->format('d M Y') }}</small>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            
                            <!-- Navigation Menu -->
                            @if (Auth::user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('inventory.index') }}">
                                        <i class="bi bi-box-seam me-2"></i>Inventaris
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}">
                                        <i class="bi bi-graph-up me-2"></i>Laporan Penjualan
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @else
                                <li><a class="dropdown-item" href="/orders">
                                        <i class="bi bi-bag-check me-2"></i>Pesanan Saya
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.orders.track') }}">
                                        <i class="bi bi-search me-2"></i>Lacak Pesanan
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @endif
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    const searchDropdown = document.getElementById('searchDropdown');
    
    // Focus pada input ketika dropdown dibuka
    searchDropdown.addEventListener('click', function() {
        setTimeout(() => {
            searchInput.focus();
        }, 100);
    });
    
    // Submit form ketika Enter ditekan
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchForm.submit();
        }
    });
    
    // Prevent dropdown close ketika klik di dalam search form saja
    const searchDropdownMenu = document.querySelector('#searchDropdown + .dropdown-menu');
    if (searchDropdownMenu) {
        searchDropdownMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Quick search - redirect langsung jika input kosong
    searchForm.addEventListener('submit', function(e) {
        const searchValue = searchInput.value.trim();
        if (searchValue === '') {
            e.preventDefault();
            window.location.href = '{{ route("customer.products") }}';
        }
    });
});
</script>
