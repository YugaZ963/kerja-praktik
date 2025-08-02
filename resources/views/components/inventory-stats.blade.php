@props(['inventory_items' => []])

@php
// normalisasi ke array dengan memeriksa jenis data
if (is_array($inventory_items)) {
    $items = $inventory_items;
} elseif (method_exists($inventory_items, 'items')) {
    // Jika paginator, ambil items-nya
    $items = $inventory_items->items();
} elseif (method_exists($inventory_items, 'toArray')) {
    // Jika collection, konversi ke array
    $items = $inventory_items->toArray();
} else {
    $items = [];
}

// hitung sekali saja
$totalItem = count($items);
$totalStock = 0;
$inventoryValue = 0;
$lowStockCount = 0;

// Hitung dengan aman menggunakan loop
foreach ($items as $item) {
    if (is_array($item)) {
        // Jika item adalah array
        $stock = $item['stock'] ?? 0;
        $minStock = $item['min_stock'] ?? 0;
        $price = $item['purchase_price'] ?? 0;
    } else {
        // Jika item adalah objek
        $stock = $item->stock ?? 0;
        $minStock = $item->min_stock ?? 0;
        $price = $item->purchase_price ?? 0;
    }
    
    $totalStock += $stock;
    $inventoryValue += ($price * $stock);
    if ($stock <= $minStock) {
        $lowStockCount++;
    }
}
    @endphp

    <div class="row g-3 mb-4">
    <!-- Total Item -->
    <div class="col-md-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-title mb-1">Total Item</h6>
                    <h2 class="mb-0">{{ $totalItem }}</h2>
                </div>
                <i class="bi bi-box-seam fs-1"></i>
            </div>
        </div>
    </div>

    <!-- Total Stock -->
    <div class="col-md-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-title mb-1">Total Stok</h6>
                    <h2 class="mb-0">{{ $totalStock }}</h2>
                </div>
                <i class="bi bi-stack fs-1"></i>
            </div>
        </div>
    </div>

    <!-- Nilai Inventaris -->
    <div class="col-md-3">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-title mb-1">Nilai Inventaris</h6>
                    <h2 class="mb-0">Rp {{ number_format($inventoryValue, 0, ',', '.') }}</h2>
                </div>
                <i class="bi bi-currency-dollar fs-1"></i>
            </div>
        </div>
    </div>

    <!-- Stok Rendah -->
    <div class="col-md-3">
        <div class="card bg-danger text-white h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-title mb-1">Stok Rendah</h6>
                    <h2 class="mb-0">{{ $lowStockCount }}</h2>
                </div>
                <i class="bi bi-exclamation-triangle fs-1"></i>
            </div>
        </div>
    </div>
    </div>