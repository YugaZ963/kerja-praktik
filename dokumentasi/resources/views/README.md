# Dokumentasi Views/Blade Templates - RAVAZKA

## Overview
Direktori Views berisi semua template Blade yang digunakan untuk menampilkan antarmuka pengguna dalam sistem RAVAZKA. Views mengikuti pola MVC dan menggunakan Blade templating engine Laravel untuk rendering HTML yang dinamis.

## Struktur Folder
```
resources/views/
├── layouts/                    # Layout utama
│   ├── app.blade.php          # Layout admin/dashboard
│   └── customer.blade.php     # Layout customer/public
├── components/                 # Komponen reusable
│   ├── navbar.blade.php       # Navigation bar
│   ├── inventory-filter.blade.php
│   ├── inventory-stats.blade.php
│   ├── inventory-table.blade.php
│   └── inventory-size-breakdown.blade.php
├── auth/                       # Halaman autentikasi
│   ├── login.blade.php        # Form login
│   └── register.blade.php     # Form registrasi
├── admin/                      # Halaman admin
│   ├── orders/                # Manajemen pesanan
│   │   ├── index.blade.php    # Daftar pesanan
│   │   └── show.blade.php     # Detail pesanan
│   └── sales/                 # Laporan penjualan
│       ├── index.blade.php    # Dashboard penjualan
│       └── pdf.blade.php      # Export PDF
├── customer/                   # Halaman customer
│   ├── orders/                # Pesanan customer
│   │   ├── index.blade.php    # Daftar pesanan
│   │   ├── show.blade.php     # Detail pesanan
│   │   └── track.blade.php    # Lacak pesanan
│   └── products.blade.php     # Katalog produk
├── cart/                       # Keranjang belanja
│   ├── index.blade.php        # Halaman keranjang
│   └── checkout.blade.php     # Halaman checkout
├── inventory/                  # Manajemen inventaris
│   ├── index.blade.php        # Daftar inventaris
│   ├── create.blade.php       # Tambah item
│   ├── edit.blade.php         # Edit item
│   ├── detail.blade.php       # Detail item
│   ├── report.blade.php       # Laporan inventaris
│   ├── pdf/
│   │   └── report.blade.php   # Export PDF
│   └── reports/
│       └── stock.blade.php    # Laporan stok
├── welcome.blade.php           # Halaman utama
├── dashboard.blade.php         # Dashboard admin
├── about.blade.php            # Tentang kami
├── contact.blade.php          # Kontak
├── product.blade.php          # Detail produk
└── products.blade.php         # Daftar produk
```

## Layouts

### 1. app.blade.php
**Lokasi:** `layouts/app.blade.php`
**Fungsi:** Layout utama untuk halaman admin dan dashboard

#### Fitur Utama
- **Meta Tags**: CSRF token, viewport, charset
- **CSS Framework**: Bootstrap 5.3.0, Bootstrap Icons
- **Custom Styles**: app.css, custom.css
- **Navigation**: Admin navbar dengan dropdown user menu
- **Flash Messages**: Success/error notifications
- **JavaScript**: Bootstrap bundle

#### Struktur
```blade
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <!-- Meta tags, title, CSS -->
</head>
<body>
    <div id="app">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <!-- Brand, toggle, menu items -->
        </nav>
        
        <!-- Main Content -->
        <main class="py-4">
            <div class="container">
                <!-- Flash messages -->
                @yield('content')
            </div>
        </main>
    </div>
    <!-- Scripts -->
</body>
</html>
```

#### Fitur Keamanan
- CSRF token meta tag
- Authentication guards
- Role-based navigation

---

### 2. customer.blade.php
**Lokasi:** `layouts/customer.blade.php`
**Fungsi:** Layout untuk halaman customer/public

#### Fitur Utama
- **Responsive Design**: Bootstrap 5.3.0
- **Cart Integration**: Real-time cart count update
- **AJAX Support**: Form submissions dengan AJAX
- **Authentication Handling**: Auto-redirect untuk guest users
- **JavaScript Features**: Cart count update, form handling

