@extends('layouts.customer')

@section('title', 'Pesanan Saya')

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
                <h1 class="page-title">
                    <i class="fas fa-shopping-bag me-3"></i>
                    Pesanan Saya
                </h1>
                <p class="page-subtitle">Pantau status pesanan dan riwayat pembelian Anda</p>
            </div>

            <!-- Status Filter Tabs -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-filter me-2"></i>
                        Filter Status Pesanan
                    </h5>
                </div>
                <div class="status-filters">
                    <div class="status-grid">
                        <a href="{{ route('customer.orders.index', ['status' => 'all']) }}" 
                           class="status-tab {{ $status === 'all' ? 'active' : '' }}">
                            <div class="status-icon bg-primary">
                                <i class="fas fa-list"></i>
                            </div>
                            <div class="status-info">
                                <h6>Semua</h6>
                                <div class="status-count">{{ $statusCounts['all'] }}</div>
                            </div>
                        </a>

                        <a href="{{ route('customer.orders.index', ['status' => 'pending']) }}" 
                           class="status-tab {{ $status === 'pending' ? 'active' : '' }}">
                            <div class="status-icon bg-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="status-info">
                                <h6>Menunggu</h6>
                                <div class="status-count">{{ $statusCounts['pending'] }}</div>
                            </div>
                        </a>

                        <a href="{{ route('customer.orders.index', ['status' => 'payment_pending']) }}" 
                           class="status-tab {{ $status === 'payment_pending' ? 'active' : '' }}">
                            <div class="status-icon bg-info">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="status-info">
                                <h6>Menunggu Verifikasi</h6>
                                <div class="status-count">{{ $statusCounts['payment_pending'] }}</div>
                            </div>
                        </a>

                        <a href="{{ route('customer.orders.index', ['status' => 'payment_verified']) }}" 
                           class="status-tab {{ $status === 'payment_verified' ? 'active' : '' }}">
                            <div class="status-icon bg-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="status-info">
                                <h6>Pembayaran Terverifikasi</h6>
                                <div class="status-count">{{ $statusCounts['payment_verified'] }}</div>
                            </div>
                        </a>

                        <a href="{{ route('customer.orders.index', ['status' => 'processing']) }}" 
                           class="status-tab {{ $status === 'processing' ? 'active' : '' }}">
                            <div class="status-icon bg-primary">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div class="status-info">
                                <h6>Diproses</h6>
                                <div class="status-count">{{ $statusCounts['processing'] }}</div>
                            </div>
                        </a>

                        <a href="{{ route('customer.orders.index', ['status' => 'packaged']) }}" 
                           class="status-tab {{ $status === 'packaged' ? 'active' : '' }}">
                            <div class="status-icon bg-purple">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="status-info">
                                <h6>Dikemas</h6>
                                <div class="status-count">{{ $statusCounts['packaged'] }}</div>
                            </div>
                        </a>

                        <a href="{{ route('customer.orders.index', ['status' => 'shipped']) }}" 
                           class="status-tab {{ $status === 'shipped' ? 'active' : '' }}">
                            <div class="status-icon bg-info">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div class="status-info">
                                <h6>Dikirim</h6>
                                <div class="status-count">{{ $statusCounts['shipped'] }}</div>
                            </div>
                        </a>

                        <a href="{{ route('customer.orders.index', ['status' => 'delivered']) }}" 
                           class="status-tab {{ $status === 'delivered' ? 'active' : '' }}">
                            <div class="status-icon bg-success">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="status-info">
                                <h6>Sudah Sampai</h6>
                                <div class="status-count">{{ $statusCounts['delivered'] }}</div>
                            </div>
                        </a>

                        <a href="{{ route('customer.orders.index', ['status' => 'completed']) }}" 
                           class="status-tab {{ $status === 'completed' ? 'active' : '' }}">
                            <div class="status-icon bg-success">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <div class="status-info">
                                <h6>Selesai</h6>
                                <div class="status-count">{{ $statusCounts['completed'] }}</div>
                            </div>
                        </a>

                        <a href="{{ route('customer.orders.index', ['status' => 'cancelled']) }}" 
                           class="status-tab {{ $status === 'cancelled' ? 'active' : '' }}">
                            <div class="status-icon bg-danger">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="status-info">
                                <h6>Dibatalkan</h6>
                                <div class="status-count">{{ $statusCounts['cancelled'] }}</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Orders List -->
                <div class="orders-container">
                    @forelse($orders as $order)
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <div class="order-number">
                                    <i class="fas fa-receipt me-2"></i>
                                    <span class="fw-bold">{{ $order->order_number }}</span>
                                </div>
                                <div class="order-date">
                                    <i class="fas fa-calendar me-2"></i>
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                            <div class="order-status">
                                @php
                                    $statusConfig = match($order->status) {
                                        'pending' => ['class' => 'warning', 'icon' => 'clock', 'text' => 'Menunggu'],
                                        'payment_pending' => ['class' => 'info', 'icon' => 'credit-card', 'text' => 'Menunggu Verifikasi'],
                                        'payment_verified' => ['class' => 'success', 'icon' => 'check-circle', 'text' => 'Pembayaran Terverifikasi'],
                                        'processing' => ['class' => 'primary', 'icon' => 'cogs', 'text' => 'Diproses'],
                                        'packaged' => ['class' => 'purple', 'icon' => 'box', 'text' => 'Dikemas'],
                                        'shipped' => ['class' => 'info', 'icon' => 'shipping-fast', 'text' => 'Dikirim'],
                                        'delivered' => ['class' => 'success', 'icon' => 'truck', 'text' => 'Sudah Sampai'],
                                        'completed' => ['class' => 'success', 'icon' => 'check-double', 'text' => 'Selesai'],
                                        'cancelled' => ['class' => 'danger', 'icon' => 'times-circle', 'text' => 'Dibatalkan'],
                                        default => ['class' => 'secondary', 'icon' => 'question', 'text' => 'Unknown']
                                    };
                                @endphp
                                <span class="status-badge bg-{{ $statusConfig['class'] }}">
                                    <i class="fas fa-{{ $statusConfig['icon'] }} me-1"></i>
                                    {{ $statusConfig['text'] }}
                                </span>
                            </div>
                        </div>

                        <div class="order-body">
                            <div class="order-items">
                                <h6 class="mb-3">
                                    <i class="fas fa-shopping-cart me-2"></i>
                                    Item Pesanan ({{ $order->items->count() }} item)
                                </h6>
                                <div class="items-list">
                                    @foreach($order->items->take(3) as $item)
                                    <div class="item-row">
                                        <div class="item-info">
                                            <div class="item-name">{{ $item->product_name }}</div>
                                            <div class="item-details">
                                                Ukuran: {{ $item->product_size ?? 'N/A' }} | 
                                                Qty: {{ $item->quantity }} | 
                                                @Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="item-total">
                                            Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
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

                            <div class="order-summary">
                                <div class="summary-row">
                                    <span>Total Pesanan:</span>
                                    <span class="fw-bold text-success fs-5">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="summary-row">
                                    <span>Metode Pembayaran:</span>
                                    <span class="payment-method">
                                        <i class="fas fa-{{ $order->payment_method === 'bri' ? 'university' : 'wallet' }} me-1"></i>
                                        {{ $order->getPaymentMethodLabel() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="order-footer">
                            <div class="order-actions">
                                <a href="{{ route('customer.orders.show', $order->order_number) }}" 
                                   class="btn-action btn-primary">
                                    <i class="fas fa-eye me-2"></i>
                                    Detail Pesanan
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Upload Modal -->
                    @if(in_array($order->status, ['pending', 'payment_pending']))
                    <div class="modal fade" id="paymentModal{{ $order->id }}" tabindex="-1">
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
                                        <div class="payment-info mb-4">
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



                    <!-- Delivered Proof Upload Modal -->
                    @if($order->status === 'delivered')
                    <div class="modal fade" id="deliveredModal{{ $order->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-camera me-2"></i>
                                        Upload Foto Bukti Barang Sudah Sampai
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('customer.orders.upload-delivery', $order) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="delivery-info mb-4">
                                            <h6>Informasi Pesanan</h6>
                                            <div class="delivery-details">
                                                <p><strong>Nomor Pesanan:</strong> {{ $order->order_number }}</p>
                                                <p><strong>Status:</strong> <span class="badge bg-success">Sudah Sampai</span></p>
                                                <div class="alert alert-success mt-3">
                                                    <i class="fas fa-check-circle me-2"></i>
                                                    <strong>Konfirmasi:</strong> Upload foto barang yang sudah Anda terima sebagai bukti bahwa pesanan telah sampai dengan baik dan sesuai pesanan.
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="delivery_proof_delivered" class="form-label">
                                                <i class="fas fa-image me-2"></i>
                                                Foto Bukti Barang Sudah Sampai
                                            </label>
                                            <input type="file" class="form-control" id="delivery_proof_delivered" name="delivery_proof" 
                                                   accept="image/*" required>
                                            <div class="form-text">
                                                Format yang didukung: JPG, PNG, JPEG. Maksimal 2MB.
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="delivery_notes_delivered" class="form-label">
                                                <i class="fas fa-comment me-2"></i>
                                                Catatan (Opsional)
                                            </label>
                                            <textarea class="form-control" id="delivery_notes_delivered" name="delivery_notes" rows="3" 
                                                      placeholder="Tambahkan catatan tentang kondisi barang atau pengalaman pengiriman..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-camera me-2"></i>
                                            Upload Foto Bukti
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-shopping-bag"></i>
                        <h5 class="mt-3 mb-2">Belum Ada Pesanan</h5>
                        <p class="mb-4">Anda belum memiliki pesanan. Mulai berbelanja sekarang!</p>
                        <a href="{{ route('customer.products') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Mulai Belanja
                        </a>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                <div class="pagination-wrapper">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
                @endif
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
        --success-color: #0d6efd;
        --warning-color: #fcdf10;
        --danger-color: #cb2368;
        --info-color: #0d6efd;
        --purple-color: #cb2368;
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
    }

    .page-title {
        font-size: 1.875rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
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

    /* Status Filter Tabs */
    .status-filters {
        padding: 1.5rem 2rem 0;
        background: var(--white);
    }

    .status-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .status-tab {
        background: var(--white);
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 1rem;
        text-decoration: none;
        color: var(--text-dark);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .status-tab:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        color: var(--text-dark);
    }

    .status-tab.active {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
        color: var(--primary-color);
    }

    .status-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.125rem;
        flex-shrink: 0;
        color: white;
    }

    .status-info h6 {
        margin: 0;
        font-weight: 600;
        font-size: 0.875rem;
        color: inherit;
    }

    .status-count {
        font-size: 1.25rem;
        font-weight: 700;
        color: inherit;
    }

    /* Orders Container */
    .orders-container {
        padding: 0 2rem 2rem;
    }

    .order-card {
        background: var(--white);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .order-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .order-header {
        background: var(--light-bg);
        padding: 1.25rem;
        display: flex;
        justify-content: between;
        align-items: center;
        border-bottom: 1px solid var(--border-color);
    }

    .order-info {
        flex: 1;
    }

    .order-number {
        font-size: 1.1rem;
        color: var(--primary-color);
        margin-bottom: 0.25rem;
    }

    .order-date {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 0.875rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        color: white;
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

    .order-body {
        padding: 1.25rem;
    }

    .order-items h6 {
        color: var(--text-dark);
        font-weight: 600;
    }

    .items-list {
        background: var(--light-bg);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .item-row:last-child {
        border-bottom: none;
    }

    .item-name {
        font-weight: 600;
        color: var(--text-dark);
    }

    .item-details {
        font-size: 0.875rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
    }

    .item-total {
        font-weight: 600;
        color: var(--success-color);
    }

    .more-items {
        text-align: center;
        padding-top: 0.5rem;
    }

    .order-summary {
        background: var(--light-bg);
        border-radius: 8px;
        padding: 1rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .summary-row:last-child {
        margin-bottom: 0;
    }

    .payment-method {
        color: var(--primary-color);
        font-weight: 600;
    }

    .order-footer {
        background: var(--light-bg);
        padding: 1.25rem;
        border-top: 1px solid var(--border-color);
    }

    .order-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        padding: 0.625rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        border: 2px solid;
        transition: all 0.3s ease;
        min-width: 120px;
        justify-content: center;
    }

    .btn-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
        background: rgba(79, 70, 229, 0.1);
    }

    .btn-primary:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-warning {
        color: var(--warning-color);
        border-color: var(--warning-color);
        background: rgba(245, 158, 11, 0.1);
    }

    .btn-warning:hover {
        background: var(--warning-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-info {
        color: var(--info-color);
        border-color: var(--info-color);
        background: rgba(59, 130, 246, 0.1);
    }

    .btn-info:hover {
        background: var(--info-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Modal Styling */
    .payment-info {
        background: var(--light-bg);
        border-radius: 8px;
        padding: 1rem;
    }

    .payment-details p {
        margin-bottom: 0.5rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
            text-align: center;
        }

        .page-title {
            font-size: 1.5rem;
            justify-content: center;
        }

        .status-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .order-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .order-actions {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
        }

        .item-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .summary-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }
    }

    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .order-card {
        animation: fadeInUp 0.6s ease-out;
    }
</style>
@endpush