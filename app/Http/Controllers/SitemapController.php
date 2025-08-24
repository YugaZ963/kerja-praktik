<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Product;
use Carbon\Carbon;

class SitemapController extends Controller
{
    /**
     * Generate XML sitemap
     */
    public function index()
    {
        $sitemap = $this->generateSitemap();
        
        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
    
    /**
     * Generate sitemap XML content
     */
    private function generateSitemap()
    {
        $baseUrl = config('app.url');
        $now = Carbon::now()->toISOString();
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Homepage
        $xml .= $this->addUrl($baseUrl, $now, 'daily', '1.0');
        
        // Static pages
        $staticPages = [
            '/products' => ['weekly', '0.9'],
            '/contact' => ['monthly', '0.7'],
            '/about' => ['monthly', '0.6'],
        ];
        
        foreach ($staticPages as $page => $config) {
            $xml .= $this->addUrl($baseUrl . $page, $now, $config[0], $config[1]);
        }
        
        // Product pages
        $products = Product::where('status', 'active')
            ->select('slug', 'updated_at')
            ->get();
            
        foreach ($products as $product) {
            $lastmod = $product->updated_at ? $product->updated_at->toISOString() : $now;
            $xml .= $this->addUrl($baseUrl . '/products/' . $product->slug, $lastmod, 'weekly', '0.8');
        }
        
        // Product categories
        $categories = Product::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category');
            
        foreach ($categories as $category) {
            $xml .= $this->addUrl($baseUrl . '/products?category=' . urlencode($category), $now, 'weekly', '0.7');
        }
        
        $xml .= '</urlset>';
        
        return $xml;
    }
    
    /**
     * Add URL to sitemap
     */
    private function addUrl($url, $lastmod, $changefreq, $priority)
    {
        $xml = "  <url>\n";
        $xml .= "    <loc>" . htmlspecialchars($url) . "</loc>\n";
        $xml .= "    <lastmod>" . $lastmod . "</lastmod>\n";
        $xml .= "    <changefreq>" . $changefreq . "</changefreq>\n";
        $xml .= "    <priority>" . $priority . "</priority>\n";
        $xml .= "  </url>\n";
        
        return $xml;
    }
    
    /**
     * Generate robots.txt content dynamically
     */
    public function robots()
    {
        $baseUrl = config('app.url');
        
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Allow: /products\n";
        $content .= "Allow: /products/*\n";
        $content .= "Allow: /contact\n";
        $content .= "Allow: /about\n";
        $content .= "\n";
        $content .= "# Disallow admin and private areas\n";
        $content .= "Disallow: /admin\n";
        $content .= "Disallow: /admin/*\n";
        $content .= "Disallow: /customer/orders\n";
        $content .= "Disallow: /customer/orders/*\n";
        $content .= "Disallow: /customer/profile\n";
        $content .= "Disallow: /customer/profile/*\n";
        $content .= "Disallow: /login\n";
        $content .= "Disallow: /register\n";
        $content .= "Disallow: /password\n";
        $content .= "Disallow: /password/*\n";
        $content .= "\n";
        $content .= "# Disallow API endpoints\n";
        $content .= "Disallow: /api\n";
        $content .= "Disallow: /api/*\n";
        $content .= "\n";
        $content .= "# Disallow storage and uploads\n";
        $content .= "Disallow: /storage\n";
        $content .= "Disallow: /storage/*\n";
        $content .= "\n";
        $content .= "# Allow important files\n";
        $content .= "Allow: /sitemap.xml\n";
        $content .= "Allow: /favicon.ico\n";
        $content .= "\n";
        $content .= "# Sitemap location\n";
        $content .= "Sitemap: {$baseUrl}/sitemap.xml\n";
        $content .= "\n";
        $content .= "# Crawl delay (optional)\n";
        $content .= "Crawl-delay: 1\n";
        
        return response($content, 200, [
            'Content-Type' => 'text/plain'
        ]);
    }
}