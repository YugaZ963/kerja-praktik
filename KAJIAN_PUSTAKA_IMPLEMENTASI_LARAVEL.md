# Kajian Pustaka - Implementasi Laravel Framework pada Sistem E-Commerce RAVAZKA

## Overview

Proyek RAVAZKA merupakan sistem e-commerce untuk toko seragam sekolah yang dibangun menggunakan Laravel 11 dengan menerapkan arsitektur Model-View-Controller (MVC). Implementasi Laravel dalam proyek ini mencakup seluruh komponen utama framework beserta fitur-fitur pendukungnya untuk menciptakan aplikasi web yang robust, scalable, dan secure.

## Komponen Utama Laravel yang Diimplementasikan

### 1. Model (Eloquent ORM)

Model dalam proyek RAVAZKA bertugas menangani data dan business logic aplikasi. Implementasi Eloquent ORM memungkinkan interaksi dengan database menggunakan pendekatan object-oriented yang intuitif.

**Contoh Implementasi Model:**
```php
// app/Models/Product.php
class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'price', 'weight', 'description', 
        'stock', 'size', 'category', 'inventory_id', 'image'
    ];

    // Relasi dengan inventory
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    // Business logic method
    public function updateInventoryStock()
    {
        if ($this->inventory_id) {
            $this->inventory->updateStock();
            $this->inventory->updateFromProducts();
        }
    }
}
```

**Fitur Model yang Diimplementasikan:**
- **Mass Assignment Protection**: Menggunakan `$fillable` untuk whitelist attributes yang dapat diisi secara massal
- **Eloquent Relationships**: Implementasi relasi One-to-Many, Many-to-One, dan Many-to-Many antar entitas
- **Business Logic Encapsulation**: Method khusus untuk operasi bisnis kompleks seperti `updateInventoryStock()`
- **Data Casting**: Automatic casting untuk tipe data seperti datetime, decimal, dan boolean
- **Model Events**: Observer pattern untuk auto-sync data antar model

**Model yang Diimplementasikan:**
- `User.php` - Manajemen pengguna dengan role-based access
- `Product.php` - Produk seragam dengan relasi inventory
- `Inventory.php` - Manajemen stok dan inventaris
- `Order.php` - Pesanan dengan status tracking
- `OrderItem.php` - Item dalam pesanan
- `Cart.php` - Keranjang belanja dengan session support

### 2. View (Blade Templating Engine)

View dalam proyek RAVAZKA menggunakan Blade templating engine untuk menyajikan data kepada pengguna dengan tampilan HTML yang dinamis dan responsive.

**Contoh Implementasi Blade Template:**
```blade
{{-- resources/views/cart/index.blade.php --}}
@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

@section('content')
    <div class="container mt-4">
        <x-navbar />

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($cartItems->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-cart-x display-1 text-muted"></i>
                <h3 class="mt-3 text-muted">Keranjang Kosong</h3>
                <p class="text-muted">Belum ada produk di keranjang Anda</p>
                <a href="{{ route('customer.products') }}" class="btn btn-primary">
                    <i class="bi bi-shop"></i> Mulai Belanja
                </a>
            </div>
        @else
            @foreach($cartItems as $item)
                <div class="border-bottom p-3" data-item-id="{{ $item->id }}">
                    {{-- Item content --}}
                </div>
            @endforeach
        @endif
    </div>
@endsection
```

**Fitur Blade yang Diimplementasikan:**
- **Template Inheritance**: `@extends`, `@section`, `@yield` untuk struktur layout yang konsisten
- **Component System**: `<x-component>` untuk reusable UI components
- **Conditional Rendering**: `@if`, `@auth`, `@guest` untuk tampilan kondisional
- **Loops**: `@foreach`, `@forelse` untuk iterasi data
- **CSRF Protection**: `@csrf` directive untuk keamanan form
- **Asset Management**: `{{ asset() }}` untuk URL asset yang konsisten
- **Localization**: `{{ __() }}` untuk multi-language support

