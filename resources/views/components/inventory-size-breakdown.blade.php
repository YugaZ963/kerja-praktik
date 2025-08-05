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
    <div class="card">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="bi bi-rulers me-2"></i>
                Detail Stok per Ukuran
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ukuran</th>
                            <th class="text-center">Stok</th>
                            <th class="text-end">Nilai</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sizeBreakdown as $breakdown)
                            <tr>
                                <td>
                                    <span class="badge bg-primary">{{ $breakdown['size'] }}</span>
                                </td>
                                <td class="text-center">
                                    <strong>{{ $breakdown['stock'] }}</strong>
                                </td>
                                <td class="text-end">
                                    Rp {{ number_format($breakdown['total_value'], 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    @if($breakdown['stock'] == 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($breakdown['stock'] <= 10)
                                        <span class="badge bg-warning">Rendah</span>
                                    @else
                                        <span class="badge bg-success">Tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th>Total</th>
                            <th class="text-center">{{ array_sum(array_column($sizeBreakdown, 'stock')) }}</th>
                            <th class="text-end">Rp {{ number_format(array_sum(array_column($sizeBreakdown, 'total_value')), 0, ',', '.') }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        Tidak ada detail produk per ukuran untuk item ini.
    </div>
@endif