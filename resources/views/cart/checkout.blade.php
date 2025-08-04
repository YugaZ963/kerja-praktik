@extends('layouts.customer')

@section('title', 'Checkout')

@section('content')
    <div class="container mt-4">
        <x-navbar />

        <!-- Hero Section -->
        <div class="bg-light p-4 rounded mb-4 text-center">
            <h1 class="h3 fw-bold text-primary">Checkout</h1>
            <p class="mb-0">Lengkapi data untuk menyelesaikan pesanan</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Data Pelanggan</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('cart.process-order') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama Lengkap *</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ old('name') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Nomor WhatsApp *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat Lengkap *</label>
                                <textarea class="form-control" id="address" name="address" rows="3" 
                                          placeholder="Jalan, RT/RW, Kelurahan, Kecamatan" required>{{ old('address') }}</textarea>
                            </div>
                            
                            <!-- Shipping Information -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="province" class="form-label">Provinsi *</label>
                                    <select class="form-select" id="province" name="province_id" required>
                                        <option value="">Pilih Provinsi</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province['province_id'] }}">{{ $province['province'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">Kota/Kabupaten *</label>
                                    <select class="form-select" id="city" name="city_id" required disabled>
                                        <option value="">Pilih Kota/Kabupaten</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="courier" class="form-label">Kurir *</label>
                                    <select class="form-select" id="courier" name="courier" required disabled>
                                        <option value="">Pilih Kurir</option>
                                        <option value="jne">JNE</option>
                                        <option value="jnt">JNT</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_service" class="form-label">Layanan Pengiriman *</label>
                                    <select class="form-select" id="shipping_service" name="shipping_service" required disabled>
                                        <option value="">Pilih Layanan</option>
                                    </select>
                                    <input type="hidden" id="shipping_cost" name="shipping_cost" value="0">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">Catatan Tambahan</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2" 
                                          placeholder="Catatan khusus untuk pesanan (opsional)">{{ old('notes') }}</textarea>
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                <strong>Informasi Penting:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Setelah checkout, Anda akan diarahkan ke WhatsApp untuk konfirmasi pesanan</li>
                                    <li>Tim kami akan menghubungi Anda untuk konfirmasi dan pembayaran</li>
                                    <li>Pastikan nomor WhatsApp yang dimasukkan aktif</li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali ke Keranjang
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-whatsapp"></i> Kirim Pesanan via WhatsApp
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        @foreach($cartItems as $item)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $item->product->name }}</h6>
                                    <small class="text-muted">{{ $item->product->size }} × {{ $item->quantity }}</small>
                                </div>
                                <span class="fw-bold">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span id="subtotal-amount">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Ongkos Kirim</span>
                            <span id="shipping-amount" class="text-muted">Pilih kurir</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <strong>Total Pembayaran</strong>
                            <strong id="total-amount" class="text-primary">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h6><i class="bi bi-truck text-primary"></i> Pengiriman</h6>
                        <small class="text-muted">JNE & JNT tersedia ke seluruh Indonesia</small>
                        <hr>
                        <h6><i class="bi bi-credit-card text-success"></i> Pembayaran</h6>
                        <small class="text-muted">Transfer Bank / E-Wallet / COD</small>
                        <hr>
                        <h6><i class="bi bi-headset text-info"></i> Customer Service</h6>
                        <small class="text-muted">Siap membantu 24/7 via WhatsApp</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 mb-0">Menghitung ongkos kirim...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const subtotalAmount = {{ $total }};
        let currentShippingCost = 0;

        // Handle province change
        document.getElementById('province').addEventListener('change', function() {
            const provinceId = this.value;
            const citySelect = document.getElementById('city');
            const courierSelect = document.getElementById('courier');
            const shippingServiceSelect = document.getElementById('shipping_service');
            
            // Reset dependent selects
            citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
            citySelect.disabled = !provinceId;
            courierSelect.disabled = true;
            shippingServiceSelect.disabled = true;
            courierSelect.value = '';
            shippingServiceSelect.value = '';
            
            // Reset shipping cost
            updateShippingCost(0, '');
            
            if (provinceId) {
                fetch(`{{ route('cart.api.cities') }}?province_id=${provinceId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.city_id;
                            option.textContent = `${city.type} ${city.city_name}`;
                            citySelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching cities:', error);
                        alert('Gagal memuat data kota. Silakan coba lagi.');
                    });
            }
        });

        // Handle city change
        document.getElementById('city').addEventListener('change', function() {
            const cityId = this.value;
            const courierSelect = document.getElementById('courier');
            const shippingServiceSelect = document.getElementById('shipping_service');
            
            courierSelect.disabled = !cityId;
            shippingServiceSelect.disabled = true;
            courierSelect.value = '';
            shippingServiceSelect.value = '';
            
            // Reset shipping cost
            updateShippingCost(0, '');
        });

        // Handle courier change
        document.getElementById('courier').addEventListener('change', function() {
            const courier = this.value;
            const cityId = document.getElementById('city').value;
            const shippingServiceSelect = document.getElementById('shipping_service');
            
            shippingServiceSelect.innerHTML = '<option value="">Pilih Layanan</option>';
            shippingServiceSelect.disabled = true;
            
            // Reset shipping cost
            updateShippingCost(0, '');
            
            if (courier && cityId) {
                // Show loading modal
                const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
                loadingModal.show();
                
                fetch('{{ route('cart.api.shipping-cost') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        destination: cityId,
                        courier: courier
                    })
                })
                .then(response => response.json())
                .then(data => {
                    loadingModal.hide();
                    
                    if (data.shipping_options && data.shipping_options.length > 0) {
                        data.shipping_options.forEach(option => {
                            const optionElement = document.createElement('option');
                            optionElement.value = `${option.service}|${option.cost}`;
                            optionElement.textContent = `${option.service} - Rp ${option.cost.toLocaleString('id-ID')} (${option.etd})`;
                            shippingServiceSelect.appendChild(optionElement);
                        });
                        shippingServiceSelect.disabled = false;
                    } else {
                        alert('Tidak ada layanan pengiriman tersedia untuk tujuan ini.');
                    }
                })
                .catch(error => {
                    loadingModal.hide();
                    console.error('Error fetching shipping cost:', error);
                    alert('Gagal menghitung ongkos kirim. Silakan coba lagi.');
                });
            }
        });

        // Handle shipping service change
        document.getElementById('shipping_service').addEventListener('change', function() {
            const value = this.value;
            if (value) {
                const [service, cost] = value.split('|');
                updateShippingCost(parseInt(cost), service);
            } else {
                updateShippingCost(0, '');
            }
        });

        function updateShippingCost(cost, service) {
            currentShippingCost = cost;
            const shippingAmountElement = document.getElementById('shipping-amount');
            const totalAmountElement = document.getElementById('total-amount');
            const shippingCostInput = document.getElementById('shipping_cost');
            
            if (cost > 0) {
                shippingAmountElement.textContent = `Rp ${cost.toLocaleString('id-ID')}`;
                shippingAmountElement.className = 'text-success';
            } else {
                shippingAmountElement.textContent = 'Pilih kurir';
                shippingAmountElement.className = 'text-muted';
            }
            
            const total = subtotalAmount + cost;
            totalAmountElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
            shippingCostInput.value = cost;
        }

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const shippingCost = document.getElementById('shipping_cost').value;
            if (!shippingCost || shippingCost === '0') {
                e.preventDefault();
                alert('Silakan pilih layanan pengiriman terlebih dahulu.');
                return false;
            }
        });
    </script>
@endsection