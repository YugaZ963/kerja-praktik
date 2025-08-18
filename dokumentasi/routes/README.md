# Dokumentasi Routes - RAVAZKA

## Overview
File routes/web.php berisi semua definisi route untuk aplikasi web RAVAZKA. Routes diorganisir berdasarkan fungsionalitas dan menggunakan middleware untuk kontrol akses. Sistem routing mengikuti pola RESTful dan menggunakan route groups untuk organisasi yang lebih baik.

## Struktur Route

### 1. Authentication Routes
**Prefix:** `/`
**Middleware:** Tidak ada (public access)

```php
// Login Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Register Routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
```

#### Fitur
- **Login Form**: Menampilkan form login
- **Login Process**: Memproses autentikasi user
- **Register Form**: Menampilkan form registrasi
- **Register Process**: Memproses registrasi user baru
- **Logout**: Mengakhiri session user

#### Security
- CSRF protection pada semua POST routes
- Input validation pada controller
- Session management

---

### 2. Dashboard Routes
**Prefix:** `/dashboard`
**Middleware:** `admin`

```php
Route::get('/dashboard', function () {
    return view('dashboard', ['titleShop' => 'RAVAZKA - Dashboard']);
})->middleware('admin')->name('dashboard');
```

#### Fitur
- **Admin Dashboard**: Halaman utama admin dengan quick access
- **Role-based Access**: Hanya admin yang dapat mengakses

---

### 3. Public Pages
**Prefix:** `/`
**Middleware:** Tidak ada (public access)

```php
// Homepage
Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index']);

// Static Pages
Route::get('/about', function () {
    return view('about', ['titleShop' => 'RAVAZKA']);
});

// Contact Pages
Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact/send', [\App\Http\Controllers\ContactController::class, 'send'])->name('contact.send');

// Product Catalog
Route::get('/products', [\App\Http\Controllers\Customer\ProductController::class, 'index'])->name('customer.products');

// Product Detail
Route::get('/products/{slug}', function ($slug) {
    $product = Product::where('slug', $slug)->firstOrFail();
    return view('product', ['titleShop' => 'RAVAZKA', 'product' => $product]);
})->name('customer.product.detail');
```

#### Pages
- **Homepage**: Landing page dengan hero section dan kategori
- **About**: Halaman tentang perusahaan
- **Contact**: Form kontak dan informasi perusahaan
- **Products**: Katalog produk dengan filter dan search
- **Product Detail**: Detail produk individual

---

### 4. Customer Order Routes
**Prefix:** `/orders`
**Middleware:** `auth`
**Namespace:** `customer.orders.`

```php
Route::prefix('orders')->name('customer.orders.')->middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\Customer\OrderController::class, 'index'])->name('index');
    Route::get('/track', [\App\Http\Controllers\Customer\OrderController::class, 'track'])->name('track');
    Route::get('/{orderNumber}', [\App\Http\Controllers\Customer\OrderController::class, 'show'])->name('show');
    Route::post('/{order}/upload-payment', [\App\Http\Controllers\Customer\OrderController::class, 'uploadPaymentProof'])->name('upload-payment');
    Route::post('/{order}/upload-delivery', [\App\Http\Controllers\Customer\OrderController::class, 'uploadDeliveryProof'])->name('upload-delivery');
    Route::post('/{order}/mark-completed', [\App\Http\Controllers\Customer\OrderController::class, 'markAsCompleted'])->name('mark-completed');
});
```

#### Endpoints
| Method | URI | Action | Description |
|--------|-----|--------|--------------|
| GET | `/orders` | index | Daftar pesanan customer |
| GET | `/orders/track` | track | Lacak pesanan |
| GET | `/orders/{orderNumber}` | show | Detail pesanan |
| POST | `/orders/{order}/upload-payment` | uploadPaymentProof | Upload bukti pembayaran |
| POST | `/orders/{order}/upload-delivery` | uploadDeliveryProof | Upload bukti pengiriman |
| POST | `/orders/{order}/mark-completed` | markAsCompleted | Tandai pesanan selesai |

