# Dokumentasi Models

## 📋 Overview
Folder `app/Models/` berisi semua model Eloquent yang merepresentasikan entitas data dalam sistem RAVAZKA. Setiap model mengelola data dan business logic untuk tabel database tertentu.

## 📁 Struktur Models

```
app/Models/
├── User.php          # Model pengguna (admin/customer)
├── Product.php       # Model produk seragam
├── Inventory.php     # Model inventaris/stok
├── Order.php         # Model pesanan
├── OrderItem.php     # Model item dalam pesanan
├── Cart.php          # Model keranjang belanja
└── Testimonial.php   # Model testimoni pelanggan
```

## 🔗 Entity Relationship Diagram

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│    User     │────►│    Order    │────►│ OrderItem   │
│             │     │             │     │             │
│ - id        │     │ - id        │     │ - id        │
│ - name      │     │ - user_id   │     │ - order_id  │
│ - email     │     │ - total     │     │ - product_id│
│ - role      │     │ - status    │     │ - quantity  │
└─────────────┘     └─────────────┘     └─────────────┘
       │                   │                     │
       │                   │                     │
       ▼                   │                     ▼
┌─────────────┐           │            ┌─────────────┐
│    Cart     │           │            │   Product   │
│             │           │            │             │
│ - id        │           │            │ - id        │
│ - user_id   │           │            │ - name      │
│ - product_id│───────────┘            │ - price     │
│ - quantity  │                        │ - stock     │
└─────────────┘                        └─────────────┘
       │                                       │
       │                                       │
       │               ┌─────────────┐        │
       │               │ Inventory   │◄───────┘
       │               │             │
       │               │ - id        │
       │               │ - code      │
       │               │ - stock     │
       │               │ - category  │
       │               └─────────────┘
       │                       │
       │                       │
       ▼                       ▼
