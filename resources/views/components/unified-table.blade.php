<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Pemasok</th>
                <th>Ukuran</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventories as $item)
                <tr>
                    <td>
                        @if($item->product && $item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="img-thumbnail" 
                                 style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px; border-radius: 4px;">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div>
                            <strong>{{ $item->product->name ?? 'Produk Tidak Ditemukan' }}</strong>
                            @if($item->product)
                                <br><small class="text-muted">SKU: {{ $item->product->sku }}</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $item->product->category ?? '-' }}</span>
                    </td>
                    <td>
                        {{ $item->supplier ?? '-' }}
                    </td>
                    <td>
                        @if($item->product)
                            @php
                                $sizes = collect(json_decode($item->product->sizes, true) ?? []);
                            @endphp
                            @if($sizes->isNotEmpty())
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($sizes as $size)
                                        <span class="badge bg-secondary">{{ $size }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="fw-bold">{{ number_format($item->stock) }}</span>
                            @if($item->product)
                                <small class="text-muted ms-1">/ {{ number_format($item->product->stock) }}</small>
                            @endif
                        </div>
                        @if($item->product)
                            <small class="text-muted">Produk: {{ number_format($item->product->stock) }}</small>
                        @endif
                    </td>
                    <td>
                        @php
                            $stockStatus = 'normal';
                            $statusText = 'Normal';
                            $statusClass = 'bg-success';
                            
                            if($item->stock <= 0) {
                                $stockStatus = 'habis';
                                $statusText = 'Habis';
                                $statusClass = 'bg-danger';
                            } elseif($item->stock <= 10) {
                                $stockStatus = 'rendah';
                                $statusText = 'Stok Rendah';
                                $statusClass = 'bg-warning text-dark';
                            }
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                        
                        @if($item->product && $item->product->stock != $item->stock)
                            <br><small class="text-warning">
                                <i class="fas fa-exclamation-triangle"></i> Tidak Sinkron
                            </small>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-info" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#detailModal{{ $item->id }}">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            @if($item->product)
                                <a href="{{ route('admin.products.edit', $item->product->id) }}" 
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif
                            
                            <a href="{{ route('inventory.edit', $item->id) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-boxes"></i>
                            </a>
                            
                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                    onclick="confirmDelete('{{ $item->id }}', '{{ $item->product->name ?? 'Item' }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <!-- Modal Detail -->
                <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detail {{ $item->product->name ?? 'Item Inventaris' }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="img-fluid rounded">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                                 style="height: 200px;">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-8">
                                        <h6>Informasi Produk</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Nama:</strong></td>
                                                <td>{{ $item->product->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>SKU:</strong></td>
                                                <td>{{ $item->product->sku ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kategori:</strong></td>
                                                <td>{{ $item->product->category ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Deskripsi:</strong></td>
                                                <td>{{ $item->product->description ?? '-' }}</td>
                                            </tr>
                                        </table>
                                        
                                        <h6 class="mt-3">Informasi Inventaris</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Stok Inventaris:</strong></td>
                                                <td>{{ number_format($item->stock) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Stok Produk:</strong></td>
                                                <td>{{ number_format($item->product->stock ?? 0) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Pemasok:</strong></td>
                                                <td>{{ $item->supplier ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Lokasi:</strong></td>
                                                <td>{{ $item->location ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Terakhir Update:</strong></td>
                                                <td>{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                @if($item->product)
                                    <a href="{{ route('admin.products.edit', $item->product->id) }}" 
                                       class="btn btn-warning">Edit Produk</a>
                                @endif
                                <a href="{{ route('inventory.edit', $item->id) }}" 
                                   class="btn btn-primary">Edit Inventaris</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>Tidak ada data inventaris yang ditemukan</p>
                            <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Inventaris Baru
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if(method_exists($inventories, 'links'))
    <div class="d-flex justify-content-center mt-4">
        {{ $inventories->links() }}
    </div>
@endif

<script>
function confirmDelete(id, name) {
    if (confirm(`Apakah Anda yakin ingin menghapus item "${name}" dari inventaris?`)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/inventory/${id}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>