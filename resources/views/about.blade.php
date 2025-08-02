{{-- resources/views/customer/about.blade.php --}}
@extends('layouts.customer')

@section('content')
    <div class="container mt-4">
        <!-- Navbar -->
        <x-navbar />

        <!-- Hero Section -->
        <div class="bg-light p-5 rounded mb-4 text-center">
            <h1 class="display-5 fw-bold text-primary">Tentang Kami</h1>
            <p class="lead">Mengetahui lebih dekat tentang {{ $titleShop }}</p>
        </div>

        <!-- About Content -->
        <div class="row mb-5">
            <div class="col-md-8">
                <h3 class="mb-4">Tentang {{ $titleShop }}</h3>
                <p class="mb-4">
                    {{ $titleShop }} adalah usaha kecil menengah yang berfokus pada produksi dan distribusi seragam
                    sekolah berkualitas tinggi.
                    Didirikan pada tahun 2010, kami telah melayani lebih dari 100 sekolah di wilayah Jakarta dan sekitarnya.
                </p>
                <p class="mb-4">
                    Kami berkomitmen untuk menyediakan seragam sekolah yang tidak hanya memenuhi standar kualitas, tetapi
                    juga nyaman digunakan
                    dan terjangkau bagi semua kalangan. Dengan tim produksi yang berpengalaman dan bahan baku terbaik, kami
                    selalu berusaha
                    memberikan yang terbaik untuk para pelanggan setia kami.
                </p>
                <p class="mb-4">
                    Visi kami adalah menjadi pelopor dalam industri seragam sekolah yang mengutamakan kualitas, inovasi, dan
                    pelayanan prima.
                    Kami percaya bahwa dengan dedikasi dan kerja keras, kami dapat memenuhi kebutuhan seragam sekolah di
                    seluruh Indonesia.
                </p>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Mengapa memilih kami?</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check-circle-fill text-primary me-2 fs-4"></i>
                                    <h6 class="mb-0">Kualitas Terbaik</h6>
                                </div>
                                <p class="small mb-0">Bahan berkualitas tinggi dan produksi yang teliti</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check-circle-fill text-primary me-2 fs-4"></i>
                                    <h6 class="mb-0">Desain Modern</h6>
                                </div>
                                <p class="small mb-0">Desain seragam yang up-to-date dan modis</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-check-circle-fill text-primary me-2 fs-4"></i>
                                    <h6 class="mb-0">Harga Terjangkau</h6>
                                </div>
                                <p class="small mb-0">Harga kompetitif dengan kualitas yang tidak kompromi</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Profil Perusahaan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="mb-2"><strong>Didirikan:</strong> 2010</p>
                                <p class="mb-2"><strong>Alamat:</strong> Jl. Raya Seragam No. 45, Jakarta Selatan</p>
                                <p class="mb-2"><strong>Email:</strong> info@seragamsekolah.com</p>
                                <p class="mb-2"><strong>Telepon:</strong> (021) 12345678</p>
                                <p class="mb-2"><strong>NPWP:</strong> 12.345.678.9-012.345</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Fasilitas Kami</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-center">
                                <i class="bi bi-check-circle text-primary me-2"></i>
                                <span>Pengiriman Cepat</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="bi bi-check-circle text-primary me-2"></i>
                                <span>Pelayanan Pelanggan 24/7</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="bi bi-check-circle text-primary me-2"></i>
                                <span>Garansi 1 Tahun</span>
                            </li>
                            <li class="list-group-item d-flex align-items-center">
                                <i class="bi bi-check-circle text-primary me-2"></i>
                                <span>Pembayaran Aman</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Testimoni Pelanggan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="mb-1 fw-bold">SMK Negeri 1 Jakarta</p>
                            <div class="text-warning mb-1">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <p class="small">"Kualitas seragam sangat baik dan layanan pelanggan sangat memuaskan. Sudah
                                bekerja sama selama 5 tahun."</p>
                        </div>
                        <div>
                            <p class="mb-1 fw-bold">SMA Katolik St. Yoseph</p>
                            <div class="text-warning mb-1">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-half"></i>
                            </div>
                            <p class="small">"Desain seragam sangat modis dan sesuai dengan kebutuhan sekolah kami."</p>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Hubungi Kami</h5>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan</label>
                                <textarea class="form-control" id="message" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery -->
        <h3 class="mb-4">Galeri</h3>
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card">
                    <img src="{{ asset('images/kemeja-sma-pdk.png') }}" class="card-img-top" alt="Produk 1">
                    <div class="card-body">
                        <p class="card-text text-center">Koleksi Seragam SMA</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <img src="{{ asset('images/kemeja-smp-pdk.png') }}" class="card-img-top" alt="Produk 2">
                    <div class="card-body">
                        <p class="card-text text-center">Koleksi Seragam SMP</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <img src="{{ asset('images/kemeja-sd-pdk.png') }}" class="card-img-top" alt="Produk 3">
                    <div class="card-body">
                        <p class="card-text text-center">Koleksi Seragam SD</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <img src="{{-- asset('images/gallery4.jpg') --}}" class="card-img-top" alt="Produk 4">
                    <div class="card-body">
                        <p class="card-text text-center">Proses Produksi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