#### JavaScript Features
```javascript
// Update cart count
function updateCartCount()

// Handle authentication errors
function handleAuthError(response)

// AJAX form submissions
document.addEventListener('DOMContentLoaded', function() {
    // Cart count update
    // Add to cart forms handling
});
```

---

## Components

### 1. navbar.blade.php
**Lokasi:** `components/navbar.blade.php`
**Fungsi:** Navigation bar dengan fitur lengkap

#### Fitur Utama
- **Responsive Navigation**: Mobile-friendly dengan toggle
- **Search Functionality**: Dropdown search dengan AJAX
- **Cart Integration**: Real-time cart count badge
- **Role-based Menu**: Different menu untuk admin/user
- **Authentication State**: Login/register atau user dropdown

#### Menu Structure
```
Navigation:
├── Beranda (/)
├── Produk (/products)
├── [Admin] Inventaris (/inventory)
├── [User] Pesanan (/orders)
├── Tentang Kami (/about)
├── Kontak (/contact)
├── Search Dropdown
├── Cart Badge
└── User Menu
    ├── [Admin] Dashboard
    ├── [Admin] Inventaris
    ├── [Admin] Laporan Penjualan
    ├── [User] Pesanan Saya
    ├── [User] Lacak Pesanan
    └── Logout
```

#### JavaScript Features
- Search dropdown interaction
- Form submission handling
- Quick search functionality
- Prevent dropdown close on form interaction

---

## Authentication Views

### 1. login.blade.php
**Lokasi:** `auth/login.blade.php`
**Fungsi:** Form login dengan validasi

#### Fitur
- **Responsive Design**: Card-based layout
- **Form Validation**: Client & server-side validation
- **Remember Me**: Persistent login option
- **Error Handling**: Display validation errors
- **User Experience**: Icons, placeholders, focus states

#### Form Fields
- Email (required, email validation)
- Password (required, min 6 characters)
- Remember Me checkbox
- Login button
- Register link

#### Styling
- Bootstrap 5 components
- Custom card styling
- Primary color scheme
- Responsive layout

---

### 2. register.blade.php
**Lokasi:** `auth/register.blade.php`
**Fungsi:** Form registrasi pengguna baru

#### Form Fields
- Name (required, max 255)
- Email (required, unique)
- Password (required, min 6, confirmed)
- Password Confirmation
- Register button
- Login link

---

## Main Pages

### 1. welcome.blade.php
**Lokasi:** `welcome.blade.php`
**Fungsi:** Landing page/halaman utama

#### Sections
- **Hero Section**: Judul utama, deskripsi, CTA button
- **Categories**: Kategori populer (SMA, SMP, SD)
- **Recent Orders**: Pesanan terbaru sebagai social proof

#### Features
- **Responsive Grid**: Bootstrap grid system
- **Category Cards**: Interactive category selection
- **Order Status Display**: Color-coded status labels
- **Image Integration**: Hero image dan logo

#### Category Cards
```blade
@foreach(['SMA', 'SMP', 'SD'] as $category)
<div class="card">
    <div class="card-body text-center">
        <i class="bi bi-icon fs-1 text-primary"></i>
        <h5>Seragam {{ $category }}</h5>
        <p>Description</p>
        <a href="{{ route('customer.products', ['search' => $category]) }}">Lihat Produk</a>
    </div>
</div>
@endforeach
```

---

### 2. dashboard.blade.php
**Lokasi:** `dashboard.blade.php`
**Fungsi:** Dashboard admin dengan quick access

#### Features
- **Role-based Content**: Different content untuk admin/user
- **Quick Access Cards**: Shortcut ke fitur utama
- **Statistics Display**: Overview metrics
- **Navigation Cards**: Visual navigation dengan icons

#### Admin Quick Access
- Kelola Inventaris
- Kelola Pesanan
- Laporan Stok
- Laporan Penjualan

#### User Quick Access
- Lihat Produk
- Pesanan Saya
- Keranjang Belanja

---

## Cart Views

