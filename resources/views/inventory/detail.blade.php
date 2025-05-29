@extends('layouts.customer')

@section('title', ' - Detail Inventaris')

@section('content')
    <div class="container mt-4">
        <!-- Navbar -->
        <x-navbar />

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/inventory">Inventaris</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $item['code'] }}</li>
            </ol>
        </nav>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Detail Item Inventaris</h2>
            <div>
                <a href="#" class="btn btn-primary me-2">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <button class="btn btn-danger">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </div>
        </div>

        <div class="row">
            <!-- Item Details -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Informasi Item</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Kode Item</div>
                            <div class="col-md-8">{{ $item['code'] }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Nama Item</div>
                            <div class="col-md-8">{{ $item['name'] }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Kategori</div>
                            <div class="col-md-8">{{ $item['category'] }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Deskripsi</div>
                            <div class="col-md-8">{{ $item['description'] }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Lokasi Penyimpanan</div>
                            <div class="col-md-8">{{ $item['location'] }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Ukuran Tersedia</div>
                            <div class="col-md-8">
                                @foreach ($item['sizes_available'] as $size)
                                    <span class="badge bg-info me-1">{{ $size }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Supplier</div>
                            <div class="col-md-8">{{ $item['supplier'] }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Terakhir Diperbarui</div>
                            <div class="col-md-8">{{ $item['last_restock'] }}</div>
                        </div>
                    </div>
                </div>

                <!-- Stock History -->
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Riwayat Stok</h5>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Transaksi
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tipe</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item['stock_history'] as $history)
                                        <tr>
                                            <td>{{ $history['date'] }}</td>
                                            <td>
                                                @if ($history['type'] == 'in')
                                                    <span class="badge bg-success">Masuk</span>
                                                @else
                                                    <span class="badge bg-danger">Keluar</span>
                                                @endif
                                            </td>
                                            <td>{{ $history['quantity'] }}</td>
                                            <td>{{ $history['notes'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Info -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Informasi Stok</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="display-4 fw-bold mb-2">{{ $item['stock'] }}</div>
                            <p class="text-muted">Jumlah Stok Saat Ini</p>

                            @php
                                $stockPercentage = ($item['stock'] / ($item['min_stock'] * 3)) * 100;
                                $progressClass = 'bg-success';

                                if ($stockPercentage <= 33) {
                                    $progressClass = 'bg-danger';
                                } elseif ($stockPercentage <= 66) {
                                    $progressClass = 'bg-warning';
                                }
                            @endphp

                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar {{ $progressClass }}" role="progressbar"
                                    style="width: {{ min($stockPercentage, 100) }}%" aria-valuenow="{{ $item['stock'] }}"
                                    aria-valuemin="0" aria-valuemax="{{ $item['min_stock'] * 3 }}">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-danger">Minimum ({{ $item['min_stock'] }})</small>
                                <small class="text-success">Optimal ({{ $item['min_stock'] * 3 }})</small>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-6 fw-bold">Harga Beli</div>
                            <div class="col-6 text-end">Rp {{ number_format($item['purchase_price']) }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6 fw-bold">Harga Jual</div>
                            <div class="col-6 text-end">Rp {{ number_format($item['selling_price']) }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6 fw-bold">Margin</div>
                            <div class="col-6 text-end">
                                @php
                                    $margin = $item['selling_price'] - $item['purchase_price'];
                                    $marginPercentage = ($margin / $item['purchase_price']) * 100;
                                @endphp
                                Rp {{ number_format($margin) }} ({{ number_format($marginPercentage, 1) }}%)
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6 fw-bold">Nilai Total</div>
                            <div class="col-6 text-end">Rp {{ number_format($item['purchase_price'] * $item['stock']) }}
                            </div>
                        </div>

                        <hr>

                        <div class="d-grid gap-2">
                            <button class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Tambah Stok
                            </button>
                            <button class="btn btn-outline-primary">
                                <i class="bi bi-dash-circle"></i> Kurangi Stok
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
