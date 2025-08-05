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
                    <td>
                        {{ $item['name'] }}
                        <br>
                        <button class="btn btn-sm btn-outline-secondary size-breakdown-toggle" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#sizeBreakdown{{ $item['id'] }}" 
                                aria-expanded="false" 
                                style="display: none;">
                            <i class="bi bi-rulers"></i> Detail Ukuran
                        </button>
                    </td>
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
                            <a href="/inventory/{{ $item['code'] }}" class="btn btn-info" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('inventory.edit', $item['id']) }}" class="btn btn-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('inventory.destroy', $item['id']) }}" method="POST" class="d-inline" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus item {{ $item['name'] }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <!-- Size Breakdown Row -->
                <tr class="collapse size-breakdown-row" id="sizeBreakdown{{ $item['id'] }}">
                    <td colspan="9" class="p-0">
                        <div class="p-3 bg-light">
                            <x-inventory-size-breakdown :item="$item" />
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

<script>
function toggleSizeBreakdown() {
    const toggleButtons = document.querySelectorAll('.size-breakdown-toggle');
    const toggleText = document.getElementById('toggleText');
    const isVisible = toggleButtons[0].style.display !== 'none';
    
    toggleButtons.forEach(button => {
        button.style.display = isVisible ? 'none' : 'inline-block';
    });
    
    if (isVisible) {
        // Sembunyikan semua breakdown yang terbuka
        document.querySelectorAll('.size-breakdown-row.show').forEach(row => {
            row.classList.remove('show');
        });
        toggleText.textContent = 'Tampilkan Detail Ukuran';
    } else {
        toggleText.textContent = 'Sembunyikan Detail Ukuran';
    }
}
</script>