**Struktur View yang Diimplementasikan:**
- `layouts/` - Layout utama (app.blade.php, customer.blade.php)
- `admin/` - Interface admin (dashboard, inventory, orders)
- `customer/` - Interface customer (products, cart, orders)
- `auth/` - Authentication pages (login, register)
- `components/` - Reusable components (navbar, alerts)

### 3. Controller

Controller berperan sebagai penghubung antara Model dan View, menangani request HTTP dan mengatur response yang dikirim ke pengguna.

**Contoh Implementasi Controller:**
```php
// app/Http/Controllers/Customer/CartController.php
class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();
        $cartItems = Cart::getCartItems($userId, $sessionId);
        $total = $cartItems->sum('total');
        $itemCount = $cartItems->sum('quantity');

        return view('cart.index', [
            'titleShop' => '🛒 Keranjang Belanja - RAVAZKA',
            'cartItems' => $cartItems,
            'total' => $total,
            'itemCount' => $itemCount
        ]);
    }

    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $userId = Auth::id();
        $sessionId = Session::getId();
        $quantity = $request->input('quantity', 1);

        // Business logic untuk menambah item ke cart
        $cartQuery = Cart::where('product_id', $productId);
        
        if ($userId) {
            $cartQuery->where('user_id', $userId);
        } else {
            $cartQuery->where('session_id', $sessionId);
        }
        
        // Implementation continues...
    }
}
```

**Fitur Controller yang Diimplementasikan:**
- **Request Handling**: Menangani HTTP requests (GET, POST, PUT, DELETE)
- **Input Validation**: Validasi data input dari form dan AJAX
- **Authentication & Authorization**: Middleware untuk kontrol akses
- **Database Transactions**: Untuk operasi data yang kompleks
- **File Upload**: Handling upload gambar produk dan bukti pembayaran
- **Session Management**: Manajemen session untuk cart dan user state
- **Error Handling**: Try-catch blocks dengan user-friendly error messages

**Controller yang Diimplementasikan:**
- `Admin/InventoryController.php` - CRUD inventaris dengan filtering
- `Admin/OrderController.php` - Manajemen pesanan dan status tracking
- `Customer/CartController.php` - Keranjang belanja dengan session support
- `Customer/OrderController.php` - Checkout dan order tracking
- `Auth/AuthController.php` - Authentication dan registration

## Fitur Pendukung Laravel yang Diimplementasikan

### 1. Routing

Sistem routing Laravel memungkinkan pengaturan rute URL dengan mudah dan menghubungkan request HTTP ke Controller yang sesuai.

**Contoh Implementasi Routing:**
```php
// routes/web.php
// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes dengan Middleware
Route::middleware('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard', ['titleShop' => 'RAVAZKA - Dashboard']);
    })->name('dashboard');
    
    Route::resource('inventory', InventoryController::class);
});

// Customer Routes
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
});

// Route Model Binding
Route::get('/products/{slug}', function ($slug) {
    $product = Product::where('slug', $slug)->firstOrFail();
    return view('product.detail', compact('product'));
});
```

**Fitur Routing yang Diimplementasikan:**
- **Route Groups**: Pengelompokan route dengan middleware dan prefix
- **Route Model Binding**: Automatic model resolution berdasarkan parameter
- **Named Routes**: Penamaan route untuk kemudahan maintenance
- **Route Parameters**: Dynamic parameters dengan constraints
- **Middleware Integration**: Authentication dan authorization pada route level

### 2. Eloquent ORM

Eloquent ORM memudahkan interaksi dengan database menggunakan Active Record pattern, mendukung operasi CRUD tanpa penulisan SQL manual.

**Fitur Eloquent yang Diimplementasikan:**
- **Query Builder**: Fluent interface untuk complex queries
- **Relationships**: One-to-One, One-to-Many, Many-to-Many relationships
- **Eager Loading**: Optimasi query dengan `with()` method
- **Scopes**: Local dan global scopes untuk reusable query logic
- **Mutators & Accessors**: Data transformation saat save/retrieve
- **Model Events**: Observer pattern untuk automatic operations

### 3. Migrations

