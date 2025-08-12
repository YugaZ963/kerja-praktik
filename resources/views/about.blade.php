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
                                <p class="mb-2"><strong>Didirikan:</strong> 2000</p>
                                <p class="mb-2"><strong>Alamat:</strong> Pasar Baru, Bandung, Jawa Barat</p>
                                <p class="mb-2"><strong>Email:</strong> ravazka963@gmail.com</p>
                                <p class="mb-2"><strong>Telepon:</strong> +62 896-7775-4918</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Testimoni Pelanggan</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $testimonials = \App\Models\Testimonial::where('is_approved', true)
                                ->orderBy('created_at', 'desc')
                                ->limit(3)
                                ->get();
                        @endphp
                        
                        @if($testimonials->count() > 0)
                            @foreach($testimonials as $testimonial)
                            <div class="mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                <p class="small mb-1 fst-italic">"{{ $testimonial->testimonial_text }}"</p>
                                <small class="text-muted fw-bold">- {{ $testimonial->customer_name }}</small>
                            </div>
                            @endforeach
                        @else
                            <!-- Default testimonials when no database testimonials exist -->
                            <div class="mb-3 border-bottom pb-3">
                                <p class="small mb-1 fst-italic">"Kualitas seragam sangat baik dan layanan pelanggan sangat memuaskan. Sudah bekerja sama selama 5 tahun."</p>
                                <small class="text-muted fw-bold">- Kepala Sekolah SMK Negeri 1 Jakarta</small>
                            </div>
                            <div class="mb-3 border-bottom pb-3">
                                <p class="small mb-1 fst-italic">"Desain seragam sangat modis dan sesuai dengan kebutuhan sekolah kami."</p>
                                <small class="text-muted fw-bold">- Wakil Kepala Sekolah SMA Katolik St. Yoseph</small>
                            </div>
                            <div>
                                <p class="small mb-1 fst-italic">"Pelayanan sangat profesional dan hasil jahitan rapi. Siswa-siswi merasa nyaman menggunakan seragam dari RAVAZKA."</p>
                                <small class="text-muted fw-bold">- Koordinator Kesiswaan SMP Negeri 5 Bandung</small>
                            </div>
                        @endif
                        
                        @if($testimonials->count() > 0)
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Menampilkan {{ $testimonials->count() }} testimoni terbaru dari pelanggan kami
                            </small>
                        </div>
                        @endif
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
