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
            @if(!Auth::check() || !Auth::user()->isAdmin())
            <!-- Menu navigasi untuk customer dan guest -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/products">Produk</a>
                </li>
                @auth
                    @if(!Auth::user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link" href="/orders">Pesanan Saya</a>
                    </li>
                    @endif
                @endauth
                <li class="nav-item">
                    <a class="nav-link" href="/about">Tentang Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact">Kontak</a>
                </li>
            </ul>
            
            <!-- Search Bar untuk customer -->
            <div class="d-flex me-2">
                <form class="d-flex" method="GET" action="/products">
                    <input class="form-control me-1" type="search" name="search" placeholder="Cari..." 
                           value="{{ request('search') }}" style="width: 120px;">
                    <button class="btn btn-outline-primary btn-sm" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
            
            <!-- Cart untuk customer yang login -->
            @auth
                @if(!Auth::user()->isAdmin())
                <div class="me-2">
                    <a href="/cart" class="btn btn-outline-success btn-sm position-relative">
                        <i class="bi bi-cart3"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">
                            {{ Auth::user()->carts ? Auth::user()->carts->sum('quantity') : 0 }}
                        </span>
                    </a>
                </div>
                @endif
            @endauth
            
            <div class="d-flex align-items-center ms-auto">

                <!-- Login/Register atau User Menu -->
                @guest
                    <div class="d-flex align-items-center">
                        <a href="{{ route('login') }}" class="nav-link me-1">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-sm btn-outline-primary">Daftar</a>
                    </div>
                @else
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
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
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @else
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
            @endif
            </div>
        </div>
    </div>
</nav>

<script>
// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('form[action="/products"]');
    const searchInput = document.querySelector('input[name="search"]');
    
    if (searchForm && searchInput) {
        searchForm.addEventListener('submit', function(e) {
            if (searchInput.value.trim() === '') {
                e.preventDefault();
                window.location.href = '/products';
            }
        });
    }
});
</script>
