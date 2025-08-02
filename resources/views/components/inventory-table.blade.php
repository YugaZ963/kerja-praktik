@props(['inventory_items' => []])

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-primary">
            <tr>
                <th>Kode</th>
                <th>Nama Item</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Supplier</th>
                <th>Terakhir Diperbarui</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($inventory_items as $item)
                <tr>
                    <td>{{ $item['code'] }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['category'] }}</td>
                    <td>
                        @if ($item['stock'] <= $item['min_stock'])
                            <span class="badge bg-danger">Stok Rendah ({{ $item['stock'] }})</span>
                        @elseif ($item['stock'] <= $item['min_stock'] * 1.5)
                            <span class="badge bg-warning text-dark">Perlu Restock ({{ $item['stock'] }})</span>
                        @else
                            <span class="badge bg-success">{{ $item['stock'] }}</span>
                        @endif
                    </td>
                    <td>Rp {{ number_format($item['purchase_price']) }}</td>
                    <td>Rp {{ number_format($item['selling_price']) }}</td>
                    <td>{{ $item['supplier'] }}</td>
                    <td>{{ $item['last_restock'] }}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="/inventory/{{ $item['code'] }}" class="btn btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="#" class="btn btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="#" class="btn btn-danger">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data inventaris</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