#### Features
- **Order History**: Riwayat pesanan customer
- **Order Tracking**: Pelacakan status pesanan
- **Payment Proof**: Upload bukti pembayaran
- **Delivery Confirmation**: Konfirmasi penerimaan barang
- **Order Completion**: Penyelesaian pesanan

---

### 5. Testimonial Routes
**Prefix:** `/testimonials`
**Middleware:** `auth`
**Namespace:** `customer.testimonials.`

```php
Route::prefix('testimonials')->name('customer.testimonials.')->middleware('auth')->group(function () {
    Route::post('/store', [\App\Http\Controllers\TestimonialController::class, 'store'])->name('store');
});
```

#### Features
- **Create Testimonial**: Membuat testimoni untuk pesanan yang sudah selesai
- **Validation**: Validasi kepemilikan pesanan dan status

---

### 6. Inventory Management Routes
**Prefix:** `/inventory`
**Middleware:** `admin`

```php
Route::prefix('inventory')->middleware('admin')->group(function () {
    // Main inventory routes
    Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/create', function () { /* ... */ })->name('inventory.create');
    Route::post('/store', function () { /* ... */ })->name('inventory.store');
    Route::get('/edit/{id}', function ($id) { /* ... */ })->name('inventory.edit');
    Route::put('/update/{id}', function ($id) { /* ... */ })->name('inventory.update');
    Route::delete('/destroy/{id}', function ($id) { /* ... */ })->name('inventory.destroy');
    
    // Reports
    Route::get('/report', [InventoryController::class, 'report'])->name('inventory.report');
    Route::get('/reports/stock', function () { /* ... */ })->name('inventory.reports.stock');
    
    // Stock management
    Route::post('/adjust-stock/{id}', function ($id) { /* ... */ })->name('inventory.adjust-stock');
    
    // Export
    Route::get('/export', function () { /* ... */ })->name('inventory.export');
    
    // Detail
    Route::get('/{code}', function ($code) { /* ... */ })->name('inventory.detail');
});
```

#### CRUD Operations
| Method | URI | Action | Description |
|--------|-----|--------|--------------|
| GET | `/inventory` | index | Daftar inventaris dengan filter |
| GET | `/inventory/create` | create | Form tambah item |
| POST | `/inventory/store` | store | Simpan item baru |
| GET | `/inventory/edit/{id}` | edit | Form edit item |
| PUT | `/inventory/update/{id}` | update | Update item |
| DELETE | `/inventory/destroy/{id}` | destroy | Hapus item |

#### Advanced Features
| Method | URI | Action | Description |
|--------|-----|--------|--------------|
| GET | `/inventory/report` | report | Laporan inventaris |
| GET | `/inventory/reports/stock` | stockReport | Laporan stok detail |
| POST | `/inventory/adjust-stock/{id}` | adjustStock | Adjust stok per ukuran |
| GET | `/inventory/export` | export | Export data Excel |
| GET | `/inventory/{code}` | detail | Detail item by code |

#### Stock Management Features
- **Multi-size Support**: Manajemen stok per ukuran
- **Stock Adjustment**: Penambahan/pengurangan stok manual
- **Stock History**: Riwayat perubahan stok
- **Low Stock Alerts**: Peringatan stok rendah
- **Automatic Sync**: Sinkronisasi stok inventory dengan products

