@extends('layouts.customer')

@section('title', ' - Manajemen Inventaris')

@section('content')
    <div class="container mt-4">
        <!-- Navbar -->
        <x-navbar />

        <!-- Hero Section -->
        <div class="bg-light p-5 rounded mb-4 text-center">
            <h1 class="display-5 fw-bold text-primary">Manajemen Inventaris</h1>
            <p class="lead">Kelola inventaris seragam sekolah dengan mudah dan efisien</p>
        </div>

        <!-- Stats Cards -->
        <x-inventory-stats :inventory_items="$inventory_items" />

        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col-md-12 d-flex justify-content-between">
                <div>
                    <a href="#" class="btn btn-primary me-2">
                        <i class="bi bi-plus-circle"></i> Tambah Item Baru
                    </a>
                    <a href="/inventory/reports/stock" class="btn btn-success">
                        <i class="bi bi-file-earmark-text"></i> Laporan Stok
                    </a>
                </div>
                <div>
                    <button class="btn btn-outline-secondary me-2">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                    <button class="btn btn-outline-primary">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <x-inventory-filter />

        <!-- Inventory Table -->
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Item Inventaris</h5>
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" placeholder="Cari item..." id="search-inventory">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <x-inventory-table :inventory_items="$inventory_items" />
            </div>
            <div class="card-footer bg-white">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table th {
            white-space: nowrap;
        }

        .badge {
            font-size: 0.9em;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-inventory');

            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();

                // Simulasi pencarian (dalam implementasi nyata akan menggunakan AJAX atau filter client-side)
                console.log('Mencari:', searchTerm);

                // Implementasi pencarian sebenarnya akan dilakukan di sini
            });
        });
    </script>
@endpush
