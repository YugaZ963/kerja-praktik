# Dokumentasi Controllers - RAVAZKA

## Overview
Direktori Controllers berisi semua controller yang menangani logika bisnis dan alur aplikasi dalam sistem RAVAZKA. Controllers mengikuti pola MVC (Model-View-Controller) dan bertanggung jawab untuk memproses request HTTP, berinteraksi dengan Models, dan mengembalikan response yang sesuai.

## Struktur Folder
```
app/Http/Controllers/
├── Admin/
│   ├── OrderController.php          # Manajemen pesanan admin
│   └── salesreportcontroller.php    # Laporan penjualan
├── Customer/
│   ├── OrderController.php          # Pesanan customer
│   └── ProductController.php        # Produk customer
├── AuthController.php               # Autentikasi
├── CartController.php               # Keranjang belanja
├── ContactController.php            # Kontak
├── Controller.php                   # Base controller
├── InventoryController.php          # Manajemen inventaris
├── ProductController.php            # Produk umum
├── TestimonialController.php        # Testimoni
└── WelcomeController.php            # Halaman utama
```

## Controllers Detail

### 1. AuthController
**File:** `AuthController.php`
**Namespace:** `App\Http\Controllers`
**Fungsi Utama:** Menangani autentikasi pengguna (login, register, logout)

#### Dependencies
- `App\Models\User`
- `App\Models\Cart`
- `Illuminate\Support\Facades\Auth`
- `Illuminate\Support\Facades\Hash`
- `Illuminate\Support\Facades\Session`
- `Illuminate\Support\Facades\Validator`

#### Methods
- **`showLoginForm()`**: Menampilkan form login
- **`login(Request $request)`**: Memproses login pengguna
  - Validasi email dan password
  - Autentikasi dengan remember me option
  - Merge cart session ke user cart setelah login
  - Redirect berdasarkan role (admin ke dashboard, user ke intended URL)
- **`showRegisterForm()`**: Menampilkan form registrasi
- **`register(Request $request)`**: Memproses registrasi pengguna baru
  - Validasi data registrasi
  - Hash password
  - Set role default sebagai 'user'
  - Auto login setelah registrasi
  - Merge cart session ke user cart
- **`logout(Request $request)`**: Memproses logout
  - Invalidate session
  - Regenerate CSRF token

#### Fitur Keamanan
- Password hashing dengan bcrypt
- Session regeneration setelah login
- CSRF protection
- Input validation
- Role-based redirection

#### Business Logic
- Cart session merge: Menggabungkan keranjang guest dengan user setelah login/register
- Intended URL handling: Redirect ke halaman yang dimaksud setelah login
- Role-based access: Admin diarahkan ke dashboard, user ke halaman utama

---

### 2. CartController
**File:** `CartController.php`
**Namespace:** `App\Http\Controllers`
**Fungsi Utama:** Menangani operasi keranjang belanja dan checkout

#### Dependencies
- `App\Models\Cart`
- `App\Models\Product`
- `App\Models\Order`
- `App\Models\OrderItem`
- `Illuminate\Support\Facades\Session`
- `Illuminate\Support\Facades\DB`
- `Illuminate\Support\Facades\Auth`

#### Methods
- **`index()`**: Menampilkan halaman keranjang
  - Mengambil cart items berdasarkan user/session
  - Menghitung total dan jumlah item
- **`add(Request $request, $productId)`**: Menambah produk ke keranjang
  - Cek stok produk
  - Update quantity jika produk sudah ada
  - Buat cart item baru jika belum ada
- **`update(Request $request, $cartId)`**: Update quantity item keranjang
  - Validasi stok
  - Hapus item jika quantity <= 0
- **`remove($cartId)`**: Hapus item dari keranjang
- **`clear()`**: Kosongkan keranjang
- **`checkout()`**: Menampilkan halaman checkout
- **`processOrder(Request $request)`**: Memproses pesanan
  - Validasi data checkout
  - Database transaction untuk konsistensi
  - Cek stok sebelum membuat order
  - Generate order number
  - Buat order dan order items
  - Generate pesan WhatsApp
  - Kosongkan keranjang setelah berhasil
  - Redirect ke WhatsApp

#### Fitur Utama
- **Session-based cart**: Mendukung keranjang untuk guest user
- **User-based cart**: Keranjang tersimpan untuk user login
- **Stock validation**: Validasi stok real-time
- **Transaction safety**: Menggunakan database transaction
- **WhatsApp integration**: Auto redirect ke WhatsApp untuk konfirmasi order
- **Order tracking**: Generate unique order number

