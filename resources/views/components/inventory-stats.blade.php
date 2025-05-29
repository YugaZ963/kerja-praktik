@props(['inventory_items' => []])

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Item</h6>
                        <h2 class="mb-0">{{ count($inventory_items) }}</h2>
                    </div>
                    <i class="bi bi-box-seam fs-1"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Stok</h6>
                        <h2 class="mb-0">{{ array_sum(array_column($inventory_items, 'stock')) }}</h2>
                    </div>
                    <i class="bi bi-stack fs-1"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Nilai Inventaris</h6>
                        @php
                            $totalValue = 0;
                            foreach ($inventory_items as $item) {
                                $totalValue += $item['purchase_price'] * $item['stock'];
                            }
                        @endphp
                        <h2 class="mb-0">Rp {{ number_format($totalValue) }}</h2>
                    </div>
                    <i class="bi bi-currency-dollar fs-1"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-danger text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Stok Rendah</h6>
                        @php
                            $lowStockCount = 0;
                            foreach ($inventory_items as $item) {
                                if ($item['stock'] <= $item['min_stock']) {
                                    $lowStockCount++;
                                }
                            }
                        @endphp
                        <h2 class="mb-0">{{ $lowStockCount }}</h2>
                    </div>
                    <i class="bi bi-exclamation-triangle fs-1"></i>
                </div>
            </div>
        </div>
    </div>
</div>