┌─────────────┐     ┌─────────────┐
│Testimonial  │     │   (Future   │
│             │     │  Extensions)│
│ - id        │     │             │
│ - user_id   │     │ - Reports   │
│ - order_id  │     │ - Analytics │
│ - rating    │     │ - Logs      │
└─────────────┘     └─────────────┘
```

---

## 📚 Model Documentation

### 1. **User.php** - Model Pengguna

**Fungsi**: Mengelola data pengguna sistem (admin dan customer)

**Tabel Database**: `users`

**Fillable Attributes**:
```php
protected $fillable = [
    'name',        // Nama lengkap pengguna
    'email',       // Email (unique)
    'password',    // Password (hashed)
    'role',        // Role: 'admin' atau 'user'
];
```

**Hidden Attributes**:
```php
protected $hidden = [
    'password',        // Password tidak ditampilkan dalam JSON
    'remember_token',  // Token remember me
];
```

**Casts**:
```php
protected function casts(): array {
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',  // Auto-hash password
    ];
}
```

**Methods**:
- `isAdmin(): bool` - Mengecek apakah user adalah admin

**Relationships**:
- `hasMany(Order::class)` - User memiliki banyak pesanan
- `hasMany(Cart::class)` - User memiliki banyak item cart
- `hasMany(Testimonial::class)` - User dapat memberikan testimoni

**Teknologi**: Laravel Authentication, Eloquent ORM, Password Hashing

**Security Features**:
- ✅ Password auto-hashing
- ✅ Hidden sensitive attributes
- ✅ Role-based access control
- ✅ Email verification ready

---

### 2. **Product.php** - Model Produk

**Fungsi**: Mengelola data produk seragam sekolah

**Tabel Database**: `products`

**Fillable Attributes**:
```php
protected $fillable = [
    'name',         // Nama produk
    'slug',         // URL-friendly name
    'price',        // Harga jual
    'weight',       // Berat produk (untuk ongkir)
    'description',  // Deskripsi produk
    'stock',        // Stok tersedia
    'size',         // Ukuran (S, M, L, XL, dll)
    'category',     // Kategori produk
    'inventory_id'  // Foreign key ke inventory
];
```

**Relationships**:
- `belongsTo(Inventory::class)` - Produk terkait dengan inventory
- `hasMany(OrderItem::class)` - Produk bisa ada di banyak order
- `hasMany(Cart::class)` - Produk bisa ada di banyak cart

**Event Listeners**:
```php
protected static function booted() {
    // Auto-update inventory stock saat produk berubah
    static::created(function ($product) {
        $product->updateInventoryStock();
    });
    
    static::updated(function ($product) {
        $product->updateInventoryStock();
    });
    
    static::deleted(function ($product) {
        $product->updateInventoryStock();
    });
}
```

**Methods**:
- `updateInventoryStock()` - Update stok inventory parent

**Teknologi**: Eloquent ORM, Model Events, Foreign Key Relations

**Business Logic**:
- ✅ Auto-sync dengan inventory stock
- ✅ Support multiple sizes
- ✅ Weight-based shipping calculation
- ✅ SEO-friendly slugs

---

### 3. **Inventory.php** - Model Inventaris

**Fungsi**: Mengelola inventaris dan stok produk secara keseluruhan

**Tabel Database**: `inventories`

**Fillable Attributes**:
```php
protected $fillable = [
    'code',            // Kode inventaris (unique)
    'name',            // Nama item inventaris
    'category',        // Kategori (SD, SMP, SMA)
    'stock',           // Total stok
    'min_stock',       // Minimum stok (alert)
    'purchase_price',  // Harga beli
    'selling_price',   // Harga jual
    'supplier',        // Nama supplier
    'last_restock',    // Tanggal restock terakhir
    'sizes_available', // Array ukuran tersedia
    'location',        // Lokasi penyimpanan
    'description',     // Deskripsi item
    'stock_history'    // History perubahan stok
];
```

**Casts**:
```php
protected $casts = [
    'stock' => 'integer',
    'min_stock' => 'integer',
    'purchase_price' => 'decimal:2',
    'selling_price' => 'decimal:2',
    'last_restock' => 'date',
    'sizes_available' => 'array',  // JSON array
    'stock_history' => 'array'     // JSON array
];
```

**Relationships**:
- `hasMany(Product::class)` - Inventory memiliki banyak produk

**Accessors**:
- `getSellingPriceFormattedAttribute()` - Format harga jual (Rp xxx)
- `getPurchasePriceFormattedAttribute()` - Format harga beli (Rp xxx)

**Methods**:
- `updateStock()` - Update total stok dari semua produk terkait

**Teknologi**: Eloquent ORM, JSON Casting, Accessors, Date Casting

**Business Logic**:
- ✅ Auto-calculate total stock dari products
- ✅ Low stock alerting (min_stock)
- ✅ Stock history tracking
- ✅ Multi-size support
- ✅ Supplier management

---

### 4. **Order.php** - Model Pesanan

**Fungsi**: Mengelola pesanan pelanggan dan status pengiriman

**Tabel Database**: `orders`

**Fillable Attributes**:
```php
protected $fillable = [
    'order_number',        // Nomor pesanan (unique)
    'user_id',            // ID pengguna (nullable untuk guest)
    'customer_name',      // Nama pelanggan
    'customer_phone',     // Nomor telepon
    'customer_address',   // Alamat pengiriman
    'notes',              // Catatan pesanan
    'payment_method',     // Metode pembayaran (bri/dana)
    'shipping_method',    // Metode pengiriman (reguler/express)
    'subtotal',           // Subtotal sebelum ongkir
    'shipping_cost',      // Biaya pengiriman
    'total_amount',       // Total pembayaran
    'status',             // Status pesanan
    'payment_proof',      // File bukti pembayaran
    'payment_verified_at',// Waktu verifikasi pembayaran
    'shipped_at',         // Waktu pengiriman
    'delivered_at',       // Waktu sampai
    'delivery_proof',     // Bukti pengiriman
    'admin_notes',        // Catatan admin
    'tracking_number',    // Nomor resi
    'stock_reduced',      // Flag stok sudah dikurangi
    'stock_reduced_at'    // Waktu pengurangan stok
];
```

**Status Constants**:
```php
const STATUS_PENDING = 'pending';
const STATUS_PAYMENT_PENDING = 'payment_pending';
const STATUS_PAYMENT_VERIFIED = 'payment_verified';
const STATUS_PROCESSING = 'processing';
const STATUS_PACKAGED = 'packaged';
const STATUS_SHIPPED = 'shipped';
const STATUS_DELIVERED = 'delivered';
const STATUS_COMPLETED = 'completed';
const STATUS_CANCELLED = 'cancelled';
```

**Casts**:
```php
protected $casts = [
    'payment_verified_at' => 'datetime',
    'shipped_at' => 'datetime',
    'delivered_at' => 'datetime',
    'stock_reduced_at' => 'datetime',
    'subtotal' => 'decimal:2',
    'shipping_cost' => 'decimal:2',
    'total_amount' => 'decimal:2',
    'stock_reduced' => 'boolean'
];
```

**Relationships**:
- `belongsTo(User::class)` - Pesanan milik user
- `hasMany(OrderItem::class)` - Pesanan memiliki banyak item
- `hasOne(Testimonial::class)` - Pesanan bisa memiliki testimoni

**Methods**:
- `getStatusLabels()` - Array label status dalam bahasa Indonesia
- `getStatusLabelAttribute()` - Accessor untuk label status
- `getPaymentMethodLabelAttribute()` - Label metode pembayaran
- `getShippingMethodLabelAttribute()` - Label metode pengiriman

**Teknologi**: Eloquent ORM, Constants, Accessors, DateTime Casting

**Business Logic**:
- ✅ Complete order lifecycle management
- ✅ Payment verification workflow
- ✅ Shipping tracking
- ✅ Stock management integration
- ✅ Multi-payment method support

---

### 5. **OrderItem.php** - Model Item Pesanan

**Fungsi**: Mengelola detail item dalam setiap pesanan

**Tabel Database**: `order_items`

**Fillable Attributes**:
```php
protected $fillable = [
    'order_id',      // Foreign key ke orders
    'product_id',    // Foreign key ke products
    'product_name',  // Nama produk (snapshot)
    'product_size',  // Ukuran produk
    'quantity',      // Jumlah item
    'price',         // Harga per item (snapshot)
    'total'          // Total harga (quantity × price)
];
```

**Casts**:
```php
protected $casts = [
    'price' => 'decimal:2',
    'total' => 'decimal:2'
];
```

**Relationships**:
- `belongsTo(Order::class)` - Item milik pesanan
- `belongsTo(Product::class)` - Item referensi ke produk

**Teknologi**: Eloquent ORM, Decimal Casting, Foreign Keys

**Business Logic**:
- ✅ Snapshot product data (nama, harga)
- ✅ Size-specific ordering
- ✅ Quantity management
- ✅ Total calculation

---

### 6. **Cart.php** - Model Keranjang Belanja

**Fungsi**: Mengelola keranjang belanja untuk user dan guest

**Tabel Database**: `carts`

**Fillable Attributes**:
```php
protected $fillable = [
    'session_id',  // Session ID untuk guest
    'user_id',     // User ID untuk logged user (nullable)
    'product_id',  // Foreign key ke products
    'quantity',    // Jumlah item
    'price'        // Harga per item (snapshot)
];
```

**Relationships**:
- `belongsTo(Product::class)` - Cart item referensi ke produk
- `belongsTo(User::class)` - Cart milik user (nullable)

**Accessors**:
- `getTotalAttribute()` - Hitung total (quantity × price)

**Static Methods**:
```php
// Ambil cart items berdasarkan user/session
public static function getCartItems($userId = null, $sessionId = null)