### 1. cart/index.blade.php
**Lokasi:** `cart/index.blade.php`
**Fungsi:** Halaman keranjang belanja

#### Features
- **Empty State**: Pesan dan CTA ketika keranjang kosong
- **Item Management**: Update quantity, remove items
- **Stock Validation**: Real-time stock checking
- **Price Calculation**: Subtotal dan total calculation
- **Bulk Actions**: Clear all cart items

#### Cart Item Structure
```blade
@foreach($cartItems as $item)
<div class="border-bottom p-3">
    <div class="row align-items-center">
        <div class="col-md-2"><!-- Product Image --></div>
        <div class="col-md-4"><!-- Product Info --></div>
        <div class="col-md-3"><!-- Quantity Controls --></div>
        <div class="col-md-2"><!-- Price & Actions --></div>
    </div>
</div>
@endforeach
```

#### JavaScript Features
- Quantity increase/decrease functions
- Form validation
- Confirmation dialogs
- Real-time price updates

---

### 2. cart/checkout.blade.php
**Lokasi:** `cart/checkout.blade.php`
**Fungsi:** Halaman checkout dan pembayaran

#### Form Sections
- **Customer Information**: Nama, telepon, alamat
- **Payment Method**: BRI, DANA
- **Shipping Method**: Reguler, Express (+15k)
- **Order Summary**: Item details, subtotal, shipping, total
- **Notes**: Catatan khusus

#### Features
- **Form Validation**: Client & server-side
- **Price Calculation**: Dynamic total calculation
- **Payment Integration**: WhatsApp redirect
- **Order Processing**: Database transaction

---

## Admin Views

### 1. admin/orders/index.blade.php
**Lokasi:** `admin/orders/index.blade.php`
**Fungsi:** Daftar pesanan untuk admin

#### Features
- **Status Filter**: Filter berdasarkan status pesanan
- **Search Function**: Cari berdasarkan order number, nama, phone
- **Status Counts**: Badge dengan jumlah per status
- **Pagination**: Efficient data loading
- **Bulk Actions**: Mass status updates

#### Order Status Flow
1. Pending → Payment Pending → Payment Verified
2. Processing → Packaged → Shipped
3. Delivered → Completed
4. Cancelled (any time)

---

### 2. admin/orders/show.blade.php
**Lokasi:** `admin/orders/show.blade.php`
**Fungsi:** Detail pesanan dengan management tools

#### Sections
- **Order Information**: Order number, date, status
- **Customer Details**: Contact information
- **Order Items**: Product details, quantities, prices
- **Payment Information**: Method, proof, verification
- **Shipping Details**: Method, tracking, delivery proof
- **Admin Actions**: Status update, file uploads, notes

#### Admin Actions
- Update order status
- Upload payment proof
- Upload delivery proof
- Add admin notes
- Set tracking number

---

## Customer Views

### 1. customer/products.blade.php
**Lokasi:** `customer/products.blade.php`
**Fungsi:** Katalog produk dengan filter

#### Features
- **Product Grid**: Responsive product cards
- **Filter System**: Category, size, price range
- **Sorting Options**: Price, name, latest
- **Pagination**: Efficient loading
- **Search Integration**: Text search
- **Add to Cart**: AJAX cart addition

#### Product Card Structure
```blade
@foreach($products as $product)
<div class="col-md-4 mb-4">
    <div class="card product-card">
        <img src="{{ $product->image }}" class="card-img-top">
        <div class="card-body">
            <h5 class="card-title">{{ $product->name }}</h5>
            <p class="text-muted">{{ $product->category }} - {{ $product->size }}</p>
            <div class="d-flex justify-content-between align-items-center">
                <span class="h5 text-primary">Rp {{ number_format($product->price) }}</span>
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
```

---

### 2. customer/orders/index.blade.php
**Lokasi:** `customer/orders/index.blade.php`
**Fungsi:** Daftar pesanan customer

#### Features
- **Order History**: Chronological order list
- **Status Tracking**: Visual status indicators
- **Order Details**: Quick view of order info
- **Actions**: View detail, track order, add testimonial
- **Filter Options**: Status-based filtering

