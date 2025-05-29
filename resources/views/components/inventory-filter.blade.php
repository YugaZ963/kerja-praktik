@props(['inventory_items' => []])

<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-3">
                <select class="form-select" id="category-filter">
                    <option selected value="">Semua Kategori</option>
                    <option value="Seragam Sekolah SD">Seragam SD</option>
                    <option value="Seragam Sekolah SMP">Seragam SMP</option>
                    <option value="Seragam Sekolah SMA">Seragam SMA</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="stock-filter">
                    <option selected value="">Status Stok</option>
                    <option value="low">Stok Rendah</option>
                    <option value="normal">Stok Normal</option>
                    <option value="high">Stok Tinggi</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="sort-filter">
                    <option selected value="">Urutkan</option>
                    <option value="name-asc">Nama (A-Z)</option>
                    <option value="name-desc">Nama (Z-A)</option>
                    <option value="stock-asc">Stok (Terendah)</option>
                    <option value="stock-desc">Stok (Tertinggi)</option>
                    <option value="price-asc">Harga Jual (Terendah)</option>
                    <option value="price-desc">Harga Jual (Tertinggi)</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-grid">
                    <button class="btn btn-primary" id="apply-filter">Terapkan Filter</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const applyFilterBtn = document.getElementById('apply-filter');

            applyFilterBtn.addEventListener('click', function() {
                const category = document.getElementById('category-filter').value;
                const stockStatus = document.getElementById('stock-filter').value;
                const sortBy = document.getElementById('sort-filter').value;

                // Simulasi filter (dalam implementasi nyata akan menggunakan AJAX atau form submit)
                console.log('Filter diterapkan:', {
                    category,
                    stockStatus,
                    sortBy
                });

                // Implementasi filter sebenarnya akan dilakukan di sini
            });
        });
    </script>
@endpush
