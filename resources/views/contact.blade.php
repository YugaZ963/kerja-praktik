{{-- resources/views/customer/contact.blade.php --}}
@extends('layouts.customer')

@section('content')
    <div class="container mt-4">
        <!-- Navbar -->
        <x-navbar />

        <!-- Hero Section -->
        <div class="bg-light p-5 rounded mb-4 text-center">
            <h1 class="display-5 fw-bold text-primary">Hubungi Kami</h1>
            <p class="lead">Butuh bantuan? Silakan hubungi kami melalui berbagai cara di bawah ini</p>
        </div>

        <!-- Contact Content -->
        <div class="row mb-5">
            <div class="col-md-6">
                <h3 class="mb-4">Informasi Kontak</h3>

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <i class="bi bi-geo-alt-fill text-primary me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-0">Alamat</h6>
                                <p class="mb-0">Jl. Raya Seragam No. 45, Jakarta Selatan, DKI Jakarta</p>
                            </div>
                        </div>

                        <div class="d-flex mb-3">
                            <i class="bi bi-telephone-fill text-primary me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-0">Telepon</h6>
                                <p class="mb-0">(021) 12345678</p>
                            </div>
                        </div>

                        <div class="d-flex mb-3">
                            <i class="bi bi-envelope-fill text-primary me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-0">Email</h6>
                                <p class="mb-0">info@seragamsekolah.com</p>
                            </div>
                        </div>

                        <div class="d-flex">
                            <i class="bi bi-clock-fill text-primary me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-0">Jam Operasional</h6>
                                <p class="mb-0">Senin - Jumat: 08.00 - 17.00<br>Sabtu: 09.00 - 13.00<br>Minggu: Tutup</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Ikuti Kami di Media Sosial</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-center gap-3">
                            <a href="#" class="text-reset fs-3">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="#" class="text-reset fs-3">
                                <i class="bi bi-twitter"></i>
                            </a>
                            <a href="#" class="text-reset fs-3">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="#" class="text-reset fs-3">
                                <i class="bi bi-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <h3 class="mb-4">Form Kontak</h3>
                <form action="{{-- route('contact.send') --}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Subjek</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Pesan</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                </form>
            </div>
        </div>

        <!-- Map -->
        <h3 class="mb-4">Lokasi Kami</h3>
        <div class="card">
            <div class="card-body">
                <div id="map" style="height: 400px;"></div>
            </div>
        </div>
    </div>

@section('scripts')
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.10.0/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.10.0/mapbox-gl.css" rel="stylesheet">
    <script>
        mapboxgl.accessToken = 'YOUR_MAPBOX_TOKEN';
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [106.8456, -6.2088], // Koordinat Jakarta
            zoom: 14
        });

        new mapboxgl.Marker()
            .setLngLat([106.8456, -6.2088])
            .setPopup(new mapboxgl.Popup().setHTML(
                '<h5>UKM Seragam Sekolah</h5>' +
                '<p>Jl. Raya Seragam No. 45, Jakarta Selatan</p>'
            ))
            .addTo(map);
    </script>
@endsection