// Merge session cart ke user cart saat login
public static function mergeSessionToUser($userId, $sessionId)
```

**Teknologi**: Eloquent ORM, Session Management, Static Methods

**Business Logic**:
- ✅ Support guest dan authenticated users
- ✅ Session-based cart untuk guest
- ✅ Auto-merge cart saat login
- ✅ Price snapshot untuk konsistensi
- ✅ Quantity management

---

### 7. **Testimonial.php** - Model Testimoni

**Fungsi**: Mengelola testimoni pelanggan untuk pesanan yang selesai

**Tabel Database**: `testimonials`

**Fillable Attributes**:
```php
protected $fillable = [
    'user_id',          // Foreign key ke users
    'order_id',         // Foreign key ke orders
    'customer_name',    // Nama pelanggan
    'institution_name', // Nama institusi/sekolah
    'testimonial_text', // Isi testimoni
    'rating',           // Rating 1-5
    'is_approved'       // Status persetujuan admin
];
```

**Casts**:
```php
protected $casts = [
    'is_approved' => 'boolean',
    'rating' => 'integer'
];
```

**Relationships**:
- `belongsTo(User::class)` - Testimoni dari user
- `belongsTo(Order::class)` - Testimoni untuk pesanan tertentu

**Teknologi**: Eloquent ORM, Boolean Casting, Foreign Keys

**Business Logic**:
- ✅ Rating system (1-5 stars)
- ✅ Admin approval workflow
- ✅ Institution-based testimonials
- ✅ Order-specific feedback

---

## 🔐 Security & Best Practices

### **Mass Assignment Protection**
- Semua model menggunakan `$fillable` untuk whitelist attributes
- Sensitive data seperti password di-hidden dari serialization
- Foreign keys divalidasi melalui relationships

### **Data Integrity**
- Menggunakan Eloquent relationships untuk referential integrity
- Model events untuk auto-sync data (Product ↔ Inventory)
- Casting untuk type safety (decimal, boolean, datetime, array)

### **Performance Optimization**
- Eager loading relationships untuk menghindari N+1 queries
- Indexing pada foreign keys dan frequently queried columns
- Efficient query methods (static methods di Cart)

### **Business Logic Encapsulation**
- Model methods untuk complex business operations
- Accessors untuk formatted output
- Constants untuk status management
- Event listeners untuk automatic updates

---

## 🚀 Usage Examples

### **User Management**
```php
// Create admin user
$admin = User::create([
    'name' => 'Admin RAVAZKA',
    'email' => 'admin@ravazka.com',
    'password' => 'password', // Auto-hashed
    'role' => 'admin'
]);