#### Validation Rules
```php
// Create/Update Validation
[
    'code' => 'required|string|max:50|unique:inventories,code',
    'name' => 'required|string|max:255',
    'category' => 'required|string|max:100',
    'stock' => 'required|integer|min:0',
    'min_stock' => 'required|integer|min:0',
    'purchase_price' => 'required|numeric|min:0',
    'selling_price' => 'required|numeric|min:0',
    'supplier' => 'nullable|string|max:255',
    'location' => 'nullable|string|max:255',
    'sizes_available' => 'nullable|array',
    'description' => 'nullable|string',
]

// Stock Adjustment Validation
[
    'adjustment_type' => 'required|in:increase,decrease',
    'size' => 'required|string',
    'quantity' => 'required|integer|min:1',
    'notes' => 'nullable|string|max:255'
]
```

---

### 7. Cart Routes
**Prefix:** `/cart`
**Middleware:** Tidak ada (mendukung guest dan user login)

```php
Route::prefix('cart')->group(function () {
    Route::get('/', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{product}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::put('/update/{cart}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{cart}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
    Route::get('/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/process-order', [\App\Http\Controllers\CartController::class, 'processOrder'])->name('cart.process-order');
});

// Cart count endpoint
Route::get('/cart/count', [\App\Http\Controllers\CartController::class, 'getCartCount'])->name('cart.count');
```

#### Cart Operations
| Method | URI | Action | Description |
|--------|-----|--------|--------------|
| GET | `/cart` | index | Tampilkan keranjang |
| POST | `/cart/add/{product}` | add | Tambah produk ke keranjang |
| PUT | `/cart/update/{cart}` | update | Update quantity item |
| DELETE | `/cart/remove/{cart}` | remove | Hapus item dari keranjang |
| DELETE | `/cart/clear` | clear | Kosongkan keranjang |
| GET | `/cart/checkout` | checkout | Halaman checkout |
| POST | `/cart/process-order` | processOrder | Proses pesanan |
| GET | `/cart/count` | getCartCount | Get jumlah item (AJAX) |

#### Features
- **Guest Cart Support**: Keranjang untuk user yang belum login
- **Session Management**: Penyimpanan cart dalam session
- **Stock Validation**: Validasi ketersediaan stok
- **Real-time Updates**: Update cart count secara real-time
- **Checkout Process**: Proses pemesanan lengkap

---

### 8. Admin Order Management Routes
**Prefix:** `/admin/orders`
**Middleware:** `admin`
**Namespace:** `admin.orders.`

```php
Route::prefix('admin/orders')->middleware('admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::post('/{order}/payment-proof', [\App\Http\Controllers\Admin\OrderController::class, 'uploadPaymentProof'])->name('admin.orders.upload-payment-proof');
    Route::post('/{order}/delivery-proof', [\App\Http\Controllers\Admin\OrderController::class, 'uploadDeliveryProof'])->name('admin.orders.upload-delivery-proof');
    Route::delete('/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('admin.orders.destroy');
});
```

#### Order Management
| Method | URI | Action | Description |
|--------|-----|--------|--------------|
| GET | `/admin/orders` | index | Daftar semua pesanan |
| GET | `/admin/orders/{order}` | show | Detail pesanan |
| PATCH | `/admin/orders/{order}/status` | updateStatus | Update status pesanan |
| POST | `/admin/orders/{order}/payment-proof` | uploadPaymentProof | Upload bukti pembayaran |
| POST | `/admin/orders/{order}/delivery-proof` | uploadDeliveryProof | Upload bukti pengiriman |
| DELETE | `/admin/orders/{order}` | destroy | Hapus pesanan |

#### Order Status Flow
```
Pending → Payment Pending → Payment Verified → Processing → Packaged → Shipped → Delivered → Completed
                                    ↓
                               Cancelled (any time)
```

#### Features
- **Order Filtering**: Filter berdasarkan status
- **Order Search**: Pencarian berdasarkan order number, nama, phone
- **Status Management**: Update status dengan timestamp
- **File Upload**: Upload bukti pembayaran dan pengiriman
- **Stock Management**: Otomatis kurangi stok saat delivered
- **Order Statistics**: Statistik pesanan per status

---

### 9. Sales Report Routes
**Prefix:** `/admin/sales`
**Middleware:** `auth`
**Namespace:** `admin.sales.`

