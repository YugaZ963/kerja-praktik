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
                                <p class="mb-0">{{ $mapsData['storeLocation']['address'] }}</p>
                            </div>
                        </div>

                        <div class="d-flex mb-3">
                            <i class="bi bi-telephone-fill text-primary me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-0">Telepon / WhatsApp</h6>
                                <p class="mb-0">+62 896-7775-4918</p>
                            </div>
                        </div>

                        <div class="d-flex mb-3">
                            <i class="bi bi-envelope-fill text-primary me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-0">Email</h6>
                                <p class="mb-0">ravazka963@gmail.com</p>
                            </div>
                        </div>

                        <div class="d-flex">
                            <i class="bi bi-clock-fill text-primary me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-0">Jam Operasional</h6>
                                <p class="mb-0">Senin - Jumat: 08.00 - 17.00<br>Sabtu - Minggu: 09.00 - 16.00<br></p>
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

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Subjek</label>
                        <input type="text" class="form-control" id="subject" name="subject"
                            value="{{ old('subject') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Pesan</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                </form>
            </div>
        </div>

        <!-- Map Section -->
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="mb-4">Lokasi Kami</h3>
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i>Temukan Toko Kami</h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="map" style="height: 450px; width: 100%;"></div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="mb-2"><i
                                        class="bi bi-building me-2"></i>{{ $mapsData['storeLocation']['name'] }}</h6>
                                <p class="mb-1"><i
                                        class="bi bi-geo-alt me-2"></i>{{ $mapsData['storeLocation']['address'] }}</p>
                                <p class="mb-0"><i
                                        class="bi bi-telephone me-2"></i>{{ $mapsData['storeLocation']['phone'] }}</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ $mapsData['simpleDirectionsUrl'] }}" target="_blank"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>Buka di Google Maps
                                </a>
                                <button onclick="getDirections()" class="btn btn-primary btn-sm mt-2">
                                    <i class="bi bi-signpost-2 me-1"></i>Petunjuk Arah
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('scripts')
    <script>
        let map;
        let directionsService;
        let directionsRenderer;

        function initMap() {
            // Koordinat toko dari controller
            const storeLocation = {
                lat: {{ $mapsData['storeLocation']['lat'] }},
                lng: {{ $mapsData['storeLocation']['lng'] }}
            };

            // Inisialisasi map
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: {{ $mapsData['mapSettings']['zoom'] }},
                center: storeLocation,
                mapTypeId: google.maps.MapTypeId.{{ strtoupper($mapsData['mapSettings']['map_type']) }},
                styles: [{
                    featureType: "poi.business",
                    stylers: [{
                        visibility: "on"
                    }]
                }]
            });

            // Marker untuk toko
            const storeMarker = new google.maps.Marker({
                position: storeLocation,
                map: map,
                title: "{{ $mapsData['storeLocation']['name'] }}",
                icon: {
                    url: "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(`
                         <svg width="{{ $mapsData['mapSettings']['marker_icon']['width'] }}" height="{{ $mapsData['mapSettings']['marker_icon']['height'] }}" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                             <circle cx="20" cy="20" r="18" fill="{{ $mapsData['mapSettings']['marker_icon']['color'] }}" stroke="white" stroke-width="2"/>
                             <text x="20" y="26" text-anchor="middle" fill="white" font-size="16" font-weight="bold">🏪</text>
                         </svg>
                     `),
                    scaledSize: new google.maps.Size({{ $mapsData['mapSettings']['marker_icon']['width'] }},
                        {{ $mapsData['mapSettings']['marker_icon']['height'] }})
                }
            });

            // Info window untuk marker
            const infoWindow = new google.maps.InfoWindow({
                content: `
                     <div style="padding: 10px; max-width: 300px;">
                         <h6 style="margin: 0 0 8px 0; color: #0d6efd; font-weight: bold;">
                             🏪 {{ $mapsData['storeLocation']['name'] }}
                         </h6>
                         <p style="margin: 0 0 5px 0; font-size: 14px;">
                             📍 {{ $mapsData['storeLocation']['address'] }}
                         </p>
                         <p style="margin: 0 0 5px 0; font-size: 14px;">
                             📞 {{ $mapsData['storeLocation']['phone'] }}
                         </p>
                        <p style="margin: 0 0 10px 0; font-size: 14px;">
                            🕒 Senin - Jumat: 08.00 - 17.00<br>
                            🕒 Sabtu: 09.00 - 13.00<br>
                            🕒 Minggu: Tutup
                        </p>
                        <div style="text-align: center;">
                             <a href="{{ $mapsData['simpleDirectionsUrl'] }}" target="_blank" 
                                style="background: #0d6efd; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 12px;">
                                 Buka di Google Maps
                             </a>
                         </div>
                    </div>
                `
            });

            // Event listener untuk marker
            storeMarker.addListener("click", () => {
                infoWindow.open(map, storeMarker);
            });

            // Buka info window secara default
            infoWindow.open(map, storeMarker);

            // Inisialisasi directions service
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                draggable: true,
                panel: document.getElementById("directionsPanel")
            });
            directionsRenderer.setMap(map);
        }

        function getDirections() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const userLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };

                        const storeLocation = {
                            lat: {{ $mapsData['storeLocation']['lat'] }},
                            lng: {{ $mapsData['storeLocation']['lng'] }}
                        };

                        const request = {
                            origin: userLocation,
                            destination: storeLocation,
                            travelMode: google.maps.TravelMode.DRIVING,
                            unitSystem: google.maps.UnitSystem.METRIC,
                            avoidHighways: false,
                            avoidTolls: false
                        };

                        directionsService.route(request, (result, status) => {
                            if (status === "OK") {
                                directionsRenderer.setDirections(result);

                                // Tampilkan informasi rute
                                const route = result.routes[0];
                                const leg = route.legs[0];

                                alert(`Jarak: ${leg.distance.text}\nWaktu tempuh: ${leg.duration.text}`);
                            } else {
                                alert("Tidak dapat menampilkan petunjuk arah: " + status);
                            }
                        });
                    },
                    (error) => {
                        alert("Tidak dapat mengakses lokasi Anda. Silakan aktifkan GPS dan coba lagi.");
                        // Fallback: buka Google Maps dengan koordinat toko
                        window.open("{{ $mapsData['simpleDirectionsUrl'] }}", "_blank");
                    }
                );
            } else {
                alert("Browser Anda tidak mendukung geolocation.");
                // Fallback: buka Google Maps dengan koordinat toko
                window.open("{{ $mapsData['simpleDirectionsUrl'] }}", "_blank");
            }
        }

        // Load Google Maps API
        window.initMap = initMap;
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $mapsData['apiKey'] }}&callback=initMap">
    </script>
@endsection
