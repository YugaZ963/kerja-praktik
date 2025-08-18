# Database Documentation - RAVAZKA Project

## Overview

Dokumentasi ini menjelaskan struktur database, migrations, dan seeders yang digunakan dalam proyek RAVAZKA. Database menggunakan MySQL dengan Laravel Eloquent ORM untuk manajemen data.

## Database Schema

### Core Tables

#### 1. Users Table
- **File**: `0001_01_01_000000_create_users_table.php`
- **Purpose**: Menyimpan data pengguna sistem
- **Fields**:
  - `id` (Primary Key)
  - `name` (String) - Nama pengguna
  - `email` (String, Unique) - Email pengguna
  - `email_verified_at` (Timestamp, Nullable)
  - `password` (String) - Password terenkripsi
  - `role` (Enum: admin, user) - Role pengguna (ditambahkan via migration)
  - `remember_token`
  - `timestamps`

#### 2. Products Table
- **File**: `2025_06_07_032110_create_products_table.php`
- **Purpose**: Menyimpan data produk yang dijual
- **Fields**:
  - `id` (Primary Key)
  - `name` (String) - Nama produk
  - `slug` (String) - URL-friendly identifier
  - `price` (Decimal) - Harga jual
  - `description` (String) - Deskripsi produk
  - `stock` (Integer) - Stok tersedia
  - `size` (String) - Ukuran produk
  - `category` (String) - Kategori produk
  - `weight` (Decimal) - Berat produk (ditambahkan via migration)
  - `inventory_id` (Foreign Key) - Referensi ke inventories table
  - `timestamps`

#### 3. Inventories Table
- **File**: `2025_06_07_033343_create_inventories_table.php`
- **Purpose**: Manajemen inventaris dan stok master
- **Fields**:
  - `id` (Primary Key)
  - `code` (String) - Kode inventaris (INV-SD-001)
  - `name` (String) - Nama item inventaris
  - `category` (String) - Kategori inventaris
  - `stock` (Integer) - Total stok
  - `min_stock` (Integer) - Minimum stok alert
  - `purchase_price` (Decimal) - Harga beli
  - `selling_price` (Decimal) - Harga jual
  - `supplier` (String) - Nama supplier
  - `last_restock` (Date) - Tanggal restock terakhir
  - `sizes_available` (JSON) - Array ukuran tersedia
  - `location` (String) - Lokasi penyimpanan
  - `description` (Text) - Deskripsi detail
  - `stock_history` (JSON) - Riwayat perubahan stok
  - `timestamps`

#### 4. Carts Table
- **File**: `2025_08_04_052012_create_carts_table.php`
- **Purpose**: Keranjang belanja untuk session dan user
- **Fields**:
  - `id` (Primary Key)
  - `session_id` (String) - Session identifier
  - `user_id` (Foreign Key, Nullable) - User ID untuk logged users
  - `product_id` (Foreign Key) - Referensi ke products
  - `quantity` (Integer) - Jumlah item
  - `price` (Decimal) - Harga saat ditambahkan
  - `timestamps`
- **Indexes**: `[session_id, product_id]`, `[user_id, product_id]`

#### 5. Orders Table
- **File**: `2025_08_11_041114_create_orders_table.php`
- **Purpose**: Data pesanan pelanggan
- **Fields**:
  - `id` (Primary Key)
  - `order_number` (String, Unique) - Nomor pesanan
  - `user_id` (Foreign Key, Nullable) - User yang memesan
  - `customer_name` (String) - Nama pelanggan
  - `customer_phone` (String) - Nomor telepon
  - `customer_address` (Text) - Alamat pengiriman
  - `notes` (Text, Nullable) - Catatan pesanan
  - `payment_method` (Enum: bri, dana) - Metode pembayaran
  - `shipping_method` (String) - Metode pengiriman
  - `subtotal` (Decimal) - Subtotal pesanan
  - `shipping_cost` (Decimal) - Biaya pengiriman
  - `total_amount` (Decimal) - Total pembayaran
  - `status` (Enum) - Status pesanan:
    - `pending` - Pesanan dikirim ke WhatsApp
    - `payment_pending` - Menunggu pembayaran
    - `payment_verified` - Pembayaran terverifikasi
    - `processing` - Sedang disiapkan
    - `packaged` - Sudah dikemas
    - `shipped` - Sedang dikirim
    - `delivered` - Sudah sampai
    - `completed` - Transaksi selesai
    - `cancelled` - Dibatalkan
  - `payment_proof` (Text, Nullable) - Path bukti pembayaran
  - `payment_verified_at` (Timestamp, Nullable)
  - `shipped_at` (Timestamp, Nullable)
  - `delivered_at` (Timestamp, Nullable)
  - `delivery_proof` (Text, Nullable) - Path foto bukti sampai
  - `tracking_number` (String, Nullable) - Nomor resi
  - `stock_reduced` (Boolean) - Flag pengurangan stok
  - `admin_notes` (Text, Nullable) - Catatan admin
  - `timestamps`
