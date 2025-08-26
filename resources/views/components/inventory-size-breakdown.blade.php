@props(['item'])

@php
    // Ambil produk yang terkait dengan inventory item ini
    $products = \App\Models\Product::where('inventory_id', $item->id ?? $item['id'])->get();
    $sizeBreakdown = [];
    
    // Kelompokkan produk berdasarkan ukuran
    foreach ($products as $product) {
        $size = $product->size;
        if (!isset($sizeBreakdown[$size])) {
            $sizeBreakdown[$size] = [
                'size' => $size,
                'stock' => 0,
                'products_count' => 0,
                'total_value' => 0
            ];
        }
        $sizeBreakdown[$size]['stock'] += $product->stock;
        $sizeBreakdown[$size]['products_count']++;
        $sizeBreakdown[$size]['total_value'] += $product->stock * ($item->purchase_price ?? $item['purchase_price']);
    }
    
    // Urutkan berdasarkan ukuran
    ksort($sizeBreakdown);
@endphp

@if(count($sizeBreakdown) > 0)
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-gradient-primary text-white">
            <h6 class="mb-0">
                <i class="bi bi-rulers me-2"></i>
                Detail Stok per Ukuran ({{ count($sizeBreakdown) }} ukuran)
            </h6>
        </div>
        <div class="card-body p-2">
            <!-- Compact Grid Layout -->
            <div class="row g-2">
                @foreach($sizeBreakdown as $breakdown)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="size-item p-2 border rounded bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge bg-primary fs-6">{{ $breakdown['size'] }}</span>
                                @if($breakdown['stock'] == 0)
                                    <span class="badge bg-danger">Habis</span>
                                @elseif($breakdown['stock'] <= 10)
                                    <span class="badge bg-warning">Rendah</span>
                                @else
                                    <span class="badge bg-success">OK</span>
                                @endif
            </div>
                            <div class="text-center mb-2">
                                <div class="fw-bold text-primary fs-5">{{ $breakdown['stock'] }}</div>
                                <small class="text-muted">unit</small>
                            </div>
                            <div class="btn-group w-100" role="group">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Kelola">
                                        <i class="bi bi-gear"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.products.manage.quantity', ['inventory' => $item->id ?? $item['id'], 'size' => $breakdown['size']]) }}">
                                            <i class="bi bi-plus-minus me-2 text-success"></i>Kelola Qty
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.products.manage.edit', ['inventory' => $item->id ?? $item['id'], 'size' => $breakdown['size']]) }}">
                                            <i class="bi bi-pencil me-2 text-warning"></i>Edit
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="confirmDeleteProducts({{ $item->id ?? $item['id'] }}, '{{ $breakdown['size'] }}')">                                            <i class="bi bi-trash me-2"></i>Hapus
                                        </a></li>
                                    </ul>
                                </div>
                                <a href="{{ route('customer.products') }}?inventory={{ $item->id ?? $item['id'] }}&size={{ $breakdown['size'] }}" 
                                   class="btn btn-outline-secondary btn-sm"
                                   title="Lihat di Katalog"
                                   target="_blank">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Summary Footer -->
            <div class="mt-3 p-2 bg-light rounded">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="fw-bold text-primary">{{ array_sum(array_column($sizeBreakdown, 'stock')) }}</div>
                        <small class="text-muted">Total Stok</small>
                    </div>
                    <div class="col-6">
                        <div class="fw-bold text-success">Rp {{ number_format(array_sum(array_column($sizeBreakdown, 'total_value')), 0, ',', '.') }}</div>
                        <small class="text-muted">Total Nilai</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info border-0">
        <i class="bi bi-info-circle me-2"></i>
        Tidak ada detail produk per ukuran untuk item ini.
    </div>
@endif


<script>
function confirmDeleteProducts(inventoryId, size) {
    if (confirm(`Apakah Anda yakin ingin menghapus SEMUA produk dengan ukuran ${size}? Tindakan ini tidak dapat dibatalkan!`)) {
        // Buat form untuk mengirim request DELETE
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/products/manage/delete/${inventoryId}/${size}`;
        
        // Tambahkan CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Tambahkan method DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        // Submit form
        document.body.appendChild(form);
        form.submit();
    }
}
</script>