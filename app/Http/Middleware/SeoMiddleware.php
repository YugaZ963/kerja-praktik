<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Product;
use App\Models\Testimonial;

class SeoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Set default SEO data
        $seoData = $this->getDefaultSeoData();
        
        // Get route-specific SEO data
        $routeSpecificData = $this->getRouteSpecificSeoData($request);
        
        // Merge with route-specific data
        $seoData = array_merge($seoData, $routeSpecificData);
        
        // Share SEO data with all views
        View::share('seoData', $seoData);
        
        return $response;
    }
    
    /**
     * Get default SEO data for the application
     */
    private function getDefaultSeoData()
    {
        return [
            'title' => 'RAVAZKA - Toko Seragam Sekolah Terpercaya',
            'description' => 'RAVAZKA menyediakan seragam sekolah berkualitas tinggi untuk SD, SMP, dan SMA. Tersedia berbagai ukuran dan model dengan harga terjangkau. Pesan sekarang!',
            'keywords' => 'seragam sekolah, baju sekolah, celana sekolah, rok sekolah, topi sekolah, seragam SD, seragam SMP, seragam SMA, RAVAZKA',
            'image' => asset('images/ravazka.jpg'),
            'url' => request()->url(),
            'type' => 'website',
            'site_name' => 'RAVAZKA',
            'locale' => 'id_ID',
            'author' => 'RAVAZKA Team',
            'robots' => 'index, follow',
            'canonical' => request()->url(),
            'structured_data' => $this->getBusinessStructuredData()
        ];
    }
    
    /**
     * Get route-specific SEO data
     */
    private function getRouteSpecificSeoData(Request $request)
    {
        $routeName = $request->route()->getName();
        $routeParameters = $request->route()->parameters();
        
        switch ($routeName) {
            case 'customer.products':
                return $this->getProductsPageSeoData($request);
                
            case 'customer.product.detail':
                return $this->getProductDetailSeoData($routeParameters['slug'] ?? null);
                
            case 'contact.index':
                return $this->getContactPageSeoData();
                
            case 'about.index':
                return $this->getAboutPageSeoData();
                
            case 'customer.orders.index':
                return $this->getOrdersPageSeoData();
                
            case 'customer.orders.show':
                return $this->getOrderDetailSeoData();
                
            case 'customer.orders.track':
                return $this->getOrderTrackSeoData();
                
            case 'cart.index':
                return $this->getCartPageSeoData();
                
            case 'cart.checkout':
                return $this->getCheckoutPageSeoData();
                
            case 'login':
                return $this->getLoginPageSeoData();
                
            case 'register':
                return $this->getRegisterPageSeoData();
                
            case 'dashboard':
                return $this->getDashboardSeoData();
                
            case 'inventory.index':
                return $this->getInventoryIndexSeoData();
                
            case 'admin.orders.index':
                return $this->getAdminOrdersSeoData();
                
            case 'admin.sales.index':
                return $this->getSalesReportSeoData();
                
            default:
                return $this->getHomePageSeoData($request);
        }
    }
    
    /**
     * Get SEO data for homepage
     */
    private function getHomePageSeoData(Request $request)
    {
        if ($request->is('/')) {
            return [
                'title' => '🏫 RAVAZKA - Toko Seragam Sekolah Terpercaya #1 | Kualitas Premium Harga Terjangkau ✨',
                'description' => '⭐ Toko seragam sekolah RAVAZKA terpercaya sejak 2010! Menyediakan seragam berkualitas premium untuk SD, SMP, SMA. ✅ Berbagai ukuran lengkap ✅ Model terbaru ✅ Harga terjangkau ✅ Pengiriman cepat. Pesan online sekarang juga!',
                'keywords' => 'toko seragam sekolah terpercaya, beli seragam online murah, seragam sekolah berkualitas, RAVAZKA seragam, seragam SD SMP SMA, toko seragam Jakarta, seragam sekolah terlengkap',
                'structured_data' => array_merge(
                    $this->getBusinessStructuredData(),
                    $this->getWebsiteStructuredData()
                )
            ];
        }
        
        return [];
    }
    
    /**
     * Get SEO data for products page
     */
    private function getProductsPageSeoData(Request $request)
    {
        $category = $request->get('category');
        $search = $request->get('search');
        
        $title = '📚 Katalog Seragam Sekolah Lengkap - RAVAZKA | Semua Jenjang Tersedia';
        $description = '🛍️ Jelajahi koleksi lengkap seragam sekolah RAVAZKA! Tersedia untuk SD, SMP, SMA dengan berbagai ukuran dan model terbaru. ✅ Kualitas terjamin ✅ Harga bersaing ✅ Stok lengkap.';
        
        if ($category) {
            $title = "🎓 Seragam {$category} Berkualitas Premium - RAVAZKA | Harga Terbaik";
            $description = "⭐ Koleksi seragam {$category} berkualitas tinggi dari RAVAZKA. Tersedia berbagai ukuran dengan harga terjangkau. ✅ Bahan premium ✅ Jahitan rapi ✅ Tahan lama.";
        }
        
        if ($search) {
            $title = "🔍 Hasil Pencarian: {$search} - RAVAZKA Seragam Sekolah";
            $description = "Hasil pencarian seragam sekolah untuk '{$search}' di RAVAZKA. Temukan seragam berkualitas yang Anda cari dengan harga terjangkau.";
        }
        
        return [
            'title' => $title,
            'description' => $description,
            'keywords' => 'katalog seragam lengkap, daftar produk seragam, ' . ($category ? "seragam {$category} berkualitas, " : '') . 'RAVAZKA terpercaya, beli seragam online',
            'type' => 'website'
        ];
    }
    
    /**
     * Get SEO data for product detail page
     */
    private function getProductDetailSeoData($slug)
    {
        if (!$slug) {
            return [];
        }
        
        try {
            $product = Product::where('slug', $slug)->with('inventory')->first();
            
            if (!$product) {
                // Return generic fallback data if product not found
                return [
                    'title' => '👕 Detail Seragam Sekolah Premium - RAVAZKA | Kualitas Terjamin',
                    'description' => '🔍 Lihat detail lengkap produk seragam sekolah berkualitas di RAVAZKA. Informasi ukuran, harga, spesifikasi, dan review pelanggan. ✅ Bahan premium ✅ Harga terjangkau ✅ Garansi kualitas.',
                    'keywords' => 'detail seragam berkualitas, spesifikasi produk seragam, harga seragam terjangkau, RAVAZKA premium, beli seragam online',
                    'type' => 'product'
                ];
            }
            
            return [
                'title' => "👕 {$product->name} - Seragam Sekolah Premium | RAVAZKA Kualitas Terjamin ⭐",
                'description' => "🛍️ Beli {$product->name} berkualitas premium di RAVAZKA. {$product->description} 💰 Harga: Rp " . number_format($product->price, 0, ',', '.') . ". ✅ Stok tersedia: {$product->stock} ✅ Bahan berkualitas ✅ Jahitan rapi ✅ Tahan lama.",
                'keywords' => "{$product->name} berkualitas, {$product->category} premium, seragam {$product->size} terbaik, {$product->slug}, RAVAZKA terpercaya, beli seragam online murah",
                'image' => $product->image ? asset('storage/' . $product->image) : asset('images/ravazka.jpg'),
                'type' => 'product',
                'structured_data' => $this->getProductStructuredData($product)
            ];
        } catch (\Exception $e) {
            // Return generic fallback data on any error
            return [
                'title' => '👕 Detail Seragam Sekolah Premium - RAVAZKA | Kualitas Terjamin',
                'description' => '🔍 Lihat detail lengkap produk seragam sekolah berkualitas di RAVAZKA. Informasi ukuran, harga, spesifikasi, dan review pelanggan. ✅ Bahan premium ✅ Harga terjangkau ✅ Garansi kualitas.',
                'keywords' => 'detail seragam berkualitas, spesifikasi produk seragam, harga seragam terjangkau, RAVAZKA premium, beli seragam online',
                'type' => 'product'
            ];
        }
    }
    
    /**
     * Get SEO data for contact page
     */
    private function getContactPageSeoData()
    {
        return [
            'title' => '📞 Hubungi Kami - RAVAZKA | Customer Service 24/7 Siap Membantu',
            'description' => '💬 Hubungi RAVAZKA untuk pertanyaan seputar seragam sekolah. Tim customer service profesional kami siap membantu Anda 24/7. ✅ Respon cepat ✅ Solusi terbaik ✅ Konsultasi gratis.',
            'keywords' => 'kontak RAVAZKA, customer service seragam, hubungi toko seragam, bantuan pelanggan, konsultasi seragam sekolah',
            'type' => 'website'
        ];
    }
    
    /**
     * Get SEO data for orders page
     */
    private function getOrdersPageSeoData()
    {
        return [
            'title' => '📦 Pesanan Saya - RAVAZKA | Pantau Status & Riwayat Pembelian',
            'description' => '📋 Lihat status pesanan seragam sekolah Anda di RAVAZKA. Pantau proses pengiriman dan riwayat pembelian dengan mudah. ✅ Update real-time ✅ Tracking lengkap.',
            'keywords' => 'pesanan seragam online, status pesanan RAVAZKA, riwayat pembelian seragam, tracking pesanan, cek status order',
            'type' => 'website',
            'robots' => 'noindex, nofollow' // Private page
        ];
    }

    /**
     * Get SEO data for about page
     */
    private function getAboutPageSeoData()
    {
        return [
            'title' => '🏢 Tentang RAVAZKA - Toko Seragam Sekolah Terpercaya Sejak 2010',
            'description' => '📖 Kenali lebih dekat RAVAZKA, toko seragam sekolah terpercaya sejak 2010. Komitmen kami memberikan seragam berkualitas premium dengan pelayanan terbaik untuk pendidikan Indonesia.',
            'keywords' => 'tentang RAVAZKA, profil toko seragam, sejarah RAVAZKA, visi misi toko seragam, toko seragam terpercaya',
            'type' => 'website'
        ];
    }

    /**
     * Get SEO data for order detail page
     */
    private function getOrderDetailSeoData()
    {
        return [
            'title' => '📋 Detail Pesanan - RAVAZKA | Informasi Lengkap Pembelian',
            'description' => '🔍 Lihat detail lengkap pesanan seragam sekolah Anda di RAVAZKA. Informasi produk, status pengiriman, dan rincian pembayaran tersedia di sini.',
            'keywords' => 'detail pesanan RAVAZKA, info lengkap order, rincian pembelian seragam, status detail pesanan',
            'type' => 'website'
        ];
    }

    /**
     * Get SEO data for order tracking page
     */
    private function getOrderTrackSeoData()
    {
        return [
            'title' => '🚚 Lacak Pesanan - RAVAZKA | Tracking Pengiriman Real-time',
            'description' => '📍 Lacak pesanan seragam sekolah Anda secara real-time di RAVAZKA. Pantau posisi paket dan estimasi waktu tiba dengan akurat.',
            'keywords' => 'lacak pesanan RAVAZKA, tracking pengiriman seragam, cek posisi paket, estimasi pengiriman',
            'type' => 'website'
        ];
    }

    /**
     * Get SEO data for cart page
     */
    private function getCartPageSeoData()
    {
        return [
            'title' => '🛒 Keranjang Belanja - RAVAZKA | Review Sebelum Checkout',
            'description' => '🛍️ Review keranjang belanja seragam sekolah Anda di RAVAZKA. Periksa item, jumlah, dan total harga sebelum melanjutkan ke pembayaran.',
            'keywords' => 'keranjang belanja RAVAZKA, review pesanan seragam, checkout seragam sekolah, total belanja',
            'type' => 'website'
        ];
    }

    /**
     * Get SEO data for checkout page
     */
    private function getCheckoutPageSeoData()
    {
        return [
            'title' => '💳 Checkout - RAVAZKA | Selesaikan Pembelian Seragam Anda',
            'description' => '✅ Selesaikan pembelian seragam sekolah di RAVAZKA. Pilih metode pembayaran, isi data pengiriman, dan konfirmasi pesanan Anda dengan aman.',
            'keywords' => 'checkout RAVAZKA, pembayaran seragam sekolah, konfirmasi pesanan, metode bayar seragam',
            'type' => 'website'
        ];
    }

    /**
     * Get SEO data for login page
     */
    private function getLoginPageSeoData()
    {
        return [
            'title' => '🔐 Masuk - RAVAZKA | Login ke Akun Anda',
            'description' => '🚪 Masuk ke akun RAVAZKA Anda untuk mengakses fitur lengkap, melihat riwayat pesanan, dan berbelanja seragam sekolah dengan mudah.',
            'keywords' => 'login RAVAZKA, masuk akun seragam, sign in toko seragam, akses akun pelanggan',
            'type' => 'website'
        ];
    }

    /**
     * Get SEO data for register page
     */
    private function getRegisterPageSeoData()
    {
        return [
            'title' => '📝 Daftar - RAVAZKA | Buat Akun Baru Gratis',
            'description' => '🆕 Daftar akun baru di RAVAZKA secara gratis! Nikmati kemudahan berbelanja seragam sekolah, tracking pesanan, dan penawaran eksklusif.',
            'keywords' => 'daftar RAVAZKA, buat akun seragam, register toko seragam, sign up gratis',
            'type' => 'website'
        ];
    }

    /**
     * Get SEO data for dashboard page
     */
    private function getDashboardSeoData()
    {
        return [
            'title' => '📊 Dashboard Admin - RAVAZKA | Panel Kontrol Toko',
            'description' => '⚙️ Dashboard admin RAVAZKA untuk mengelola toko seragam sekolah. Pantau penjualan, kelola produk, dan analisis performa bisnis.',
            'keywords' => 'dashboard admin RAVAZKA, panel kontrol toko, manajemen seragam, admin toko online',
            'type' => 'website'
        ];
    }

    /**
     * Get SEO data for inventory index page
     */
    private function getInventoryIndexSeoData()
    {
        return [
            'title' => '📦 Manajemen Inventori - RAVAZKA | Kelola Stok Seragam',
            'description' => '📋 Kelola inventori dan stok seragam sekolah di RAVAZKA. Pantau ketersediaan produk, update stok, dan optimalisasi inventory management.',
            'keywords' => 'manajemen inventori RAVAZKA, kelola stok seragam, inventory management, stok seragam sekolah',
            'type' => 'website'
        ];
    }

    /**
     * Get SEO data for admin orders page
     */
    private function getAdminOrdersSeoData()
    {
        return [
            'title' => '📋 Kelola Pesanan - RAVAZKA Admin | Manajemen Order',
            'description' => '🛠️ Panel admin untuk mengelola semua pesanan seragam sekolah di RAVAZKA. Update status, proses pembayaran, dan koordinasi pengiriman.',
            'keywords' => 'kelola pesanan admin, manajemen order RAVAZKA, admin pesanan seragam, proses order',
            'type' => 'website'
        ];
    }

    /**
     * Get SEO data for sales report page
     */
    private function getSalesReportSeoData()
    {
        return [
            'title' => '📈 Laporan Penjualan - RAVAZKA | Analisis Performa Bisnis',
            'description' => '📊 Laporan penjualan lengkap RAVAZKA dengan analisis performa bisnis. Pantau revenue, trend penjualan, dan insights untuk pengembangan toko.',
            'keywords' => 'laporan penjualan RAVAZKA, analisis bisnis seragam, sales report, performa toko online',
            'type' => 'website'
        ];
    }
    
    /**
     * Get business structured data
     */
    private function getBusinessStructuredData()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => 'RAVAZKA',
            'description' => 'Toko seragam sekolah terpercaya yang menyediakan seragam berkualitas untuk SD, SMP, dan SMA',
            'url' => config('app.url'),
            'telephone' => '+62-xxx-xxxx-xxxx', // Update with actual phone
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Jl. Contoh No. 123', // Update with actual address
                'addressLocality' => 'Jakarta',
                'addressRegion' => 'DKI Jakarta',
                'postalCode' => '12345',
                'addressCountry' => 'ID'
            ],
            'openingHours' => 'Mo-Sa 08:00-17:00',
            'priceRange' => '$$',
            'image' => asset('images/ravazka.jpg')
        ];
    }
    
    /**
     * Get website structured data
     */
    private function getWebsiteStructuredData()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'RAVAZKA',
            'url' => config('app.url'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => config('app.url') . '/products?search={search_term_string}',
                'query-input' => 'required name=search_term_string'
            ]
        ];
    }
    
    /**
     * Get product structured data
     */
    private function getProductStructuredData($product)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $product->description,
            'image' => $product->image ? asset('storage/' . $product->image) : asset('images/ravazka.jpg'),
            'sku' => $product->id,
            'category' => $product->category,
            'brand' => [
                '@type' => 'Brand',
                'name' => 'RAVAZKA'
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->price,
                'priceCurrency' => 'IDR',
                'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => 'RAVAZKA'
                ]
            ],
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => '4.5',
                'reviewCount' => Testimonial::count()
            ]
        ];
    }
}