<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Product;
use Carbon\Carbon;

/**
 * Class SitemapController
 *
 * Handles the generation of the sitemap and robots.txt file.
 */
class SitemapController extends Controller
{
    /**
     * Generate the XML sitemap.
     *
     * @return Response
     */
    public function index(): Response
    {
        $sitemap = $this->generateSitemap();
        
        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
    
    /**
     * Generate the sitemap XML content.
     *
     * @return string
     */
    private function generateSitemap(): string
    {
        $baseUrl = config('app.url');
        $now = Carbon::now()->toISOString();
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        $xml .= $this->addUrl($baseUrl, $now, 'daily', '1.0');
        
        $staticPages = [
            '/products' => ['weekly', '0.9'],
            '/contact' => ['monthly', '0.7'],
            '/about' => ['monthly', '0.6'],
        ];
        
        foreach ($staticPages as $page => $config) {
            $xml .= $this->addUrl($baseUrl . $page, $now, $config[0], $config[1]);
        }
        
        $products = Product::where('status', 'active')
            ->select('slug', 'updated_at')
            ->get();
            
        foreach ($products as $product) {
            $lastmod = $product->updated_at ? $product->updated_at->toISOString() : $now;
            $xml .= $this->addUrl($baseUrl . '/products/' . $product->slug, $lastmod, 'weekly', '0.8');
        }
        
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
     * Add a URL to the sitemap XML.
     *
     * @param string $url
     * @param string $lastmod
     * @param string $changefreq
     * @param string $priority
     * @return string
     */
    private function addUrl(string $url, string $lastmod, string $changefreq, string $priority): string
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
     * Generate the robots.txt file content.
     *
     * @return Response
     */
    public function robots(): Response
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