---

## Inventory Views

### 1. inventory/index.blade.php
**Lokasi:** `inventory/index.blade.php`
**Fungsi:** Daftar inventaris dengan management tools

#### Features
- **Advanced Filtering**: Multiple filter criteria
- **Sorting Options**: Various sorting methods
- **Bulk Actions**: Mass operations
- **Stock Alerts**: Low stock indicators
- **CRUD Operations**: Create, read, update, delete

#### Filter Options
- Search (name, category, description, code)
- Category filter
- Stock status (low, out, ready, critical)
- Price range
- Date range

---

### 2. inventory/create.blade.php & edit.blade.php
**Lokasi:** `inventory/create.blade.php`, `inventory/edit.blade.php`
**Fungsi:** Form untuk tambah/edit item inventaris

#### Form Fields
- Basic Information (name, code, category)
- Pricing (purchase price, selling price)
- Stock Management (current stock, min stock)
- Product Details (description, supplier)
- Images (product photos)

---

## Design Patterns & Best Practices

### 1. Blade Templating
- **Template Inheritance**: `@extends`, `@section`, `@yield`
- **Component System**: `<x-component>` syntax
- **Conditional Rendering**: `@if`, `@auth`, `@guest`
- **Loops**: `@foreach`, `@forelse`
- **CSRF Protection**: `@csrf` directive

### 2. Responsive Design
- **Bootstrap Grid**: Responsive column system
- **Mobile-first**: Progressive enhancement
- **Breakpoint Usage**: sm, md, lg, xl classes
- **Flexible Components**: Adaptive layouts

### 3. User Experience
- **Loading States**: Skeleton screens, spinners
- **Error Handling**: User-friendly error messages
- **Success Feedback**: Flash messages, notifications
- **Progressive Enhancement**: JavaScript enhancements

### 4. Security Implementation
- **CSRF Protection**: All forms protected
- **XSS Prevention**: Blade escaping
- **Input Validation**: Client & server-side
- **Authentication Guards**: Route protection

## JavaScript Integration

### 1. AJAX Implementation
```javascript
// Cart operations
fetch('/cart/add/' + productId, {
    method: 'POST',
    body: formData,
    headers: {
        'X-CSRF-TOKEN': csrfToken
    }
})
```

### 2. Real-time Updates
- Cart count updates
- Stock validation
- Price calculations
- Form validations

### 3. User Interactions
- Dropdown menus
- Modal dialogs
- Form submissions
- Search functionality

## Performance Optimization

### 1. Asset Management
- **CSS/JS Bundling**: Minimized file requests
- **CDN Usage**: Bootstrap, icons from CDN
- **Image Optimization**: Proper sizing, lazy loading
- **Caching**: Browser caching headers

### 2. Database Optimization
- **Eager Loading**: Prevent N+1 queries
- **Pagination**: Efficient data loading
- **Query Optimization**: Indexed searches

### 3. Frontend Performance
- **Lazy Loading**: Images, components
- **Code Splitting**: JavaScript modules
- **Minification**: CSS/JS compression

## Accessibility Features

### 1. Semantic HTML
- Proper heading hierarchy
- Form labels and descriptions
- ARIA attributes
- Keyboard navigation

### 2. Visual Design
- Color contrast compliance
- Focus indicators
- Responsive text sizing
- Icon alternatives

## Error Handling

### 1. User-Friendly Messages
```blade
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

### 2. Validation Display
- Inline field validation
- Summary error messages
- Real-time validation feedback

## Maintenance & Updates

### 1. Code Organization
- Consistent file naming
- Logical folder structure
- Reusable components
- Clear documentation

### 2. Version Control
- Semantic versioning
- Change documentation
- Backward compatibility
- Migration guides

Dokumentasi ini memberikan gambaran lengkap tentang semua Views/Blade Templates dalam sistem RAVAZKA, termasuk struktur, fitur, best practices, dan implementasi teknis yang digunakan untuk menciptakan antarmuka pengguna yang responsif dan user-friendly.