- **Indexes**: `[status, created_at]`, `order_number`

#### 6. Order Items Table
- **File**: `2025_08_11_041154_create_order_items_table.php`
- **Purpose**: Detail item dalam pesanan
- **Fields**:
  - `id` (Primary Key)
  - `order_id` (Foreign Key) - Referensi ke orders
  - `product_id` (Foreign Key) - Referensi ke products
  - `product_name` (String) - Nama produk saat order
  - `product_size` (String) - Ukuran produk saat order
  - `quantity` (Integer) - Jumlah item
  - `price` (Decimal) - Harga per unit saat order
  - `total` (Decimal) - Total harga item
  - `timestamps`
- **Index**: `[order_id, product_id]`

#### 7. Testimonials Table
- **File**: `2025_08_11_082138_create_testimonials_table.php`
- **Purpose**: Testimoni pelanggan
- **Fields**:
  - `id` (Primary Key)
  - `user_id` (Foreign Key) - User pemberi testimoni
  - `order_id` (Foreign Key) - Pesanan terkait
  - `customer_name` (String) - Nama pelanggan
  - `institution_name` (String) - Nama institusi
  - `testimonial_text` (Text) - Isi testimoni
  - `rating` (Integer) - Rating (default: 5)
  - `is_approved` (Boolean) - Status persetujuan
  - `timestamps`

### System Tables

#### Authentication & Session Tables
- **password_reset_tokens**: Token reset password
- **sessions**: Session management
- **cache**: Cache storage
- **jobs**: Queue jobs

## Database Relationships

### One-to-Many Relationships
- `User` → `Orders` (user_id)
- `User` → `Carts` (user_id)
- `User` → `Testimonials` (user_id)
- `Inventory` → `Products` (inventory_id)
- `Product` → `Cart Items` (product_id)
- `Product` → `Order Items` (product_id)
- `Order` → `Order Items` (order_id)
- `Order` → `Testimonials` (order_id)

### Key Foreign Key Constraints
- Products.inventory_id → Inventories.id (SET NULL)
- Carts.product_id → Products.id (CASCADE)
- Carts.user_id → Users.id (CASCADE)
- Orders.user_id → Users.id (SET NULL)
- Order_items.order_id → Orders.id (CASCADE)
- Order_items.product_id → Products.id (CASCADE)
- Testimonials.user_id → Users.id (CASCADE)
- Testimonials.order_id → Orders.id (CASCADE)

## Seeders

### 1. DatabaseSeeder
- **File**: `DatabaseSeeder.php`
- **Purpose**: Main seeder orchestrator
- **Calls**:
  - `InventoryTableSeeder`
  - `ProductsTableSeeder`
- **Creates**: Test user dengan factory

### 2. AdminUserSeeder
- **File**: `AdminUserSeeder.php`
- **Purpose**: Membuat akun admin dan user default
- **Creates**:
  - Admin: `admin@ravazka.com` / `admin123`
  - User: `user@ravazka.com` / `user123`

### 3. InventoryTableSeeder
- **File**: `InventoryTableSeeder.php`
- **Purpose**: Mengisi data inventaris awal
- **Data Categories**:
  - Kemeja Sekolah (SD Pendek/Panjang)
  - Kemeja Batik
  - Kemeja Batik Koko
  - Kemeja Padang
  - Rok Sekolah
  - Celana Sekolah
- **Features**:
  - Stock history tracking
  - Multiple size variants
  - Supplier information
  - Location mapping

### 4. ProductsTableSeeder
- **File**: `ProductsTableSeeder.php`
- **Purpose**: Generate produk berdasarkan inventaris
- **Logic**:
  - Mapping inventory_id ke size variants
  - Auto-generate slug dari nama + ukuran
  - Set stock default 20 per variant
  - Link ke inventory parent

