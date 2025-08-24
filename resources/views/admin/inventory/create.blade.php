@extends('layouts.customer')

@section('title', 'Tambah Item Inventaris')

@section('content')
    <div class="container mt-4">
        <x-navbar />

        <div class="bg-light p-4 rounded mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-primary">Tambah Item Inventaris Baru</h1>
                <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('inventory.store') }}" method="POST" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label for="code" class="form-label">Kode Item</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label">Nama Item</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="category" class="form-label">Kategori</label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="" selected disabled>Pilih Kategori</option>
                            <option value="Kemeja Sekolah">Kemeja Sekolah</option>
                            <option value="Kemeja Batik">Kemeja Batik</option>
                            <option value="Kemeja Batik Koko">Kemeja Batik Koko</option>
                            <option value="Kemeja Padang">Kemeja Padang</option>
                            <option value="Rok Sekolah">Rok Sekolah</option>
                            <option value="Celana Sekolah">Celana Sekolah</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="stock" class="form-label">Stok Awal</label>
                        <input type="number" class="form-control" id="stock" name="stock" min="0" required>
                    </div>

                    <div class="col-md-4">
                        <label for="min_stock" class="form-label">Stok Minimal</label>
                        <input type="number" class="form-control" id="min_stock" name="min_stock" min="1" required>
                    </div>

                    <div class="col-md-6">
                        <label for="purchase_price" class="form-label">Harga Beli (Rp)</label>
                        <input type="number" class="form-control" id="purchase_price" name="purchase_price" min="0" required>
                    </div>

                    <div class="col-md-6">
                        <label for="selling_price" class="form-label">Harga Jual (Rp)</label>
                        <input type="number" class="form-control" id="selling_price" name="selling_price" min="0" required>
                    </div>

                    <div class="col-md-6">
                        <label for="supplier" class="form-label">Supplier</label>
                        <input type="text" class="form-control" id="supplier" name="supplier" required>
                    </div>

                    <div class="col-md-6">
                        <label for="location" class="form-label">Lokasi Penyimpanan</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Ukuran yang Tersedia</label>
                        <div class="row g-2">
                            @foreach (['3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', 'S', 'M', 'L', 'XL', 'L3', 'L4', 'L5', 'L6'] as $size)
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="sizes_available[]" value="{{ $size }}" id="size-{{ $size }}">
                                        <label class="form-check-label" for="size-{{ $size }}">{{ $size }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Item
                        </button>
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>
@endpush