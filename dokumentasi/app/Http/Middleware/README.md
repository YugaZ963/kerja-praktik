# Dokumentasi Middleware - RAVAZKA

## Overview
Middleware dalam aplikasi RAVAZKA berfungsi sebagai filter HTTP yang memproses request sebelum mencapai controller atau setelah response dibuat. Sistem menggunakan middleware untuk autentikasi, otorisasi, dan kontrol akses berdasarkan role user.

## Struktur Middleware

```
app/Http/Middleware/
├── AdminMiddleware.php          # Middleware untuk akses admin
└── RequireLoginMiddleware.php   # Middleware untuk autentikasi user
```

## Registrasi Middleware

**File:** `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'require.login' => \App\Http\Middleware\RequireLoginMiddleware::class,
    ]);
})
```

### Alias Middleware
- **admin**: Middleware untuk akses admin only
- **require.login**: Middleware untuk user yang harus login
- **auth**: Built-in Laravel middleware untuk autentikasi

---

## 1. AdminMiddleware

**File:** `app/Http/Middleware/AdminMiddleware.php`
**Alias:** `admin`
**Fungsi:** Membatasi akses hanya untuk user dengan role admin

### Implementation

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return redirect('/')->with('error', 'Akses ditolak. Halaman ini hanya untuk admin.');
        }

        return $next($request);
    }
}
```

### Fitur Utama

#### 1. Authentication Check
- **Fungsi**: Memastikan user sudah login
- **Action**: Redirect ke halaman login jika belum authenticated
- **Message**: "Silakan login terlebih dahulu."

#### 2. Authorization Check
- **Fungsi**: Memastikan user memiliki role admin
- **Method**: Menggunakan `Auth::user()->isAdmin()`
- **Action**: Redirect ke homepage jika bukan admin
- **Message**: "Akses ditolak. Halaman ini hanya untuk admin."

#### 3. Error Handling
- **Flash Messages**: Menggunakan session flash untuk error messages
- **User Experience**: Pesan error yang user-friendly
- **Redirect Strategy**: Logical redirect berdasarkan kondisi

### Usage dalam Routes

```php
// Single route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('admin');

// Route group
Route::prefix('inventory')->middleware('admin')->group(function () {
    Route::get('/', [InventoryController::class, 'index']);
    Route::post('/store', [InventoryController::class, 'store']);
    // ... other routes
});

