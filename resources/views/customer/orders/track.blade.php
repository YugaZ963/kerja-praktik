@extends('layouts.customer')

@section('title', 'Lacak Pesanan')

@section('content')
<x-navbar />
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-search-location me-3"></i>
                    Lacak Pesanan
                </h1>
                <p class="page-subtitle">Masukkan nomor pesanan untuk melacak status pesanan Anda</p>
            </div>

            <!-- Tracking Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-search me-2"></i>
                        Cari Pesanan
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.orders.track') }}" method="GET" class="tracking-form">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="order_number" class="form-label">
                                    <i class="fas fa-receipt me-2"></i>
                                    Nomor Pesanan
                                </label>
                                <input type="text" class="form-control" id="order_number" name="order_number" 
                                       value="{{ request('order_number') }}" placeholder="Contoh: RVZ240611001" required>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-2"></i>
                                Lacak Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(isset($order))
            <!-- Order Found -->
            <div class="card mt-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-package me-2"></i>
                            Hasil Pencarian
                        </h5>
                        <span class="order-number-badge">#{{ $order->order_number }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Order Basic Info -->
                    <div class="order-basic-info">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="fas fa-calendar me-2"></i>
                                    <div>
                                        <div class="info-label">Tanggal Pesanan</div>
                                        <div class="info-value">{{ $order->created_at->format('d M Y, H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <i class="fas fa-money-bill-wave me-2"></i>
                                    <div>
                                        <div class="info-label">Total Pembayaran</div>
                                        <div class="info-value text-success fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($order->tracking_number)
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="info-item">
                                    <i class="fas fa-truck me-2"></i>
                                    <div>
                                        <div class="info-label">Nomor Resi</div>
                                        <div class="info-value">
                                            <span class="badge bg-info fs-6">{{ $order->tracking_number }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Current Status -->
                    <div class="current-status">
                        <h6 class="status-title">
                            <i class="fas fa-info-circle me-2"></i>
                            Status Saat Ini
                        </h6>
                        @php
                            $statusConfig = match($order->status) {
                                'pending' => ['class' => 'warning', 'icon' => 'clock', 'text' => 'Menunggu Pembayaran'],
                                'payment_pending' => ['class' => 'info', 'icon' => 'credit-card', 'text' => 'Menunggu Verifikasi Pembayaran'],
                                'payment_verified' => ['class' => 'success', 'icon' => 'check-circle', 'text' => 'Pembayaran Terverifikasi'],
                                'processing' => ['class' => 'primary', 'icon' => 'cogs', 'text' => 'Sedang Diproses'],
                                'packaged' => ['class' => 'purple', 'icon' => 'box', 'text' => 'Sedang Dikemas'],
                                'shipped' => ['class' => 'info', 'icon' => 'shipping-fast', 'text' => 'Sedang Dikirim'],
                                'delivered' => ['class' => 'success', 'icon' => 'truck', 'text' => 'Sudah Sampai'],
                                'completed' => ['class' => 'success', 'icon' => 'check-double', 'text' => 'Pesanan Selesai'],
                                'cancelled' => ['class' => 'danger', 'icon' => 'times-circle', 'text' => 'Pesanan Dibatalkan'],
                                default => ['class' => 'secondary', 'icon' => 'question', 'text' => 'Status Tidak Diketahui']
                            };
                        @endphp
                        <div class="status-badge-large bg-{{ $statusConfig['class'] }}">
                            <i class="fas fa-{{ $statusConfig['icon'] }} me-2"></i>
                            {{ $statusConfig['text'] }}
                        </div>
                        <div class="status-date">
                            Terakhir diperbarui: {{ $order->updated_at->format('d M Y, H:i') }}
                        </div>
                    </div>

                    <!-- Status Timeline -->
                    <div class="status-timeline-section">
                        <h6 class="timeline-title">
                            <i class="fas fa-route me-2"></i>
                            Riwayat Status Pesanan
                        </h6>
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

                    <!-- Order Items Summary -->
                    <div class="order-items-summary">
                        <h6 class="items-title">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Item Pesanan ({{ $order->items->count() }} item)
                        </h6>
                        <div class="items-preview">
                            @foreach($order->items->take(3) as $item)
                            <div class="item-preview">
                                <div class="item-name">{{ $item->product_name }}</div>
                                <div class="item-details">
                                    {{ $item->quantity }}x | Ukuran: {{ $item->product_size ?? 'N/A' }}
                                </div>
                            </div>
                            @endforeach
                            
                            @if($order->items->count() > 3)
                            <div class="more-items">
                                <small class="text-muted">
                                    <i class="fas fa-plus me-1"></i>
                                    {{ $order->items->count() - 3 }} item lainnya
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        @auth
                        <a href="{{ route('customer.orders.show', $order->order_number) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-eye me-2"></i>
                            Lihat Detail Lengkap
                        </a>
                        @endauth
                        
                        <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>
                            Cetak Status
                        </button>
                    </div>
                </div>
            </div>
            @elseif(request()->has('order_number'))
            <!-- Order Not Found -->
            <div class="card mt-4">
                <div class="card-body text-center">
                    <div class="not-found-state">
                        <i class="fas fa-search-minus"></i>
                        <h5 class="mt-3 mb-2">Pesanan Tidak Ditemukan</h5>
                        <p class="mb-4">
                            Pesanan dengan nomor <strong>{{ request('order_number') }}</strong> 
                            tidak ditemukan atau bukan milik Anda.
                        </p>
                        <div class="suggestions">
                            <h6>Pastikan:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check me-2 text-success"></i>Nomor pesanan sudah benar</li>
                                <li><i class="fas fa-check me-2 text-success"></i>Pesanan tersebut milik akun Anda</li>
                                <li><i class="fas fa-check me-2 text-success"></i>Tidak ada spasi atau karakter tambahan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Help Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-question-circle me-2"></i>
                        Butuh Bantuan?
                    </h5>
                </div>
                <div class="card-body">
                    <div class="help-content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="help-item">
                                    <i class="fas fa-phone text-primary me-3"></i>
                                    <div>
                                        <h6>Hubungi Customer Service</h6>
                                        <p class="mb-0">+62 123 456 7890</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="help-item">
                                    <i class="fas fa-envelope text-primary me-3"></i>
                                    <div>
                                        <h6>Email Support</h6>
                                        <p class="mb-0">support@example.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
        text-align: center;
    }

    .page-title {
        font-size: 1.875rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .page-subtitle {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
        font-size: 1rem;
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

    /* Tracking Form */
    .tracking-form {
        padding: 1rem 0;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 2px solid var(--border-color);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* Order Number Badge */
    .order-number-badge {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--purple-color) 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    /* Order Basic Info */
    .order-basic-info {
        background: var(--light-bg);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-item i {
        color: var(--primary-color);
        font-size: 1.25rem;
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
        font-size: 1rem;
    }

    /* Current Status */
    .current-status {
        background: var(--light-bg);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        text-align: center;
    }

    .status-title {
        color: var(--text-dark);
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .status-badge-large {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 1rem 2rem;
        border-radius: 25px;
        font-size: 1.1rem;
        font-weight: 700;
        color: white;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .bg-primary { background: linear-gradient(135deg, var(--primary-color) 0%, #3730a3 100%) !important; }
    .bg-info { background: linear-gradient(135deg, var(--info-color) 0%, #1d4ed8 100%) !important; }
    .bg-success { background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%) !important; }
    .bg-warning { background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%) !important; }
    .bg-danger { background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%) !important; }
    .bg-purple { background: linear-gradient(135deg, var(--purple-color) 0%, #7c3aed 100%) !important; }
    .bg-secondary { background: linear-gradient(135deg, var(--secondary-color) 0%, #4b5563 100%) !important; }

    .status-date {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    /* Status Timeline */
    .status-timeline-section {
        margin-bottom: 2rem;
    }

    .timeline-title {
        color: var(--text-dark);
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

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

    .timeline-content .timeline-title {
        font-weight: 600;
        color: var(--text-dark);
        margin: 0 0 0.25rem 0;
        font-size: 1rem;
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

    /* Order Items Summary */
    .order-items-summary {
        background: var(--light-bg);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .items-title {
        color: var(--text-dark);
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .items-preview {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .item-preview {
        background: var(--white);
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid var(--border-color);
    }

    .item-name {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }

    .item-details {
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    .more-items {
        text-align: center;
        padding: 0.5rem;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    /* Not Found State */
    .not-found-state {
        padding: 2rem;
        color: var(--text-muted);
    }

    .not-found-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .suggestions {
        background: var(--light-bg);
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
        text-align: left;
        display: inline-block;
    }

    .suggestions h6 {
        color: var(--text-dark);
        margin-bottom: 0.75rem;
    }

    .suggestions ul li {
        margin-bottom: 0.5rem;
        color: var(--text-muted);
    }

    /* Help Section */
    .help-content {
        padding: 1rem 0;
    }

    .help-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .help-item:last-child {
        margin-bottom: 0;
    }

    .help-item h6 {
        color: var(--text-dark);
        margin: 0 0 0.25rem 0;
        font-weight: 600;
    }

    .help-item p {
        color: var(--text-muted);
        margin: 0;
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
        .page-title {
            font-size: 1.5rem;
            flex-direction: column;
            gap: 0.5rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .help-item {
            flex-direction: column;
            text-align: center;
            gap: 0.5rem;
        }

        .order-basic-info .row {
            text-align: center;
        }

        .info-item {
            justify-content: center;
        }
    }

    /* Print Styles */
    @media print {
        .page-header,
        .tracking-form,
        .action-buttons,
        .help-content {
            display: none !important;
        }
        
        .card {
            box-shadow: none !important;
            border: 1px solid var(--border-color) !important;
        }
    }
</style>
@endpush