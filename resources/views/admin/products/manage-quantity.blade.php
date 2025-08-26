@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-plus-minus me-2"></i>
                        Kelola Quantity Produk - {{ $inventory->name }} (Ukuran {{ $size }})
                    </h4>
                    <div>
                        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.products.manage.quantity', ['inventory' => $inventory->id, 'size' => $size]) }}">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Informasi Inventaris</h6>
                                        <p class="mb-1"><strong>Nama:</strong> {{ $inventory->name }}</p>
                                        <p class="mb-1"><strong>Kategori:</strong> {{ $inventory->category }}</p>
                                        <p class="mb-0"><strong>Ukuran:</strong> <span class="badge bg-primary">{{ $size }}</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Total Produk</h6>
                                        <h3 class="mb-0">{{ $products->count() }} Produk</h3>
                                        <small>Total Stok Saat Ini: {{ $products->sum('stock') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Produk</th>
                                        <th>Harga</th>
                                        <th>Stok Saat Ini</th>
                                        <th>Quantity Baru</th>
                                        <th>Aksi Cepat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td><span class="badge bg-secondary">#{{ $product->id }}</span></td>
                                            <td>
                                                <strong>{{ $product->name }}</strong>
                                                @if($product->description)
                                                    <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge {{ $product->stock <= 10 ? 'bg-warning' : ($product->stock == 0 ? 'bg-danger' : 'bg-success') }}">
                                                    {{ $product->stock }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm" style="width: 150px;">
                                                    <button type="button" class="btn btn-outline-secondary" onclick="decreaseQuantity({{ $product->id }})">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    <input type="number" 
                                                           class="form-control text-center" 
                                                           name="quantities[{{ $product->id }}]" 
                                                           id="quantity_{{ $product->id }}"
                                                           value="{{ $product->stock }}" 
                                                           min="0" 
                                                           required>
                                                    <button type="button" class="btn btn-outline-secondary" onclick="increaseQuantity({{ $product->id }})">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-success" onclick="setQuantity({{ $product->id }}, {{ $product->stock + 10 }})" title="+10">
                                                        +10
                                                    </button>
                                                    <button type="button" class="btn btn-outline-warning" onclick="setQuantity({{ $product->id }}, Math.max(0, {{ $product->stock }} - 10))" title="-10">
                                                        -10
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" onclick="setQuantity({{ $product->id }}, 0)" title="Reset ke 0">
                                                        0
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                <button type="button" class="btn btn-outline-info" onclick="resetAllQuantities()">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Reset Semua
                                </button>
                                <button type="button" class="btn btn-outline-success" onclick="addToAllQuantities(10)">
                                    <i class="bi bi-plus me-1"></i>Tambah 10 ke Semua
                                </button>
                            </div>
                            <div>
                                <a href="{{ route('inventory.index') }}" class="btn btn-secondary me-2">
                                    <i class="bi bi-x-circle me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function increaseQuantity(productId) {
    const input = document.getElementById(`quantity_${productId}`);
    input.value = parseInt(input.value) + 1;
}

function decreaseQuantity(productId) {
    const input = document.getElementById(`quantity_${productId}`);
    const newValue = Math.max(0, parseInt(input.value) - 1);
    input.value = newValue;
}

function setQuantity(productId, quantity) {
    const input = document.getElementById(`quantity_${productId}`);
    input.value = Math.max(0, quantity);
}

function resetAllQuantities() {
    @foreach($products as $product)
        setQuantity({{ $product->id }}, {{ $product->stock }});
    @endforeach
}

function addToAllQuantities(amount) {
    @foreach($products as $product)
        const input = document.getElementById(`quantity_{{ $product->id }}`);
        input.value = parseInt(input.value) + amount;
    @endforeach
}
</script>
@endsection