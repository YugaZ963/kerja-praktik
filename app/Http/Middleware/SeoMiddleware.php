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
                
            case 'customer.orders.index':
                return $this->getOrdersPageSeoData();
                
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
                'title' => 'RAVAZKA - Toko Seragam Sekolah Terpercaya | Kualitas Terbaik Harga Terjangkau',
                'description' => 'Toko seragam sekolah RAVAZKA menyediakan seragam berkualitas untuk SD, SMP, SMA. Tersedia berbagai ukuran, model terlengkap, harga terjangkau. Pesan online sekarang!',
                'keywords' => 'toko seragam sekolah, beli seragam online, seragam sekolah murah, seragam berkualitas, RAVAZKA, seragam SD SMP SMA',
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
        
        $title = 'Katalog Seragam Sekolah';
        $description = 'Jelajahi koleksi lengkap seragam sekolah RAVAZKA. Tersedia untuk SD, SMP, SMA dengan berbagai ukuran dan model.';
        
        if ($category) {
            $title = "Seragam {$category} - RAVAZKA";
            $description = "Koleksi seragam {$category} berkualitas tinggi dari RAVAZKA. Tersedia berbagai ukuran dengan harga terjangkau.";
        }
        
        if ($search) {
            $title = "Hasil Pencarian: {$search} - RAVAZKA";
            $description = "Hasil pencarian seragam sekolah untuk '{$search}' di RAVAZKA. Temukan seragam yang Anda cari.";
        }
        
        return [
            'title' => $title,
            'description' => $description,
            'keywords' => 'katalog seragam, daftar produk seragam, ' . ($category ? "seragam {$category}, " : '') . 'RAVAZKA',
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
        
        $product = Product::where('slug', $slug)->with('inventory')->first();
        
        if (!$product) {
            return [];
        }
        
        return [
            'title' => "{$product->name} - Seragam Sekolah Berkualitas | RAVAZKA",
            'description' => "Beli {$product->name} berkualitas tinggi di RAVAZKA. {$product->description} Harga: Rp " . number_format($product->price, 0, ',', '.') . ". Stok tersedia: {$product->stock}.",
            'keywords' => "{$product->name}, {$product->category}, seragam {$product->size}, {$product->slug}, RAVAZKA",
            'image' => $product->image ? asset('storage/' . $product->image) : asset('images/ravazka.jpg'),
            'type' => 'product',
            'structured_data' => $this->getProductStructuredData($product)
        ];
    }
    
    /**
     * Get SEO data for contact page
     */
    private function getContactPageSeoData()
    {
        return [
            'title' => 'Hubungi Kami - RAVAZKA Toko Seragam Sekolah',
            'description' => 'Hubungi RAVAZKA untuk informasi seragam sekolah, konsultasi ukuran, atau pertanyaan lainnya. Kami siap membantu Anda 24/7.',
            'keywords' => 'kontak RAVAZKA, hubungi toko seragam, customer service, alamat toko seragam',
            'type' => 'website'
        ];
    }
    
    /**
     * Get SEO data for orders page
     */
    private function getOrdersPageSeoData()
    {
        return [
            'title' => 'Pesanan Saya - RAVAZKA',
            'description' => 'Lihat status pesanan seragam sekolah Anda di RAVAZKA. Pantau proses pengiriman dan riwayat pembelian.',
            'keywords' => 'pesanan seragam, status pesanan, riwayat pembelian, RAVAZKA',
            'type' => 'website',
            'robots' => 'noindex, nofollow' // Private page
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