@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Detail Pesanan #{{ $order->order_number }}</h2>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order Information -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>No. Pesanan:</strong></td>
                                    <td>{{ $order->order_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Pesanan:</strong></td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @php
                                            $statusClass = match($order->status) {
                                                'pending' => 'warning',
                                                'payment_pending' => 'info',
                                                'payment_verified' => 'success',
                                                'processing' => 'primary',
                                                'packaged' => 'primary',
                                                'shipped' => 'info',
                                                'delivered' => 'success',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }} fs-6">{{ $order->getStatusLabel() }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Metode Pembayaran:</strong></td>
                                    <td>{{ $order->getPaymentMethodLabel() }}</td>
                                </tr>
                                @if($order->tracking_number)
                                <tr>
                                    <td><strong>Nomor Resi:</strong></td>
                                    <td><span class="badge bg-info">{{ $order->tracking_number }}</span></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nama Pelanggan:</strong></td>
                                    <td>{{ $order->customer_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Akun:</strong></td>
                                    <td>
                                            @if($order->user && $order->user->name)
                                                <span class="text-primary">
                                                    <i class="fas fa-user me-1"></i>{{ $order->user->name }}
                                                </span>
                                            @elseif($order->user_id)
                                                <span class="text-warning">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>User ID: {{ $order->user_id }} (Nama tidak ditemukan)
                                                </span>
                                            @else
                                                <span class="text-muted">
                                                    <i class="fas fa-user-slash me-1"></i>Tidak ada akun terkait
                                                </span>
                                            @endif
                                        </td>
                                </tr>
                                <tr>
                                    <td><strong>No. Telepon:</strong></td>
                                    <td>{{ $order->customer_phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat:</strong></td>
                                    <td>{{ $order->customer_address }}</td>
                                </tr>
                                @if($order->notes)
                                <tr>
                                    <td><strong>Catatan:</strong></td>
                                    <td>{{ $order->notes }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($order->admin_notes)
                    <div class="alert alert-info">
                        <strong>Catatan Admin:</strong> {{ $order->admin_notes }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Item Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Ukuran</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                 alt="{{ $item->product_name }}" 
                                                 class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ $item->product_name }}</strong>
                                                @if($item->product)
                                                <br><small class="text-muted">SKU: {{ $item->product->id }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->product_size }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td><strong>Rp {{ number_format($item->total, 0, ',', '.') }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Subtotal:</th>
                                    <th>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</th>
                                </tr>
                                @if($order->shipping_cost > 0)
                                <tr>
                                    <th colspan="4" class="text-end">Ongkos Kirim:</th>
                                    <th>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</th>
                                </tr>
                                @endif
                                <tr class="table-primary">
                                    <th colspan="4" class="text-end">Total:</th>
                                    <th>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions & Timeline -->
        <div class="col-md-4">
            <!-- Quick Actions -->
            @if($order->status !== 'completed' && $order->status !== 'cancelled')
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label">Update Status</label>
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
                            <label class="form-label">Nomor Resi</label>
                            <input type="text" name="tracking_number" class="form-control" placeholder="Masukkan nomor resi..." value="{{ $order->tracking_number }}">
                            <small class="text-muted">Nomor resi untuk tracking pesanan</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan Admin</label>
                            <textarea name="admin_notes" class="form-control" rows="3" placeholder="Catatan tambahan...">{{ $order->admin_notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Payment Proof -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Bukti Pembayaran</h5>
                </div>
                <div class="card-body">
                    @if($order->payment_proof)
                    <div class="text-center mb-3">
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" 
                             alt="Bukti Pembayaran" 
                             class="img-fluid rounded" 
                             style="max-height: 200px; cursor: pointer;"
                             data-bs-toggle="modal" 
                             data-bs-target="#paymentProofModal">
                        <p class="text-muted mt-2">Klik untuk memperbesar</p>
                    </div>
                    @else
                    <p class="text-muted text-center">Belum ada bukti pembayaran</p>
                    @endif

                    <!-- Upload Payment Proof Form -->
                    <form method="POST" action="{{ route('admin.orders.upload-payment-proof', $order) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Upload Bukti Pembayaran</label>
                            <input type="file" name="payment_proof" class="form-control" accept="image/*" required>
                            <small class="text-muted">Format: JPG, PNG (Max: 2MB)</small>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Upload Bukti</button>
                    </form>
                </div>
            </div>

            <!-- Delivery Proof -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Bukti Pengiriman</h5>
                </div>
                <div class="card-body">
                    @if($order->delivery_proof)
                    <div class="text-center mb-3">
                        <img src="{{ asset('storage/' . $order->delivery_proof) }}" 
                             alt="Bukti Pengiriman" 
                             class="img-fluid rounded" 
                             style="max-height: 200px; cursor: pointer;"
                             data-bs-toggle="modal" 
                             data-bs-target="#deliveryProofModal">
                        <p class="text-muted mt-2">Klik untuk memperbesar</p>
                    </div>
                    @else
                    <p class="text-muted text-center">Belum ada bukti pengiriman</p>
                    @endif

                    <!-- Upload Delivery Proof Form -->
                    <form method="POST" action="{{ route('admin.orders.upload-delivery-proof', $order) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Upload Bukti Pengiriman</label>
                            <input type="file" name="delivery_proof" class="form-control" accept="image/*" required>
                            <small class="text-muted">Format: JPG, PNG (Max: 2MB)</small>
                        </div>
                        <button type="submit" class="btn btn-info w-100">Upload Bukti</button>
                    </form>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Timeline Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Pesanan Dibuat</h6>
                                <p class="timeline-text">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        @if($order->payment_verified_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Pembayaran Terverifikasi</h6>
                                <p class="timeline-text">{{ $order->payment_verified_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($order->shipped_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Pesanan Dikirim</h6>
                                <p class="timeline-text">{{ $order->shipped_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($order->delivered_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Pesanan Terkirim</h6>
                                <p class="timeline-text">{{ $order->delivered_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Proof Modal -->
@if($order->payment_proof)
<div class="modal fade" id="paymentProofModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset('storage/' . $order->payment_proof) }}" 
                     alt="Bukti Pembayaran" 
                     class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endif

<!-- Delivery Proof Modal -->
@if($order->delivery_proof)
<div class="modal fade" id="deliveryProofModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Pengiriman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset('storage/' . $order->delivery_proof) }}" 
                     alt="Bukti Pengiriman" 
                     class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endif

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    padding-left: 20px;
}

.timeline-title {
    margin-bottom: 5px;
    font-size: 14px;
    font-weight: 600;
}

.timeline-text {
    margin: 0;
    font-size: 12px;
    color: #6c757d;
}
</style>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    });
</script>
@endif
@endsection