```php
Route::prefix('admin/sales')->name('admin.sales.')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\SalesReportController::class, 'index'])->name('index');
    Route::get('/export-pdf', [\App\Http\Controllers\Admin\SalesReportController::class, 'exportPdf'])->name('export-pdf');
    Route::get('/data', [\App\Http\Controllers\Admin\SalesReportController::class, 'getData'])->name('data');
});
```

#### Sales Reporting
| Method | URI | Action | Description |
|--------|-----|--------|--------------|
| GET | `/admin/sales` | index | Dashboard laporan penjualan |
| GET | `/admin/sales/export-pdf` | exportPdf | Export laporan ke PDF |
| GET | `/admin/sales/data` | getData | Get data penjualan (AJAX) |

#### Features
- **Sales Dashboard**: Overview penjualan dengan grafik
- **Date Range Filter**: Filter berdasarkan periode
- **PDF Export**: Export laporan ke format PDF
- **AJAX Data Loading**: Loading data secara asinkron
- **Sales Analytics**: Analisis penjualan dan trend

---

## Middleware Usage

### 1. Admin Middleware
**File:** `app/Http/Middleware/AdminMiddleware.php`
**Usage:** Membatasi akses hanya untuk user dengan role admin

```php
// Routes yang menggunakan admin middleware:
- /dashboard
- /inventory/*
- /admin/orders/*
```

### 2. Auth Middleware
**Built-in Laravel middleware**
**Usage:** Membatasi akses hanya untuk user yang sudah login

```php
// Routes yang menggunakan auth middleware:
- /orders/*
- /testimonials/*
- /admin/sales/* (dengan auth, bukan admin)
```

### 3. Guest Routes
**No middleware**
**Usage:** Dapat diakses oleh semua user (guest dan authenticated)

```php
// Routes tanpa middleware:
- / (homepage)
- /about
- /contact
- /products
- /products/{slug}
- /cart/* (mendukung guest cart)
```

---

## Route Naming Convention

### 1. Resource Routes
```php
// Pattern: {resource}.{action}
inventory.index     // GET /inventory
inventory.create    // GET /inventory/create
inventory.store     // POST /inventory/store
inventory.show      // GET /inventory/{id}
inventory.edit      // GET /inventory/edit/{id}
inventory.update    // PUT /inventory/update/{id}
inventory.destroy   // DELETE /inventory/destroy/{id}
```

### 2. Nested Routes
```php
// Pattern: {parent}.{child}.{action}
customer.orders.index       // GET /orders
customer.orders.show        // GET /orders/{orderNumber}
admin.orders.index          // GET /admin/orders
admin.orders.show           // GET /admin/orders/{order}
```

### 3. Action Routes
```php
// Pattern: {resource}.{specific-action}
cart.add                    // POST /cart/add/{product}
cart.update                 // PUT /cart/update/{cart}
inventory.adjust-stock      // POST /inventory/adjust-stock/{id}
admin.orders.update-status  // PATCH /admin/orders/{order}/status
```

---

## Route Parameters

### 1. Model Binding
```php
// Implicit binding
Route::get('/orders/{order}', [OrderController::class, 'show']);
// Laravel otomatis resolve Order model berdasarkan ID

// Explicit binding (dalam RouteServiceProvider)
Route::model('order', Order::class);
```

### 2. Custom Parameters
```php
// Slug parameter
Route::get('/products/{slug}', function ($slug) {
    $product = Product::where('slug', $slug)->firstOrFail();
    return view('product', compact('product'));
});

// Code parameter
Route::get('/inventory/{code}', function ($code) {
    $item = Inventory::where('code', $code)->firstOrFail();
    return view('inventory.detail', compact('item'));
});
```

