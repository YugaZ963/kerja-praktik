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
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Info:</strong> Stok akan dikelola melalui data produk, tidak perlu diisi di sini.
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Info:</strong> Harga beli, harga jual, dan ukuran akan dikelola melalui data produk. Ukuran akan otomatis muncul ketika produk ditambahkan, diubah, atau dihapus.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="supplier" class="form-label">Supplier</label>
                        <input type="text" class="form-control @error('supplier') is-invalid @enderror" id="supplier" name="supplier" value="{{ old('supplier') }}" placeholder="Masukkan nama supplier">
                        @error('supplier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Kosongkan jika tidak diketahui</small>
                    </div>

                    <div class="col-md-6">
                        <label for="location" class="form-label">Lokasi Penyimpanan</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}" placeholder="Contoh: Rak A-1">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Kosongkan jika belum ditentukan</small>
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required placeholder="Masukkan deskripsi detail item inventaris...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Deskripsi wajib diisi untuk memudahkan identifikasi item</small>
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