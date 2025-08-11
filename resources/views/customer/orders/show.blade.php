@extends('layouts.customer')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<x-navbar />
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <!-- Success/Error Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Page Header -->
            <div class="page-header">
                <div class="header-content">
                    <h1 class="page-title">
                        <i class="fas fa-receipt me-3"></i>
                        Detail Pesanan
                    </h1>
                    <div class="order-number-badge">
                        #{{ $order->order_number }}
                    </div>
                </div>
                <a href="{{ route('customer.orders.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>
                    Kembali ke Daftar Pesanan
                </a>
            </div>

            <div class="row">
                <!-- Order Status Timeline -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-route me-2"></i>
                                Status Pesanan
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="status-timeline">
                                @php
                                    $statuses = [
                                        'pending' => ['icon' => 'clock', 'title' => 'Pesanan Dibuat', 'desc' => 'Pesanan berhasil dibuat dan menunggu pembayaran'],
                                        'payment_pending' => ['icon' => 'credit-card', 'title' => 'Menunggu Verifikasi', 'desc' => 'Bukti pembayaran sedang diverifikasi'],
                                        'payment_verified' => ['icon' => 'check-circle', 'title' => 'Pembayaran Terverifikasi', 'desc' => 'Pembayaran telah dikonfirmasi'],
                                        'processing' => ['icon' => 'cogs', 'title' => 'Sedang Diproses', 'desc' => 'Pesanan sedang disiapkan'],
                                        'packaged' => ['icon' => 'box', 'title' => 'Dikemas', 'desc' => 'Pesanan telah dikemas dan siap dikirim'],
                                        'shipped' => ['icon' => 'shipping-fast', 'title' => 'Dikirim', 'desc' => 'Pesanan dalam perjalanan'],
                                        'delivered' => ['icon' => 'truck', 'title' => 'Sudah Sampai', 'desc' => 'Pesanan telah sampai di tujuan'],
                                        'completed' => ['icon' => 'check-double', 'title' => 'Selesai', 'desc' => 'Pesanan telah selesai']
                                    ];
                                    
                                    $currentStatusIndex = array_search($order->status, array_keys($statuses));
                                @endphp

                                @foreach($statuses as $statusKey => $statusInfo)
                                    @php
                                        $statusIndex = array_search($statusKey, array_keys($statuses));
                                        $isActive = $statusIndex <= $currentStatusIndex;
                                        $isCurrent = $statusKey === $order->status;
                                    @endphp
                                    
                                    <div class="timeline-item {{ $isActive ? 'active' : '' }} {{ $isCurrent ? 'current' : '' }}">
                                        <div class="timeline-marker">
                                            <i class="fas fa-{{ $statusInfo['icon'] }}"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">{{ $statusInfo['title'] }}</h6>
                                            <p class="timeline-desc">{{ $statusInfo['desc'] }}</p>
                                            @if($isCurrent)
                                            <div class="timeline-date">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $order->updated_at->format('d M Y, H:i') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Item Pesanan ({{ $order->items->count() }} item)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="items-list">
                                @foreach($order->items as $item)
                                <div class="item-card">
                                    <div class="item-image">
                                        <div class="no-image">
                                            <i class="fas fa-tshirt"></i>
                                        </div>
                                    </div>
                                    <div class="item-details">
                                        <h6 class="item-name">{{ $item->product_name }}</h6>
                                        <div class="item-specs">
                                            <span class="spec-item">
                                                <i class="fas fa-ruler me-1"></i>
                                                Ukuran: {{ $item->size ?? 'N/A' }}
                                            </span>
                                            <span class="spec-item">
                                                <i class="fas fa-hashtag me-1"></i>
                                                Qty: {{ $item->quantity }}
                                            </span>
                                        </div>
                                        <div class="item-price">
                                            <span class="unit-price">@Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <div class="item-total">
                                        <div class="total-amount">
                                            Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="col-lg-4">
                    <!-- Order Summary -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-calculator me-2"></i>
                                Ringkasan Pesanan
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="summary-details">
                                <div class="summary-row">
                                    <span>Subtotal</span>
                                    <span>Rp {{ number_format($order->items->sum(function($item) { return $item->quantity * $item->price; }), 0, ',', '.') }}</span>
                                </div>
                                @if($order->shipping_cost > 0)
                                <div class="summary-row">
                                    <span>Ongkos Kirim</span>
                                    <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                <div class="summary-row total-row">
                                    <span>Total</span>
                                    <span class="total-amount">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-user me-2"></i>
                                Informasi Pelanggan
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="customer-info">
                                <div class="info-row">
                                    <i class="fas fa-user-circle me-2"></i>
                                    <div>
                                        <div class="info-label">Nama</div>
                                        <div class="info-value">{{ $order->customer_name }}</div>
                                    </div>
                                </div>
                                <div class="info-row">
                                    <i class="fas fa-envelope me-2"></i>
                                    <div>
                                        <div class="info-label">Email</div>
                                        <div class="info-value">{{ $order->customer_email }}</div>
                                    </div>
                                </div>
                                <div class="info-row">
                                    <i class="fas fa-phone me-2"></i>
                                    <div>
                                        <div class="info-label">Telepon</div>
                                        <div class="info-value">{{ $order->customer_phone }}</div>
                                    </div>
                                </div>
                                <div class="info-row">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    <div>
                                        <div class="info-label">Alamat</div>
                                        <div class="info-value">{{ $order->customer_address }}</div>
                                    </div>
                                </div>
                                @if($order->tracking_number)
                                <div class="info-row">
                                    <i class="fas fa-truck me-2"></i>
                                    <div>
                                        <div class="info-label">Nomor Resi</div>
                                        <div class="info-value">
                                            <span class="badge bg-info">{{ $order->tracking_number }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-credit-card me-2"></i>
                                Informasi Pembayaran
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="payment-info">
                                <div class="payment-method">
                                    <i class="fas fa-{{ $order->payment_method === 'bri' ? 'university' : 'wallet' }} me-2"></i>
                                    {{ $order->getPaymentMethodLabel() }}
                                </div>
                                
                                @if($order->payment_proof)
                                <div class="payment-proof mt-3">
                                    <div class="proof-label">Bukti Pembayaran:</div>
                                    <div class="proof-image">
                                        <img src="{{ asset('storage/' . $order->payment_proof) }}" 
                                             alt="Bukti Pembayaran" class="img-fluid rounded">
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="action-buttons">
                                @if(in_array($order->status, ['pending', 'payment_pending']))
                                <button type="button" class="btn btn-warning w-100 mb-3" 
                                        data-bs-toggle="modal" data-bs-target="#paymentModal">
                                    <i class="fas fa-upload me-2"></i>
                                    @if($order->status === 'pending')
                                        Upload Bukti Bayar
                                    @else
                                        Upload Ulang Bukti Bayar
                                    @endif
                                </button>
                                @endif
                                
                                @if(in_array($order->status, ['shipped', 'delivered']))
                                <a href="{{ route('customer.orders.track') }}?order_number={{ $order->order_number }}" 
                                   class="btn btn-info w-100 mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    Lacak Pesanan
                                </a>
                                @endif
                                
                                <button type="button" class="btn btn-outline-primary w-100" onclick="window.print()">
                                    <i class="fas fa-print me-2"></i>
                                    Cetak Detail
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial Form for Completed Orders -->
                    @if($order->status === 'completed')
                    @php
                        $existingTestimonial = \App\Models\Testimonial::where('order_id', $order->id)->first();
                    @endphp
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-star me-2"></i>
                                @if($existingTestimonial)
                                    Testimoni Anda
                                @else
                                    Berikan Testimoni
                                @endif
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($existingTestimonial)
                                <!-- Display existing testimonial -->
                                <div class="testimonial-display">
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Terima kasih telah memberikan testimoni!
                                    </div>
                                    <div class="testimonial-content">
                                        <p class="testimonial-text">{{ $existingTestimonial->testimonial_text }}</p>
                                        <div class="testimonial-meta">
                                            <small class="text-muted">
                                                Dikirim pada {{ $existingTestimonial->created_at->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Testimonial form -->
                                <form action="{{ route('customer.testimonials.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">

                                    <div class="mb-3">
                                        <label for="customer_name" class="form-label">
                                            <i class="fas fa-user me-2"></i>
                                            Nama
                                        </label>
                                        <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                               value="{{ auth()->user()->name }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="testimonial_text" class="form-label">
                                            <i class="fas fa-comment me-2"></i>
                                            Testimoni
                                        </label>
                                        <textarea class="form-control" id="testimonial_text" name="testimonial_text" 
                                                  rows="4" placeholder="Bagikan pengalaman Anda dengan produk dan layanan kami..."></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Kirim Testimoni
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Upload Modal -->
@if(in_array($order->status, ['pending', 'payment_pending']))
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-upload me-2"></i>
                    @if($order->status === 'pending')
                        Upload Bukti Pembayaran
                    @else
                        Upload Ulang Bukti Pembayaran
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('customer.orders.upload-payment', $order) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="payment-info-modal mb-4">
                        <h6>Informasi Pembayaran</h6>
                        <div class="payment-details">
                            <p><strong>Nomor Pesanan:</strong> {{ $order->order_number }}</p>
                            <p><strong>Total Pembayaran:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            <p><strong>Metode Pembayaran:</strong> {{ $order->getPaymentMethodLabel() }}</p>
                            @if($order->status === 'payment_pending')
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Status:</strong> Pesanan Anda sedang menunggu verifikasi pembayaran. 
                                Anda dapat mengunggah ulang bukti pembayaran jika diperlukan.
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="payment_proof" class="form-label">
                            <i class="fas fa-image me-2"></i>
                            Bukti Pembayaran
                        </label>
                        <input type="file" class="form-control" id="payment_proof" name="payment_proof" 
                               accept="image/*" required>
                        <div class="form-text">
                            Format yang didukung: JPG, PNG, JPEG. Maksimal 2MB.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>
                        @if($order->status === 'pending')
                            Upload Bukti
                        @else
                            Upload Ulang Bukti
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    /* Ravazka Color Palette */
    :root {
        --primary-color: #0d6efd;
        --secondary-color: #6b7280;
        --success-color: #0d9d17;
        --warning-color: #fbdd15;
        --danger-color: #ca2068;
        --info-color: #0d6efd;
        --purple-color: #ca2068;
        --light-bg: #f8fafc;
        --white: #ffffff;
        --text-dark: #1f2937;
        --text-muted: #6b7280;
        --border-color: #e5e7eb;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--purple-color) 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: var(--shadow-lg);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-content {
        flex: 1;
    }

    .page-title {
        font-size: 1.875rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .order-number-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 1.1rem;
        font-weight: 600;
        margin-top: 0.5rem;
        display: inline-block;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-2px);
    }

    /* Card Styling */
    .card {
        border: none;
        border-radius: 16px;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--purple-color) 100%);
        color: white;
        padding: 1.5rem 2rem;
        border: none;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
    }

    /* Status Timeline */
    .status-timeline {
        padding: 1rem 0;
    }

    .timeline-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 2rem;
        position: relative;
        opacity: 0.4;
        transition: all 0.3s ease;
    }

    .timeline-item.active {
        opacity: 1;
    }

    .timeline-item.current {
        opacity: 1;
        transform: scale(1.02);
    }

    .timeline-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 19px;
        top: 40px;
        width: 2px;
        height: calc(100% + 1rem);
        background: var(--border-color);
    }

    .timeline-item.active:not(:last-child)::after {
        background: var(--success-color);
    }

    .timeline-marker {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
        color: white;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .timeline-item.active .timeline-marker {
        background: var(--success-color);
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
    }

    .timeline-item.current .timeline-marker {
        background: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.2);
        animation: pulse 2s infinite;
    }

    .timeline-content {
        flex: 1;
        padding-top: 0.25rem;
    }

    .timeline-title {
        font-weight: 600;
        color: var(--text-dark);
        margin: 0 0 0.25rem 0;
    }

    .timeline-desc {
        color: var(--text-muted);
        margin: 0 0 0.5rem 0;
        font-size: 0.9rem;
    }

    .timeline-date {
        color: var(--primary-color);
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Items List */
    .items-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .item-card {
        background: var(--light-bg);
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .item-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .item-image {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-image {
        width: 100%;
        height: 100%;
        background: var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        font-size: 1.5rem;
    }

    .item-details {
        flex: 1;
    }

    .item-name {
        font-weight: 600;
        color: var(--text-dark);
        margin: 0 0 0.5rem 0;
    }

    .item-specs {
        display: flex;
        gap: 1rem;
        margin-bottom: 0.5rem;
    }

    .spec-item {
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    .item-price {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .unit-price {
        font-weight: 600;
    }

    .item-total {
        text-align: right;
    }

    .total-amount {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--success-color);
    }

    /* Summary Details */
    .summary-details {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
    }

    .summary-row:not(.total-row) {
        border-bottom: 1px solid var(--border-color);
    }

    .total-row {
        border-top: 2px solid var(--primary-color);
        padding-top: 1rem;
        margin-top: 0.5rem;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .total-row .total-amount {
        color: var(--success-color);
        font-size: 1.25rem;
    }

    /* Customer Info */
    .customer-info {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .info-row i {
        color: var(--primary-color);
        margin-top: 0.25rem;
        flex-shrink: 0;
    }

    .info-label {
        font-size: 0.875rem;
        color: var(--text-muted);
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-weight: 600;
        color: var(--text-dark);
    }

    /* Payment Info */
    .payment-method {
        display: flex;
        align-items: center;
        font-weight: 600;
        color: var(--primary-color);
        font-size: 1.1rem;
    }

    .proof-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .proof-image {
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid var(--border-color);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        flex-direction: column;
    }

    /* Testimonial Styling */
    .testimonial-display {
        background: var(--light-bg);
        border-radius: 12px;
        padding: 1.5rem;
    }

    .testimonial-content {
        margin-top: 1rem;
    }

    .testimonial-text {
        font-style: italic;
        color: var(--text-dark);
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .testimonial-meta {
        border-top: 1px solid var(--border-color);
        padding-top: 0.75rem;
    }



    /* Modal Styling */
    .payment-info-modal {
        background: var(--light-bg);
        border-radius: 8px;
        padding: 1rem;
    }

    .payment-details p {
        margin-bottom: 0.5rem;
    }

    /* Animations */
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            text-align: left;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .btn-back {
            align-self: stretch;
            text-align: center;
        }

        .item-card {
            flex-direction: column;
            text-align: center;
        }

        .item-specs {
            justify-content: center;
        }

        .summary-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }

        .info-row {
            flex-direction: column;
            gap: 0.25rem;
        }
    }

    /* Print Styles */
    @media print {
        .page-header,
        .btn-back,
        .action-buttons {
            display: none !important;
        }
        
        .card {
            box-shadow: none !important;
            border: 1px solid var(--border-color) !important;
        }
    }
</style>
@endpush