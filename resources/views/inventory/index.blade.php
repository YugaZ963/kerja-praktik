{{-- resources/views/inventory/index.blade.php --}}
@extends('layouts.customer')

@section('title', 'Manajemen Inventaris')

@section('content')
    <div class="container mt-4">
        <x-navbar />

        <div class="bg-light p-5 rounded mb-4 text-center">
            <h1 class="display-5 fw-bold text-primary">Manajemen Inventaris</h1>
            <p class="lead">Kelola inventaris seragam sekolah dengan mudah dan efisien</p>
        </div>

        <x-inventory-stats :inventory_items="$inventory_items" />

        <div class="row mb-4">
            <div class="col-md-12 d-flex justify-content-between flex-wrap gap-2">
                <div>
                    <a href="{{ route('inventory.create') }}" class="btn btn-primary me-2">
                        <i class="bi bi-plus-circle"></i> Tambah Item Baru
                    </a>
                    <a href="{{ route('inventory.report') }}" class="btn btn-success">
                        <i class="bi bi-file-earmark-text"></i> Laporan Stok
                    </a>
                </div>
                <div>
                    <button onclick="window.print()" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                    <a href="{{ route('inventory.export') }}" class="btn btn-outline-primary">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </a>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('inventory.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    <option value="Kemeja Sekolah" {{ request('category') == 'Kemeja Sekolah' ? 'selected' : '' }}>Kemeja Sekolah</option>
                    <option value="Kemeja Batik" {{ request('category') == 'Kemeja Batik' ? 'selected' : '' }}>Kemeja Batik</option>
                    <option value="Kemeja Batik Koko" {{ request('category') == 'Kemeja Batik Koko' ? 'selected' : '' }}>Kemeja Batik Koko</option>
                    <option value="Kemeja Padang" {{ request('category') == 'Kemeja Padang' ? 'selected' : '' }}>Kemeja Padang</option>
                    <option value="Rok Sekolah" {{ request('category') == 'Rok Sekolah' ? 'selected' : '' }}>Rok Sekolah</option>
                    <option value="Celana Sekolah" {{ request('category') == 'Celana Sekolah' ? 'selected' : '' }}>Celana Sekolah</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Ukuran</label>
                <select name="size" class="form-select">
                    <option value="">Semua Ukuran</option>
                    @foreach (['3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', 'S', 'M', 'L', 'XL', 'L3', 'L4', 'L5', 'L6'] as $u)
                        <option value="{{ $u }}" {{ request('size') == $u ? 'selected' : '' }}>{{ $u }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status Stok</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Tersedia</option>
                    <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Stok Rendah</option>
                    <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>Habis</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary flex-fill">Reset</a>
            </div>
        </form>

        <div class="card">
            <div class="card-header bg-white">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">Daftar Item Inventaris</h5>
                    </div>
                    <div class="col-auto">
                        <form method="GET" action="{{ route('inventory.index') }}" class="input-group input-group-sm"
                            style="width:260px;">
                            <input type="text" name="search" class="form-control" placeholder="Cari item..."
                                value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Item</th>
                                <th>Kategori</th>
                                <th>Ukuran</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inventory_items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td><span class="badge bg-secondary">{{ $item->category }}</span></td>
                                    <td>{{ $item->size }}</td>
                                    <td>
                                        @if ($item->stock == 0)
                                            <span class="badge bg-danger">Habis</span>
                                        @elseif($item->stock <= 5)
                                            <span class="badge bg-warning text-dark">{{ $item->stock }}</span>
                                        @else
                                            <span class="badge bg-success">{{ $item->stock }}</span>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('inventory.edit', $item->id) }}"
                                            class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('inventory.destroy', $item->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Hapus item ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if (method_exists($inventory_items, 'hasPages') && $inventory_items->hasPages())
                <div class="card-footer bg-white">
                    {{ $inventory_items->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table th {
            white-space: nowrap
        }

        .badge {
            font-size: .9em
        }
    </style>
@endpush