### 3. Route Constraints
```php
// Numeric constraint
Route::get('/inventory/edit/{id}', function ($id) {
    // $id harus numeric
})->where('id', '[0-9]+');

// Alphanumeric constraint
Route::get('/products/{slug}', function ($slug) {
    // $slug harus alphanumeric dengan dash
})->where('slug', '[a-zA-Z0-9-]+');
```

---

## Security Considerations

### 1. CSRF Protection
```php
// Semua POST, PUT, PATCH, DELETE routes otomatis protected
// Menggunakan @csrf directive di Blade templates
<form method="POST">
    @csrf
    <!-- form fields -->
</form>
```

### 2. Route Model Binding Security
```php
// Menggunakan firstOrFail() untuk 404 jika tidak ditemukan
$product = Product::where('slug', $slug)->firstOrFail();

// Policy authorization (jika diperlukan)
$this->authorize('view', $order);
```

### 3. Input Validation
```php
// Validation di controller atau form request
$validated = request()->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users',
    'password' => 'required|min:6|confirmed'
]);
```

---

## Performance Optimization

### 1. Route Caching
```bash
# Cache routes untuk production
php artisan route:cache

# Clear route cache
php artisan route:clear
```

### 2. Eager Loading
```php
// Dalam controller, gunakan eager loading
$orders = Order::with(['user', 'orderItems.product'])->get();
```

### 3. Query Optimization
```php
// Gunakan select() untuk field yang diperlukan saja
$products = Product::select('id', 'name', 'price', 'slug')->get();

// Gunakan pagination untuk data besar
$orders = Order::paginate(15);
```

---

## API Endpoints (AJAX)

### 1. Cart Count
```javascript
// GET /cart/count
// Returns: { count: number }
fetch('/cart/count')
    .then(response => response.json())
    .then(data => {
        document.getElementById('cart-count').textContent = data.count;
    });
```

### 2. Sales Data
```javascript
// GET /admin/sales/data?start_date=2024-01-01&end_date=2024-01-31
// Returns: { sales: array, total: number, ... }
fetch('/admin/sales/data?' + new URLSearchParams({
    start_date: '2024-01-01',
    end_date: '2024-01-31'
}))
.then(response => response.json())
.then(data => {
    // Update charts/tables
});
```

---

## Error Handling

### 1. 404 Handling
```php
// Menggunakan firstOrFail() untuk otomatis throw 404
$product = Product::where('slug', $slug)->firstOrFail();

// Custom 404 handling
if (!$item) {
    return redirect()->route('inventory.index')
        ->with('error', 'Item tidak ditemukan.');
}
```

### 2. Validation Errors
```php
// Redirect dengan error messages
return redirect()->back()
    ->withErrors($validator)
    ->withInput();

// JSON response untuk AJAX
return response()->json([
    'success' => false,
    'errors' => $validator->errors()
], 422);
```

### 3. Authorization Errors
```php
// Middleware akan redirect ke login jika tidak authenticated
// AdminMiddleware akan redirect dengan error jika bukan admin
return redirect()->route('login')
    ->with('error', 'Anda harus login terlebih dahulu.');
```

---

## Testing Routes

### 1. Feature Tests
```php
// Test GET route
$response = $this->get('/products');
$response->assertStatus(200);

// Test POST route dengan authentication
$user = User::factory()->create();
$response = $this->actingAs($user)
    ->post('/cart/add/1', ['quantity' => 2]);
$response->assertRedirect();

// Test middleware
$response = $this->get('/admin/orders');
$response->assertRedirect('/login');
```

### 2. Route Testing
```php
// Test route exists
$this->assertTrue(Route::has('inventory.index'));

// Test route parameters
$url = route('customer.product.detail', ['slug' => 'product-slug']);
$this->assertEquals('/products/product-slug', $url);
```

Dokumentasi ini memberikan gambaran lengkap tentang sistem routing dalam aplikasi RAVAZKA, termasuk struktur, fitur, keamanan, dan best practices yang diimplementasikan.