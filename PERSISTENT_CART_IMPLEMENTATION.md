# Implementasi Persistent Cart

## Deskripsi
Implementasi fitur persistent cart yang memungkinkan produk dalam cart tersimpan walaupun user logout, dan akan hilang ketika sudah dikirim ke WhatsApp.

## Perubahan yang Dilakukan

### 1. Database Migration
- **File**: `database/migrations/2025_01_15_000000_add_user_id_to_carts_table.php`
- **Perubahan**: Menambahkan kolom `user_id` nullable dengan foreign key constraint ke tabel `carts`
- **Index**: Composite index pada `(user_id, product_id)` untuk optimasi query

### 2. Model Cart
- **File**: `app/Models/Cart.php`
- **Perubahan**:
  - Menambahkan `user_id` ke `$fillable`
  - Menambahkan relationship `belongsTo` dengan User model
  - Method `getCartItems($userId, $sessionId)` untuk mengambil cart items berdasarkan user atau session
  - Method `mergeSessionToUser($userId, $sessionId)` untuk merge cart session ke user cart saat login

### 3. CartController
- **File**: `app/Http/Controllers/CartController.php`
- **Perubahan**:
  - Method `index()`: Menggunakan `Cart::getCartItems()` untuk dual support
  - Method `add()`: Menyimpan cart dengan `user_id` untuk authenticated user
  - Method `clear()`: Menghapus cart berdasarkan `user_id` atau `session_id`
  - Method `checkout()`: Menggunakan `Cart::getCartItems()` untuk dual support
  - Method `processOrder()`: Menggunakan `Cart::getCartItems()` dan dual deletion logic
  - Method `getCartCount()`: Menggunakan `Cart::getCartItems()` untuk dual support

### 4. AuthController
- **File**: `app/Http/Controllers/AuthController.php`
- **Perubahan**:
  - Import `Cart` model dan `Session` facade
  - Method `login()`: Menambahkan `Cart::mergeSessionToUser()` setelah login berhasil
  - Method `register()`: Menambahkan `Cart::mergeSessionToUser()` setelah register berhasil

## Cara Kerja

### Guest User (Belum Login)
- Cart disimpan berdasarkan `session_id`
- `user_id` bernilai `NULL`

### Authenticated User (Sudah Login)
- Cart disimpan berdasarkan `user_id`
- `session_id` tetap ada untuk backward compatibility

### Saat Login/Register
- Cart session guest akan di-merge ke user cart
- Jika produk sudah ada di user cart, quantity akan ditambahkan
- Jika produk belum ada, akan ditambahkan sebagai item baru
- Cart session guest akan dihapus setelah merge

### Saat Logout
- Cart tetap tersimpan di database dengan `user_id`
- Saat login kembali, cart akan muncul lagi

### Saat Order Dikirim ke WhatsApp
- Cart akan dihapus otomatis setelah order berhasil diproses
- Berlaku untuk guest maupun authenticated user

## Testing
1. Tambahkan produk ke cart sebagai guest
2. Login/register - cart guest akan merge ke user cart
3. Logout - cart tetap tersimpan
4. Login kembali - cart muncul lagi
5. Checkout dan kirim ke WhatsApp - cart akan terhapus

## Kompatibilitas
- Backward compatible dengan sistem session-based cart yang lama
- Mendukung dual mode: session untuk guest, user_id untuk authenticated user
- Tidak mempengaruhi flow WhatsApp yang sudah ada