// Check if user is admin
if ($user->isAdmin()) {
    // Admin-only operations
}
```

### **Product & Inventory Management**
```php
// Create inventory
$inventory = Inventory::create([
    'code' => 'KEMEJA-SD-001',
    'name' => 'Kemeja SD Putih',
    'category' => 'SD',
    'purchase_price' => 45000,
    'selling_price' => 65000,
    'sizes_available' => ['S', 'M', 'L', 'XL']
]);

// Create products for different sizes
foreach (['S', 'M', 'L', 'XL'] as $size) {
    Product::create([
        'name' => 'Kemeja SD Putih',
        'size' => $size,
        'stock' => 50,
        'price' => 65000,
        'inventory_id' => $inventory->id
    ]);
}

// Inventory stock will auto-update to 200 (50×4)
```

### **Order Processing**
```php
// Create order
$order = Order::create([
    'order_number' => 'ORD-' . time(),
    'user_id' => $user->id,
    'customer_name' => $user->name,
    'total_amount' => 130000,
    'status' => Order::STATUS_PENDING
]);

// Add order items
OrderItem::create([
    'order_id' => $order->id,
    'product_id' => $product->id,
    'quantity' => 2,
    'price' => $product->price,
    'total' => 2 * $product->price
]);

// Update order status
$order->update(['status' => Order::STATUS_PAYMENT_VERIFIED]);
```

### **Cart Management**
```php
// Add to cart (guest)
Cart::create([
    'session_id' => session()->getId(),
    'product_id' => $product->id,
    'quantity' => 1,
    'price' => $product->price
]);

// Get cart items
$cartItems = Cart::getCartItems(auth()->id(), session()->getId());

// Merge cart when user logs in
Cart::mergeSessionToUser(auth()->id(), session()->getId());
```

Model-model ini membentuk fondasi yang solid untuk sistem e-commerce RAVAZKA dengan relationship yang jelas, business logic yang terencapsulasi, dan security yang terjaga.