#### Business Logic
- Dual cart system: Session untuk guest, database untuk user login
- Stock management: Validasi stok sebelum add/update
- Shipping cost calculation: Express shipping +15.000
- Order workflow: Pending → WhatsApp confirmation

---

### 3. ProductController
**File:** `ProductController.php`
**Namespace:** `App\Http\Controllers`
**Fungsi Utama:** Menampilkan produk untuk customer

#### Dependencies
- `App\Models\Product`

#### Methods
- **`index(Request $request)`**: Menampilkan daftar produk dengan filter
  - Filter berdasarkan kategori
  - Filter berdasarkan ukuran
  - Sorting (harga, nama)
  - Pagination (12 item per halaman)

#### Fitur Filter & Sorting
- **Category filter**: Filter produk berdasarkan kategori
- **Size filter**: Filter berdasarkan ukuran (S, M, L, XL, XXL)
- **Sorting options**:
  - Price ascending/descending
  - Name ascending/descending
  - Latest (default)

---

### 4. InventoryController
**File:** `InventoryController.php`
**Namespace:** `App\Http\Controllers`
**Fungsi Utama:** Menangani manajemen inventaris untuk admin

#### Dependencies
- `App\Models\Inventory`
- `App\Models\Product`

#### Methods
- **`index(Request $request)`**: Menampilkan daftar inventaris
  - Multiple filters (search, category, status, price range, date)
  - Advanced sorting options
  - Pagination (15 item per halaman)
- **`report(Request $request)`**: Generate laporan inventaris
  - Filter untuk laporan
  - Stock status analysis
  - Value calculation

#### Fitur Filter Advanced
- **Search**: Nama, kategori, deskripsi, kode
- **Category filter**: Filter berdasarkan kategori produk
- **Stock status**:
  - Low stock (≤ 100)
  - Out of stock (= 0)
  - Ready stock (> 100)
  - Critical stock (≤ 50)
- **Price range**: Min/max price filter
- **Date range**: Filter berdasarkan tanggal

#### Sorting Options
- Name, stock, price, category (ascending/descending)
- Latest (default)

---

### 5. Admin\OrderController
**File:** `Admin/OrderController.php`
**Namespace:** `App\Http\Controllers\Admin`
**Fungsi Utama:** Manajemen pesanan untuk admin

#### Dependencies
- `App\Models\Order`
- `App\Models\OrderItem`
- `App\Models\Product`
- `Illuminate\Support\Facades\Storage`
- `Illuminate\Support\Facades\Log`

#### Methods
- **`index(Request $request)`**: Daftar pesanan dengan filter
  - Filter berdasarkan status
  - Search berdasarkan order number, nama, phone
  - Status counts untuk dashboard
- **`show(Order $order)`**: Detail pesanan
  - Load relasi items dan user
- **`updateStatus(Request $request, Order $order)`**: Update status pesanan
  - Validasi status transition
  - Update timestamp berdasarkan status
  - Auto stock reduction saat delivered
- **`uploadPaymentProof(Request $request, Order $order)`**: Upload bukti pembayaran
- **`uploadDeliveryProof(Request $request, Order $order)`**: Upload bukti pengiriman
- **`destroy(Order $order)`**: Hapus pesanan
- **`reduceProductStock(Order $order)`**: Private method untuk kurangi stok
- **`getStatusCounts()`**: Private method untuk hitung status

#### Order Status Flow
1. **Pending**: Pesanan baru dibuat
2. **Payment Pending**: Menunggu pembayaran
3. **Payment Verified**: Pembayaran terverifikasi
4. **Processing**: Sedang diproses
5. **Packaged**: Sudah dikemas
6. **Shipped**: Sudah dikirim
7. **Delivered**: Sudah sampai (auto stock reduction)
8. **Completed**: Selesai
9. **Cancelled**: Dibatalkan

#### Fitur Utama
- **File upload**: Bukti pembayaran dan pengiriman
- **Stock management**: Auto stock reduction saat delivered
- **Logging**: Comprehensive logging untuk audit trail
- **Status tracking**: Timestamp untuk setiap status change

---

### 6. WelcomeController
**File:** `WelcomeController.php`
**Namespace:** `App\Http\Controllers`
**Fungsi Utama:** Menangani halaman utama/landing page