Migrasi database memungkinkan pengelolaan struktur database menggunakan kode PHP, memudahkan kolaborasi tim dan deployment.

**Contoh Implementasi Migration:**
```php
// database/migrations/2025_06_07_032110_create_products_table.php
class CreateProductsTable extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->decimal('price');
            $table->string('description');
            $table->integer('stock');
            $table->string('size');
            $table->string('category');
            $table->unsignedBigInteger('inventory_id')->nullable();
            $table->foreign('inventory_id')->references('id')->on('inventories')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
}
```

**Fitur Migration yang Diimplementasikan:**
- **Schema Builder**: Fluent API untuk database schema definition
- **Foreign Key Constraints**: Referential integrity antar tabel
- **Index Management**: Primary keys, unique constraints, dan indexes
- **Rollback Support**: `down()` method untuk migration rollback
- **Column Modifiers**: Nullable, default values, auto-increment

### 4. Artisan Command-Line Tool

Artisan menyediakan berbagai perintah untuk otomatisasi tugas pengembangan dan maintenance aplikasi.

**Perintah Artisan yang Digunakan:**
```bash
# Database Operations
php artisan migrate
php artisan db:seed
php artisan migrate:refresh --seed

# Development Server
php artisan serve

# Code Generation
php artisan make:model Product -m
php artisan make:controller ProductController --resource
php artisan make:middleware AdminMiddleware

# Cache Management
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Implementasi Keamanan Laravel

### 1. Authentication & Authorization
- **Built-in Authentication**: Laravel Auth dengan session-based authentication
- **Role-based Access Control**: Admin dan Customer roles dengan middleware
- **Password Hashing**: Bcrypt hashing untuk password security
- **Remember Token**: Persistent login functionality

### 2. CSRF Protection
- **CSRF Tokens**: Automatic CSRF token generation dan validation
- **Blade Directive**: `@csrf` untuk form protection
- **AJAX Support**: CSRF token dalam AJAX requests

### 3. Input Validation
- **Form Request Validation**: Centralized validation logic
- **Custom Validation Rules**: Business-specific validation
- **File Upload Validation**: Type, size, dan security validation

### 4. SQL Injection Prevention
- **Eloquent ORM**: Automatic query parameter binding
- **Query Builder**: Parameterized queries
- **Mass Assignment Protection**: `$fillable` dan `$guarded` attributes

## Performance Optimization

### 1. Database Optimization
- **Eager Loading**: Menghindari N+1 query problem dengan `with()`
- **Query Optimization**: Efficient database queries dengan indexing
- **Pagination**: Laravel pagination untuk large datasets

### 2. Caching Strategy
- **Session-based Caching**: Cart persistence untuk guest users
- **Config Caching**: Production optimization dengan config cache
- **Route Caching**: Faster route resolution

### 3. Asset Management
- **Vite Integration**: Modern asset bundling dan hot reload
- **CSS/JS Minification**: Production asset optimization
- **Image Optimization**: Responsive images dengan proper sizing

## Kesimpulan

Implementasi Laravel Framework pada sistem e-commerce RAVAZKA mendemonstrasikan penggunaan komprehensif dari seluruh ekosistem Laravel. Arsitektur MVC yang diterapkan memisahkan concerns dengan jelas, dimana Model menangani data dan business logic melalui Eloquent ORM, View menyajikan interface yang responsive menggunakan Blade templating engine, dan Controller mengatur alur aplikasi dengan handling request yang robust.

Fitur-fitur pendukung seperti routing system, migrations, dan Artisan CLI tools mempercepat development process dan memastikan maintainability yang tinggi. Implementasi security features seperti CSRF protection, input validation, dan role-based access control menjamin keamanan aplikasi. Performance optimization melalui eager loading, caching strategy, dan proper database design menghasilkan aplikasi yang scalable dan responsive.

Proyek RAVAZKA berhasil memanfaatkan kekuatan Laravel Framework untuk menciptakan sistem e-commerce yang modern, secure, dan user-friendly, dengan arsitektur yang mendukung pertumbuhan bisnis dan kemudahan maintenance jangka panjang.