// Admin orders
Route::prefix('admin/orders')->middleware('admin')->group(function () {
    Route::get('/', [Admin\OrderController::class, 'index']);
    // ... other routes
});
```

### Protected Routes
- `/dashboard` - Admin dashboard
- `/inventory/*` - Semua route inventory management
- `/admin/orders/*` - Semua route order management admin

---

## 2. RequireLoginMiddleware

**File:** `app/Http/Middleware/RequireLoginMiddleware.php`
**Alias:** `require.login`
**Fungsi:** Memastikan user sudah login dengan handling khusus untuk AJAX dan POST requests

### Implementation

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequireLoginMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            // Untuk AJAX request atau POST request, kembalikan response JSON
            if ($request->expectsJson() || $request->isMethod('POST')) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'Silakan login terlebih dahulu untuk melanjutkan.',
                    'redirect_url' => route('login')
                ], 401);
            }
            
            // Simpan URL yang diminta untuk redirect setelah login (hanya untuk GET request)
            if ($request->isMethod('GET')) {
                $request->session()->put('url.intended', $request->fullUrl());
            }
            
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu untuk melanjutkan.');
        }

        return $next($request);
    }
}
```

### Fitur Utama

#### 1. Authentication Check
- **Fungsi**: Memastikan user sudah login
- **Scope**: Semua request types (GET, POST, AJAX)

#### 2. AJAX/POST Request Handling
- **Detection**: `$request->expectsJson() || $request->isMethod('POST')`
- **Response**: JSON response dengan status 401
- **Data Structure**:
  ```json
  {
    "error": "Unauthorized",
    "message": "Silakan login terlebih dahulu untuk melanjutkan.",
    "redirect_url": "/login"
  }
  ```

#### 3. Intended URL Preservation
- **Scope**: Hanya untuk GET requests
- **Mechanism**: Menyimpan URL yang diminta dalam session
- **Key**: `url.intended`
- **Purpose**: Redirect user ke halaman yang diminta setelah login

#### 4. Standard Redirect
- **Target**: Route login
- **Message Type**: Info (bukan error)
- **Message**: "Silakan login terlebih dahulu untuk melanjutkan."

### Usage dalam Routes

```php
// Customer orders (memerlukan login)
Route::prefix('orders')->name('customer.orders.')->middleware('auth')->group(function () {
    Route::get('/', [Customer\OrderController::class, 'index']);
    Route::get('/{orderNumber}', [Customer\OrderController::class, 'show']);
});

// Testimonials (memerlukan login)
Route::prefix('testimonials')->name('customer.testimonials.')->middleware('auth')->group(function () {
    Route::post('/store', [TestimonialController::class, 'store']);
});
```

**Note**: Dalam implementasi saat ini, routes menggunakan built-in `auth` middleware Laravel, bukan `require.login` custom middleware.

---

## Built-in Laravel Middleware

### 1. Auth Middleware
**Alias:** `auth`
**Source:** Laravel built-in
**Usage:** Digunakan untuk routes yang memerlukan autentikasi

```php
// Customer orders
Route::prefix('orders')->middleware('auth')->group(function () {
    // Routes yang memerlukan login
});

// Sales reports (dengan auth, bukan admin)
Route::prefix('admin/sales')->middleware(['auth'])->group(function () {
    // Routes yang memerlukan login tapi tidak harus admin
});
```

### 2. Guest Middleware
**Alias:** `guest`
**Source:** Laravel built-in
**Usage:** Untuk routes yang hanya boleh diakses oleh guest (belum login)

```php
// Login dan register routes (biasanya)
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login');
```

---

## Middleware Flow & Logic

### 1. Request Processing Flow

```
Incoming Request
       ↓
   Middleware Stack
       ↓
  [AdminMiddleware]
       ↓
   Check Auth::check()
       ↓
   ┌─ No → Redirect to Login
   └─ Yes → Check isAdmin()
              ↓
          ┌─ No → Redirect to Home with Error
          └─ Yes → Continue to Controller
                      ↓
                  Controller Action
                      ↓
                   Response
```

### 2. RequireLoginMiddleware Flow

```
Incoming Request
       ↓
   Check Auth::check()
       ↓
   ┌─ Yes → Continue to Controller
   └─ No → Check Request Type
              ↓
          ┌─ AJAX/POST → JSON Response (401)
          └─ GET → Save Intended URL → Redirect to Login
```

---

## Security Considerations

### 1. Authentication Security
- **Session Management**: Menggunakan Laravel session untuk auth state
- **CSRF Protection**: Semua POST requests protected by CSRF
- **Secure Redirects**: Validasi redirect URLs untuk mencegah open redirect

### 2. Authorization Security
- **Role-based Access**: Menggunakan `isAdmin()` method pada User model
- **Principle of Least Privilege**: Admin access hanya untuk yang diperlukan
- **Fail-safe Defaults**: Default behavior adalah deny access

### 3. Error Handling Security
- **Information Disclosure**: Error messages tidak mengungkap informasi sensitif
- **Consistent Responses**: Consistent error handling untuk semua scenarios
- **Logging**: Potential untuk logging unauthorized access attempts

---

## Performance Considerations

### 1. Database Queries
- **Auth Check**: `Auth::check()` menggunakan session, tidak query database
- **User Role Check**: `Auth::user()->isAdmin()` mungkin memerlukan database query
- **Optimization**: Consider caching user roles dalam session

### 2. Middleware Order
- **Efficient Ordering**: Auth check sebelum role check
- **Early Termination**: Redirect segera jika tidak authenticated
- **Minimal Processing**: Avoid heavy operations dalam middleware

---

## Testing Middleware

### 1. Unit Testing AdminMiddleware

```php
public function test_admin_middleware_allows_admin_user()
{
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);
    
    $response = $this->get('/dashboard');
    $response->assertStatus(200);
}

public function test_admin_middleware_redirects_regular_user()
{
    $user = User::factory()->create(['role' => 'user']);
    $this->actingAs($user);
    
    $response = $this->get('/dashboard');
    $response->assertRedirect('/');
    $response->assertSessionHas('error');
}

public function test_admin_middleware_redirects_guest()
{
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
    $response->assertSessionHas('error');
}
```

### 2. Feature Testing dengan Middleware

```php
public function test_inventory_access_requires_admin()
{
    // Test guest access
    $response = $this->get('/inventory');
    $response->assertRedirect('/login');
    
    // Test regular user access
    $user = User::factory()->create(['role' => 'user']);
    $response = $this->actingAs($user)->get('/inventory');
    $response->assertRedirect('/');
    
    // Test admin access
    $admin = User::factory()->create(['role' => 'admin']);
    $response = $this->actingAs($admin)->get('/inventory');
    $response->assertStatus(200);
}
```

---

## Best Practices

### 1. Middleware Design
- **Single Responsibility**: Setiap middleware memiliki satu tanggung jawab
- **Reusable**: Middleware dapat digunakan di multiple routes
- **Configurable**: Parameter untuk customization jika diperlukan

### 2. Error Handling
- **User-friendly Messages**: Pesan error yang mudah dipahami user
- **Consistent UX**: Consistent redirect dan message patterns
- **Graceful Degradation**: Handle edge cases dengan baik

### 3. Security Best Practices
- **Fail Secure**: Default behavior adalah deny access
- **Input Validation**: Validate semua input parameters
- **Audit Trail**: Log security-relevant events

### 4. Performance Optimization
- **Early Returns**: Return segera jika kondisi tidak terpenuhi
- **Minimal Database Queries**: Cache user data jika memungkinkan
- **Efficient Checks**: Order checks dari yang paling efficient

---

## Troubleshooting

### 1. Common Issues

#### Infinite Redirect Loop
```php
// Problem: Admin middleware di route login
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->middleware('admin'); // ❌ Wrong!

// Solution: Jangan gunakan admin middleware di login route
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login'); // ✅ Correct
```

#### Session Issues
```php
// Problem: Session tidak tersimpan
// Check: Session configuration di config/session.php
// Check: Session middleware dalam middleware stack
```

#### AJAX Request Issues
```javascript
// Problem: AJAX request tidak handle 401 response
fetch('/api/endpoint')
    .then(response => {
        if (response.status === 401) {
            const data = response.json();
            window.location.href = data.redirect_url;
        }
        return response.json();
    });
```

### 2. Debugging Tips

#### Log Middleware Execution
```php
public function handle(Request $request, Closure $next): Response
{
    \Log::info('AdminMiddleware: Checking user', [
        'user_id' => Auth::id(),
        'is_admin' => Auth::check() ? Auth::user()->isAdmin() : false,
        'route' => $request->route()->getName()
    ]);
    
    // ... rest of middleware logic
}
```

#### Check Middleware Registration
```php
// Dalam tinker atau test
php artisan tinker
>>> app('router')->getMiddleware()
>>> app('router')->getMiddlewareGroups()
```

---

## Future Enhancements

### 1. Role-based Permissions
```php
// Enhanced role checking
class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        
        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'Unauthorized');
        }
        
        return $next($request);
    }
}

// Usage
Route::get('/admin/users', [UserController::class, 'index'])
    ->middleware('role:admin,super_admin');
```

### 2. Permission-based Access
```php
// Permission middleware
class PermissionMiddleware
{
    public function handle($request, Closure $next, $permission)
    {
        if (!Auth::user()->hasPermission($permission)) {
            abort(403);
        }
        
        return $next($request);
    }
}

// Usage
Route::post('/inventory/create', [InventoryController::class, 'store'])
    ->middleware('permission:inventory.create');
```

### 3. Rate Limiting
```php
// API rate limiting
Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    Route::get('/api/orders', [OrderController::class, 'apiIndex']);
});
```

Dokumentasi ini memberikan gambaran lengkap tentang sistem middleware dalam aplikasi RAVAZKA, termasuk implementasi, usage, security considerations, dan best practices untuk pengembangan dan maintenance yang efektif.