### 5. TestimonialSeeder
- **File**: `TestimonialSeeder.php`
- **Purpose**: Membuat testimoni sample
- **Features**:
  - Sample customer testimonials
  - Rating system
  - Approval status
  - Institution names

### 6. Utility Seeders
- **RavazkaProductSeeder**: Seeder khusus produk RAVAZKA
- **UpdateInventoryStockSeeder**: Update stok inventaris

## Migration Timeline

### Core Structure (2025-06-07)
1. `create_products_table` - Tabel produk dasar
2. `create_inventories_table` - Sistem inventaris

### User & Authentication (2025-08-04)
3. `add_role_to_users_table` - Role-based access
4. `create_carts_table` - Keranjang belanja
5. `add_weight_to_products_table` - Berat produk

### Order System (2025-08-11)
6. `create_orders_table` - Sistem pesanan
7. `create_order_items_table` - Detail pesanan
8. `add_user_id_to_orders_table` - Link user ke order
9. `add_tracking_number_to_orders_table` - Nomor resi
10. `create_testimonials_table` - Sistem testimoni
11. `add_stock_reduced_to_orders_table` - Flag stok

### Enhancements (2025-01-15/16)
12. `add_user_id_to_carts_table` - User cart support
13. `add_shipping_method_to_orders_table` - Metode kirim

## Database Indexes

### Performance Indexes
- `carts`: `[session_id, product_id]`, `[user_id, product_id]`
- `orders`: `[status, created_at]`, `order_number`
- `order_items`: `[order_id, product_id]`
- `sessions`: `user_id`, `last_activity`

## JSON Fields

### Inventories Table
- `sizes_available`: Array ukuran tersedia
  ```json
  ["8", "9", "10", "11", "12", "13", "14", "15", "16"]
  ```
- `stock_history`: Riwayat perubahan stok
  ```json
  [
    {
      "date": "2025-01-15",
      "type": "in",
      "quantity": 180,
      "notes": "Stok awal"
    }
  ]
  ```

## Security Considerations

### Data Protection
- Password hashing menggunakan bcrypt
- Email verification support
- Remember token untuk persistent login
- CSRF protection via Laravel

### Access Control
- Role-based access (admin/user)
- Foreign key constraints untuk data integrity
- Soft deletes untuk data penting

### Data Validation
- Unique constraints pada email dan order_number
- Enum constraints untuk status dan payment_method
- Not null constraints pada field wajib

## Performance Optimization

### Database Design
- Proper indexing pada field yang sering di-query
- Foreign key relationships untuk data consistency
- JSON fields untuk flexible data storage

### Query Optimization
- Eager loading untuk relationships
- Index pada composite keys
- Pagination untuk large datasets

## Backup & Maintenance

### Regular Tasks
- Database backup harian
- Index optimization
- Log cleanup
- Session cleanup

### Monitoring
- Query performance monitoring
- Storage usage tracking
- Connection pool monitoring

## Migration Commands

```bash
# Run migrations
php artisan migrate

# Run seeders
php artisan db:seed

# Refresh database with seeders
php artisan migrate:refresh --seed

# Run specific seeder
php artisan db:seed --class=AdminUserSeeder

# Check migration status
php artisan migrate:status
```

## Testing

### Database Testing
- Factory untuk User model
- Seeder testing dengan sample data
- Migration rollback testing
- Foreign key constraint testing

### Test Database
- Separate test database configuration
- In-memory SQLite untuk unit tests
- Database transactions untuk test isolation

## Troubleshooting

### Common Issues
1. **Foreign Key Constraint Errors**
   - Pastikan parent record exists sebelum insert
   - Check migration order

2. **Migration Rollback Issues**
   - Pastikan down() method properly implemented
   - Check foreign key dependencies

3. **Seeder Errors**
   - Run migrations sebelum seeders
   - Check data dependencies antar seeders

4. **Performance Issues**
   - Add indexes pada field yang sering di-query
   - Optimize N+1 queries dengan eager loading
   - Use pagination untuk large datasets

## Future Enhancements

### Planned Features
- Product variants dengan attributes
- Advanced inventory tracking
- Multi-warehouse support
- Audit trail untuk semua perubahan data
- Advanced reporting tables

### Scalability Considerations
- Database sharding untuk large datasets
- Read replicas untuk query optimization
- Caching layer untuk frequently accessed data
- Archive strategy untuk old orders

Dokumentasi ini akan terus diperbarui seiring dengan perkembangan proyek RAVAZKA.