@props(['inventory_items' => []])

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-primary">
            <tr>
                <th style="min-width: 100px;">Kode</th>
                <th style="min-width: 200px;">Nama Item</th>
                <th style="min-width: 100px;">Kategori</th>
                <th style="min-width: 80px;">Stok</th>
                <th style="min-width: 120px;" class="d-none d-md-table-cell">Supplier</th>
                <th style="min-width: 120px;" class="d-none d-lg-table-cell">Terakhir Diperbarui</th>
                <th style="min-width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($inventory_items as $item)
                <tr>
                    <td>{{ $item['code'] }}</td>
                    <td>
                        {{ $item['name'] }}
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
                    <td class="d-none d-md-table-cell">{{ $item['supplier'] }}</td>
                    <td class="d-none d-lg-table-cell">{{ $item['last_restock'] }}</td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <!-- Mobile: Stack buttons vertically -->
                            <div class="d-block d-md-none w-100">
                                <div class="btn-group-vertical btn-group-sm w-100" role="group">
                                    <a href="/inventory/{{ $item['code'] }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>

                                </div>
                            </div>
                            
                            <!-- Desktop: Horizontal button group -->
                            <div class="d-none d-md-flex">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="/inventory/{{ $item['code'] }}" class="btn btn-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="text-muted">
                            <i class="bi bi-inbox display-4 d-block mb-2"></i>
                            <p class="mb-0">Tidak ada data inventaris</p>
                            <small>Silakan tambah item inventaris baru</small>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
// Fungsi untuk menambah stok
function addProduct(inventoryId, size) {
    console.log('addProduct called with:', inventoryId, size);
    
    // Cek apakah modal sudah ada
    const modal = document.getElementById('addProductModal');
    if (!modal) {
        alert('Modal tambah stok tidak ditemukan. Silakan refresh halaman.');
        return;
    }
    
    // Reset form
    const form = document.getElementById('addStockForm');
    if (form) {
        form.reset();
        document.getElementById('add_inventory_id').value = inventoryId;
        document.getElementById('add_size').value = size;
        document.getElementById('size_info').textContent = size;
        
        new bootstrap.Modal(modal).show();
    } else {
        alert('Form tambah stok tidak ditemukan. Silakan refresh halaman.');
    }
}

// Fungsi untuk edit produk
function editProducts(inventoryId, size) {
    console.log('editProducts called with:', inventoryId, size);
    
    fetch(`/inventory/${inventoryId}/products?size=${size}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let html = '';
                data.products.forEach(product => {
                    html += `
                        <div class="product-edit-item">
                            <form class="edit-product-form" data-product-id="${product.id}">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Produk</label>
                                        <input type="text" class="form-control" name="name" value="${product.name}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Harga</label>
                                        <input type="number" class="form-control" name="price" value="${product.price}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Stok</label>
                                        <input type="number" class="form-control" name="stock" value="${product.stock}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3 d-flex align-items-end">
                                        <button type="button" class="btn btn-primary me-2" onclick="updateProduct(${product.id})">
                                            <i class="bi bi-check"></i> Update
                                        </button>
                                        <button type="button" class="btn btn-danger" onclick="deleteSingleProduct(${product.id})">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Foto Produk</label>
                                        <input type="file" class="form-control" name="image" accept="image/*">
                                        ${product.image ? `<small class="text-muted">Foto saat ini: ${product.image}</small>` : ''}
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea class="form-control" name="description" rows="2">${product.description || ''}</textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    `;
                });
                
                const editList = document.getElementById('editProductList');
                if (editList) {
                    editList.innerHTML = html;
                    const editModal = document.getElementById('editProductModal');
                    if (editModal) {
                        new bootstrap.Modal(editModal).show();
                    }
                } else {
                    alert('Modal edit produk tidak ditemukan. Silakan refresh halaman.');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengambil data produk.');
        });
}

// Fungsi untuk kurangi stok
function deleteProducts(inventoryId, size) {
    console.log('deleteProducts called with:', inventoryId, size);
    
    // Cek apakah modal sudah ada
    const modal = document.getElementById('deleteProductModal');
    if (!modal) {
        alert('Modal kurangi stok tidak ditemukan. Silakan refresh halaman.');
        return;
    }
    
    // Reset form
    const form = document.getElementById('reduceStockForm');
    if (form) {
        form.reset();
        document.getElementById('reduce_inventory_id').value = inventoryId;
        document.getElementById('reduce_size').value = size;
        document.getElementById('reduce_size_info').textContent = size;
        
        // Ambil informasi stok saat ini
        fetch(`/inventory/${inventoryId}/products?size=${size}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let totalStock = data.products.reduce((sum, product) => sum + product.stock, 0);
                    const stockInfo = document.getElementById('currentStockInfo');
                    if (stockInfo) {
                        stockInfo.innerHTML = `
                            <div class="alert alert-info">
                                <strong>Stok Saat Ini:</strong> ${totalStock} unit
                            </div>
                        `;
                    }
                    
                    const reduceStockInput = document.getElementById('reduce_stock');
                    if (reduceStockInput) {
                        reduceStockInput.setAttribute('max', totalStock);
                    }
                    
                    new bootstrap.Modal(modal).show();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data stok.');
            });
    } else {
        alert('Form kurangi stok tidak ditemukan. Silakan refresh halaman.');
    }
}

// Fungsi untuk update produk individual
function updateProduct(productId) {
    console.log('updateProduct called with:', productId);
    
    const form = document.querySelector(`form[data-product-id="${productId}"]`);
    if (!form) {
        alert('Form tidak ditemukan');
        return;
    }
    
    const formData = new FormData(form);
    formData.append('_method', 'PUT');
    
    fetch(`/admin/products/${productId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Produk berhasil diperbarui!');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbarui produk.');
    });
}

// Fungsi untuk hapus produk individual
function deleteSingleProduct(productId) {
    console.log('deleteSingleProduct called with:', productId);
    
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
        fetch(`/admin/products/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Produk berhasil dihapus!');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus produk.');
        });
    }
}

// Make functions globally accessible
window.addProduct = addProduct;
window.editProducts = editProducts;
window.deleteProducts = deleteProducts;
window.updateProduct = updateProduct;
window.deleteSingleProduct = deleteSingleProduct;

console.log('Inventory table functions loaded:', {
    addProduct: typeof window.addProduct,
    editProducts: typeof window.editProducts,
    deleteProducts: typeof window.deleteProducts,
    updateProduct: typeof window.updateProduct,
    deleteSingleProduct: typeof window.deleteSingleProduct
});

// Dropdown initialization is handled globally in customer.blade.php layout
});
</script>