#### Dependencies
- `App\Models\Order`

#### Methods
- **`index()`**: Menampilkan halaman utama
  - Mengambil 3 pesanan terbaru yang completed/delivered
  - Untuk menampilkan social proof

---

### 7. TestimonialController
**File:** `TestimonialController.php`
**Namespace:** `App\Http\Controllers`
**Fungsi Utama:** Menangani testimoni customer

#### Dependencies
- `App\Models\Testimonial`
- `App\Models\Order`
- `Illuminate\Support\Facades\Auth`

#### Methods
- **`store(Request $request)`**: Menyimpan testimoni baru
  - Validasi ownership order
  - Cek status order (harus completed)
  - Prevent duplicate testimonial
  - Auto approve testimonial

#### Business Rules
- Hanya user pemilik order yang bisa beri testimoni
- Order harus berstatus 'completed'
- Satu order hanya bisa satu testimoni
- Auto approval untuk semua testimoni

---

## Design Patterns yang Digunakan

### 1. MVC Pattern
- **Model**: Data dan business logic
- **View**: Presentation layer (Blade templates)
- **Controller**: Request handling dan koordinasi

### 2. Repository Pattern (Implicit)
- Eloquent ORM sebagai repository layer
- Model methods untuk complex queries

### 3. Service Layer Pattern
- Business logic encapsulation dalam controller methods
- Transaction handling untuk data consistency

### 4. Middleware Pattern
- Authentication middleware
- CSRF protection
- Admin role checking

## Security Considerations

### 1. Input Validation
- Form request validation
- CSRF token protection
- File upload validation

### 2. Authentication & Authorization
- Session-based authentication
- Role-based access control
- Route protection dengan middleware

### 3. Data Protection
- Password hashing
- SQL injection prevention (Eloquent ORM)
- XSS protection (Blade templating)

### 4. File Security
- File type validation
- File size limits
- Secure file storage

## Performance Optimization

### 1. Database Optimization
- Eager loading dengan `with()`
- Pagination untuk large datasets
- Efficient queries dengan query builder

### 2. Caching Strategy
- Session-based cart caching
- Query result caching (implicit)

### 3. Memory Management
- Pagination untuk menghindari memory overflow
- Efficient data structures

## Error Handling

### 1. Exception Handling
- Try-catch blocks untuk critical operations
- Database transaction rollback
- Comprehensive error logging

### 2. User Feedback
- Flash messages untuk user feedback
- Validation error display
- Success/error notifications

## Integration Points

### 1. External Services
- **WhatsApp API**: Order confirmation
- **File Storage**: Payment/delivery proof upload

### 2. Internal Services
- **Authentication**: Laravel Auth facade
- **Session Management**: Laravel Session
- **Database**: Eloquent ORM

## Best Practices Implemented

1. **Single Responsibility**: Setiap controller memiliki tanggung jawab spesifik
2. **DRY Principle**: Reusable methods dan logic
3. **SOLID Principles**: Loose coupling, high cohesion
4. **Security First**: Input validation, authentication, authorization
5. **Error Handling**: Comprehensive error handling dan logging
6. **Performance**: Efficient queries dan pagination
7. **Maintainability**: Clean code, proper documentation

## Contoh Penggunaan

### 1. Authentication Flow
```php
// Login
POST /login
{
    "email": "user@example.com",
    "password": "password",
    "remember": true
}

// Register
POST /register
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

### 2. Cart Operations
```php
// Add to cart
POST /cart/add/{productId}
{
    "quantity": 2
}

// Update cart
PUT /cart/update/{cartId}
{
    "quantity": 3
}

// Checkout
POST /cart/checkout
{
    "name": "John Doe",
    "phone": "08123456789",
    "address": "Jl. Example No. 123",
    "payment_method": "bri",
    "shipping_method": "express",
    "notes": "Catatan khusus"
}
```

### 3. Admin Order Management
```php
// Update order status
PUT /admin/orders/{order}/status
{
    "status": "shipped",
    "admin_notes": "Dikirim via JNE",
    "tracking_number": "JNE123456789"
}

// Upload payment proof
POST /admin/orders/{order}/payment-proof
{
    "payment_proof": [file]
}
```

Dokumentasi ini memberikan gambaran lengkap tentang semua Controllers dalam sistem RAVAZKA, termasuk fungsi, dependencies, business logic, dan best practices yang diimplementasikan.