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
                            
                            <!-- Metode Pembayaran -->
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran *</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card payment-option" data-payment="bri">
                                            <div class="card-body text-center">
                                                <input type="radio" name="payment_method" value="bri" id="payment_bri" 
                                                       class="form-check-input me-2" {{ old('payment_method') == 'bri' ? 'checked' : '' }} required>
                                                <label for="payment_bri" class="form-check-label">
                                                    <i class="bi bi-bank text-primary fs-2 d-block mb-2"></i>
                                                    <strong>Bank BRI</strong>
                                                    <small class="d-block text-muted">Transfer Bank</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card payment-option" data-payment="dana">
                                            <div class="card-body text-center">
                                                <input type="radio" name="payment_method" value="dana" id="payment_dana" 
                                                       class="form-check-input me-2" {{ old('payment_method') == 'dana' ? 'checked' : '' }} required>
                                                <label for="payment_dana" class="form-check-label">
                                                    <i class="bi bi-wallet2 text-success fs-2 d-block mb-2"></i>
                                                    <strong>DANA</strong>
                                                    <small class="d-block text-muted">E-Wallet</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Payment Details -->
                                <div id="payment-details" class="mt-3" style="display: none;">
                                    <div class="alert alert-info">
                                        <div id="bri-details" style="display: none;">
                                            <h6><i class="bi bi-bank"></i> Bank BRI</h6>
                                            <p class="mb-1"><strong>No. Rekening:</strong> 1234-5678-9012-3456</p>
                                            <p class="mb-0"><strong>Atas Nama:</strong> RAVAZKA STORE</p>
                                        </div>
                                        <div id="dana-details" style="display: none;">
                                            <h6><i class="bi bi-wallet2"></i> DANA E-Wallet</h6>
                                            <p class="mb-1"><strong>No. DANA:</strong> 0896-7775-4918</p>
                                            <p class="mb-0"><strong>Atas Nama:</strong> RAVAZKA STORE</p>
                                        </div>
                                    </div>
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
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <strong>Total Pembayaran</strong>
                            <strong class="text-primary">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                        

                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
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

<style>
.payment-option {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
}

.payment-option:hover {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.payment-option.selected {
    border-color: #0d6efd;
    background-color: #f8f9fa;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentOptions = document.querySelectorAll('.payment-option');
    const paymentDetails = document.getElementById('payment-details');
    const briDetails = document.getElementById('bri-details');
    const danaDetails = document.getElementById('dana-details');
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');

    // Handle payment option clicks
    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            const paymentType = this.dataset.payment;
            const radio = this.querySelector('input[type="radio"]');
            
            // Remove selected class from all options
            paymentOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Add selected class to clicked option
            this.classList.add('selected');
            
            // Check the radio button
            radio.checked = true;
            
            // Show payment details
            showPaymentDetails(paymentType);
        });
    });

    // Handle radio button changes
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                const paymentType = this.value;
                
                // Update visual selection
                paymentOptions.forEach(opt => opt.classList.remove('selected'));
                this.closest('.payment-option').classList.add('selected');
                
                // Show payment details
                showPaymentDetails(paymentType);
            }
        });
    });

    function showPaymentDetails(paymentType) {
        // Hide all details first
        briDetails.style.display = 'none';
        danaDetails.style.display = 'none';
        
        // Show selected payment details
        if (paymentType === 'bri') {
            briDetails.style.display = 'block';
        } else if (paymentType === 'dana') {
            danaDetails.style.display = 'block';
        }
        
        // Show the payment details container
        paymentDetails.style.display = 'block';
    }

    // Check if there's a pre-selected payment method (for old input)
    const checkedRadio = document.querySelector('input[name="payment_method"]:checked');
    if (checkedRadio) {
        const paymentType = checkedRadio.value;
        checkedRadio.closest('.payment-option').classList.add('selected');
        showPaymentDetails(paymentType);
    }
});
</script>

@endsection