@extends('layouts.app')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">
                        <i class="fas fa-shopping-cart text-primary me-3"></i>Manajemen Pesanan
                    </h2>
                    <p class="text-muted mb-0">Kelola dan pantau semua pesanan pelanggan</p>
                </div>
                <div class="d-flex gap-3">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex gap-2">
                        <div class="input-group" style="width: 300px;">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 search-input" 
                                   placeholder="Cari nomor pesanan, nama, atau telepon..." 
                                   value="{{ request('search') }}">
                        </div>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-search me-2"></i>Cari
                        </button>
                        @if(request('search') || request('status'))
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-danger">
                            <i class="fas fa-times me-2"></i>Reset
                        </a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-list-alt me-2"></i>Daftar Pesanan
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-white text-primary px-3 py-2">
                                <i class="fas fa-chart-bar me-1"></i>
                                Total: {{ $statusCounts['all'] }} pesanan
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Status Filter Tabs -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="status-tabs-container">
                                <div class="row g-2">
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                        <a class="status-tab {{ !request('status') || request('status') === 'all' ? 'active' : '' }}" 
                                           href="{{ route('admin.orders.index', ['status' => 'all', 'search' => request('search')]) }}">
                                            <div class="status-icon bg-primary">
                                                <i class="fas fa-list"></i>
                                            </div>
                                            <div class="status-info">
                                                <span class="status-label">Semua</span>
                                                <span class="status-count">{{ $statusCounts['all'] }}</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                        <a class="status-tab {{ request('status') === 'pending' ? 'active' : '' }}" 
                                           href="{{ route('admin.orders.index', ['status' => 'pending', 'search' => request('search')]) }}">
                                            <div class="status-icon bg-warning">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <div class="status-info">
                                                <span class="status-label">Pending</span>
                                                <span class="status-count">{{ $statusCounts['pending'] }}</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                        <a class="status-tab {{ request('status') === 'payment_pending' ? 'active' : '' }}" 
                                           href="{{ route('admin.orders.index', ['status' => 'payment_pending', 'search' => request('search')]) }}">
                                            <div class="status-icon bg-info">
                                                <i class="fas fa-credit-card"></i>
                                            </div>
                                            <div class="status-info">
                                                <span class="status-label">Menunggu Verifikasi</span>
                                                <span class="status-count">{{ $statusCounts['payment_pending'] }}</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                        <a class="status-tab {{ request('status') === 'payment_verified' ? 'active' : '' }}" 
                                           href="{{ route('admin.orders.index', ['status' => 'payment_verified', 'search' => request('search')]) }}">
                                            <div class="status-icon bg-success">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <div class="status-info">
                                                <span class="status-label">Pembayaran Terverifikasi</span>
                                                <span class="status-count">{{ $statusCounts['payment_verified'] }}</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                        <a class="status-tab {{ request('status') === 'processing' ? 'active' : '' }}" 
                                           href="{{ route('admin.orders.index', ['status' => 'processing', 'search' => request('search')]) }}">
                                            <div class="status-icon bg-primary">
                                                <i class="fas fa-cogs"></i>
                                            </div>
                                            <div class="status-info">
                                                <span class="status-label">Diproses</span>
                                                <span class="status-count">{{ $statusCounts['processing'] }}</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                        <a class="status-tab {{ request('status') === 'packaged' ? 'active' : '' }}" 
                                           href="{{ route('admin.orders.index', ['status' => 'packaged', 'search' => request('search')]) }}">
                                            <div class="status-icon bg-purple">
                                                <i class="fas fa-box"></i>
                                            </div>
                                            <div class="status-info">
                                                <span class="status-label">Dikemas</span>
                                                <span class="status-count">{{ $statusCounts['packaged'] }}</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                        <a class="status-tab {{ request('status') === 'shipped' ? 'active' : '' }}" 
                                           href="{{ route('admin.orders.index', ['status' => 'shipped', 'search' => request('search')]) }}">
                                            <div class="status-icon bg-info">
                                                <i class="fas fa-shipping-fast"></i>
                                            </div>
                                            <div class="status-info">
                                                <span class="status-label">Dikirim</span>
                                                <span class="status-count">{{ $statusCounts['shipped'] }}</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                        <a class="status-tab {{ request('status') === 'delivered' ? 'active' : '' }}" 
                                           href="{{ route('admin.orders.index', ['status' => 'delivered', 'search' => request('search')]) }}">
                                            <div class="status-icon bg-success">
                                                <i class="fas fa-truck"></i>
                                            </div>
                                            <div class="status-info">
                                                <span class="status-label">Sudah Sampai</span>
                                                <span class="status-count">{{ $statusCounts['delivered'] }}</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                        <a class="status-tab {{ request('status') === 'completed' ? 'active' : '' }}" 
                                           href="{{ route('admin.orders.index', ['status' => 'completed', 'search' => request('search')]) }}">
                                            <div class="status-icon bg-success">
                                                <i class="fas fa-check-double"></i>
                                            </div>
                                            <div class="status-info">
                                                <span class="status-label">Selesai</span>
                                                <span class="status-count">{{ $statusCounts['completed'] }}</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                        <a class="status-tab {{ request('status') === 'cancelled' ? 'active' : '' }}" 
                                           href="{{ route('admin.orders.index', ['status' => 'cancelled', 'search' => request('search')]) }}">
                                            <div class="status-icon bg-danger">
                                                <i class="fas fa-times-circle"></i>
                                            </div>
                                            <div class="status-info">
                                                <span class="status-label">Dibatalkan</span>
                                                <span class="status-count">{{ $statusCounts['cancelled'] }}</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Table -->
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-hashtag me-2"></i>No. Pesanan</th>
                                        <th><i class="fas fa-calendar me-2"></i>Tanggal</th>
                                        <th><i class="fas fa-user me-2"></i>Pelanggan</th>
                                        <th><i class="fas fa-money-bill me-2"></i>Total</th>
                                        <th><i class="fas fa-credit-card me-2"></i>Pembayaran</th>
                                        <th><i class="fas fa-info-circle me-2"></i>Status</th>
                                        <th class="text-center"><i class="fas fa-cogs me-2"></i>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <div class="order-number">
                                                <i class="fas fa-receipt me-2"></i>
                                                <span class="fw-bold">{{ $order->order_number }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="date-info">
                                                <div class="fw-semibold">{{ $order->created_at->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="customer-info">
                                                <div class="d-flex align-items-center">
                                                    <div class="customer-avatar">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <div class="fw-semibold text-dark">{{ $order->customer_name }}</div>
                                                        <small class="text-muted d-block">{{ $order->customer_phone }}</small>
                                                        <small class="d-block">
                                                            @if($order->user && $order->user->name)
                                                                <span class="text-primary"><i class="fas fa-user me-1"></i>{{ $order->user->name }}</span>
                                                            @elseif($order->user_id)
                                                                <span class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>User ID: {{ $order->user_id }}</span>
                                                            @else
                                                                <span class="text-muted"><i class="fas fa-user-slash me-1"></i>Tidak ada akun</span>
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="amount-info">
                                                <div class="fw-bold text-success fs-6">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                                <small class="text-muted">{{ $order->items->count() }} item</small>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $paymentConfig = $order->payment_method === 'bri' 
                                                    ? ['class' => 'primary', 'icon' => 'university', 'bg' => 'primary'] 
                                                    : ['class' => 'info', 'icon' => 'wallet', 'bg' => 'info'];
                                            @endphp
                                            <span class="payment-badge bg-{{ $paymentConfig['bg'] }}">
                                                <i class="fas fa-{{ $paymentConfig['icon'] }} me-1"></i>
                                                {{ $order->getPaymentMethodLabel() }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $statusConfig = match($order->status) {
                                                    'pending' => ['class' => 'warning', 'icon' => 'clock', 'bg' => 'warning'],
                                                    'payment_pending' => ['class' => 'info', 'icon' => 'credit-card', 'bg' => 'info'],
                                                    'payment_verified' => ['class' => 'success', 'icon' => 'check-circle', 'bg' => 'success'],
                                                    'processing' => ['class' => 'primary', 'icon' => 'cogs', 'bg' => 'primary'],
                                                    'packaged' => ['class' => 'purple', 'icon' => 'box', 'bg' => 'purple'],
                                                    'shipped' => ['class' => 'info', 'icon' => 'shipping-fast', 'bg' => 'info'],
                                                    'delivered' => ['class' => 'success', 'icon' => 'truck', 'bg' => 'success'],
                                                    'completed' => ['class' => 'success', 'icon' => 'check-double', 'bg' => 'success'],
                                                    'cancelled' => ['class' => 'danger', 'icon' => 'times-circle', 'bg' => 'danger'],
                                                    default => ['class' => 'secondary', 'icon' => 'question', 'bg' => 'secondary']
                                                };
                                            @endphp
                                            <span class="status-badge bg-{{ $statusConfig['bg'] }}">
                                                <i class="fas fa-{{ $statusConfig['icon'] }} me-1"></i>
                                                {{ $order->getStatusLabel() }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.orders.show', $order) }}" 
                                                   class="btn-action btn-view" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                    <span>Detail</span>
                                                </a>
                                                @if($order->status !== 'completed' && $order->status !== 'cancelled')
                                                <button type="button" class="btn-action btn-edit" 
                                                        data-bs-toggle="modal" data-bs-target="#statusModal{{ $order->id }}" 
                                                        title="Update Status">
                                                    <i class="fas fa-edit"></i>
                                                    <span>Edit</span>
                                                </button>
                                                @endif
                                                <button type="button" class="btn-action btn-delete" 
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $order->id }}" 
                                                        title="Hapus Pesanan">
                                                    <i class="fas fa-trash"></i>
                                                    <span>Hapus</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                <!-- Status Update Modal -->
                                @if($order->status !== 'completed' && $order->status !== 'cancelled')
                                <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Status Pesanan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Status Baru</label>
                                                        <select name="status" class="form-select" required>
                                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="payment_pending" {{ $order->status == 'payment_pending' ? 'selected' : '' }}>Menunggu Verifikasi Pembayaran</option>
                                                            <option value="payment_verified" {{ $order->status == 'payment_verified' ? 'selected' : '' }}>Pembayaran Terverifikasi</option>
                                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Sedang Diproses</option>
                                                            <option value="packaged" {{ $order->status == 'packaged' ? 'selected' : '' }}>Dikemas</option>
                                                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Terkirim</option>
                                                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Catatan Admin</label>
                                                        <textarea name="admin_notes" class="form-control" rows="3" placeholder="Catatan tambahan...">{{ $order->admin_notes }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $order->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.orders.destroy', $order) }}">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Hapus Pesanan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus pesanan <strong>{{ $order->order_number }}</strong>?</p>
                                                    <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <i class="fas fa-shopping-cart"></i>
                                            <h5 class="mt-3 mb-2">Tidak ada pesanan ditemukan</h5>
                                            <p class="mb-0">Belum ada pesanan yang sesuai dengan filter yang dipilih.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($orders->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                    @endif
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

    /* Page Layout */
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
        gap: 0.75rem;
    }

    /* Search Form */
    .search-form {
        background: var(--white);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        margin-bottom: 1.5rem;
    }

    .search-input-group {
        position: relative;
        max-width: 400px;
    }

    .search-box {
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 0.875rem 1rem 0.875rem 3rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: var(--white);
        width: 100%;
    }

    .search-box:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        outline: none;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        z-index: 2;
    }

    .search-btn {
        background: var(--primary-color);
        border: none;
        border-radius: 12px;
        padding: 0.875rem 1.5rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-left: 0.75rem;
    }

    .search-btn:hover {
        background: #3730a3;
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
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
        gap: 0.5rem;
    }

    /* Status Filter Tabs */
    .status-filters {
        padding: 1.5rem 2rem 0;
        background: var(--white);
    }

    .status-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
        position: relative;
        overflow: hidden;
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

    /* Table Styling */
    .table-container {
        background: var(--white);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        margin-top: 1.5rem;
    }

    .table-modern {
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern thead th {
        background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1.25rem 1rem;
        border: none;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table-modern tbody tr {
        background: var(--white);
        transition: all 0.3s ease;
        border-bottom: 1px solid var(--border-color);
    }

    .table-modern tbody tr:hover {
        background: var(--light-bg);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .table-modern tbody tr:last-child {
        border-bottom: none;
    }

    .table-modern td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        border: none;
    }

    /* Table Content Styling */
    .order-number {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary-color);
        font-weight: 600;
    }

    .date-info {
        line-height: 1.4;
    }

    .customer-info .customer-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--purple-color) 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .amount-info {
        text-align: left;
    }

    /* Badge Styling */
    .payment-badge, .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 0.875rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        color: white;
        border: none;
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

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 0.875rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        border: 2px solid;
        transition: all 0.3s ease;
        min-width: 70px;
        justify-content: center;
    }

    .btn-view {
        color: var(--primary-color);
        border-color: var(--primary-color);
        background: rgba(79, 70, 229, 0.1);
    }

    .btn-view:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-edit {
        color: var(--warning-color);
        border-color: var(--warning-color);
        background: rgba(245, 158, 11, 0.1);
    }

    .btn-edit:hover {
        background: var(--warning-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-delete {
        color: var(--danger-color);
        border-color: var(--danger-color);
        background: rgba(239, 68, 68, 0.1);
    }

    .btn-delete:hover {
        background: var(--danger-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
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

        .search-form {
            padding: 1rem;
        }

        .search-input-group {
            max-width: 100%;
        }

        .search-btn {
            margin-left: 0;
            margin-top: 0.75rem;
            width: 100%;
        }

        .status-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .status-tab {
            padding: 0.875rem;
        }

        .table-container {
            margin: 0 -1rem;
            border-radius: 0;
        }

        .action-buttons {
            flex-direction: column;
            gap: 0.375rem;
        }

        .btn-action {
            min-width: auto;
            width: 100%;
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

    .card, .search-form {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Tooltip Styling */
    .tooltip {
        font-size: 0.8rem;
    }

    .tooltip-inner {
        background: var(--text-dark);
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Show success/error messages
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
</script>
@endpush