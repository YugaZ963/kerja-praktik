<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * Class BreadcrumbHelper
 *
 * A helper class for generating breadcrumbs.
 */
class BreadcrumbHelper
{
    /**
     * Generate breadcrumbs for the current route.
     *
     * @return array
     */
    public static function generate(): array
    {
        $routeName = Route::currentRouteName();
        $routeParameters = Route::current()->parameters();
        $breadcrumbs = [];
        
        $breadcrumbs[] = [
            'title' => 'Home',
            'url' => url('/'),
            'active' => false
        ];
        
        switch ($routeName) {
            case 'customer.products':
                $breadcrumbs[] = [
                    'title' => 'Products',
                    'url' => route('customer.products'),
                    'active' => true
                ];
                break;
                
            case 'customer.product.detail':
                $breadcrumbs[] = [
                    'title' => 'Products',
                    'url' => route('customer.products'),
                    'active' => false
                ];
                
                if (isset($routeParameters['slug'])) {
                    $product = \App\Models\Product::where('slug', $routeParameters['slug'])->first();
                    if ($product) {
                        $breadcrumbs[] = [
                            'title' => $product->name,
                            'url' => route('customer.product.detail', $product->slug),
                            'active' => true
                        ];
                    }
                }
                break;
                
            case 'contact.index':
                $breadcrumbs[] = [
                    'title' => 'Contact',
                    'url' => route('contact.index'),
                    'active' => true
                ];
                break;
                
            case 'customer.orders.index':
                $breadcrumbs[] = [
                    'title' => 'My Orders',
                    'url' => route('customer.orders.index'),
                    'active' => true
                ];
                break;
                
            case 'customer.orders.show':
                $breadcrumbs[] = [
                    'title' => 'My Orders',
                    'url' => route('customer.orders.index'),
                    'active' => false
                ];
                
                if (isset($routeParameters['orderNumber'])) {
                    $breadcrumbs[] = [
                        'title' => 'Order Details #' . $routeParameters['orderNumber'],
                        'url' => route('customer.orders.show', $routeParameters['orderNumber']),
                        'active' => true
                    ];
                }
                break;
                
            default:
                $segments = request()->segments();
                $url = url('/');
                
                foreach ($segments as $segment) {
                    $url .= '/' . $segment;
                    $title = ucfirst(str_replace('-', ' ', $segment));
                    
                    $breadcrumbs[] = [
                        'title' => $title,
                        'url' => $url,
                        'active' => $url === request()->url()
                    ];
                }
                break;
        }
        
        return $breadcrumbs;
    }
    
    /**
     * Generate structured data for breadcrumbs.
     *
     * @param array|null $breadcrumbs
     * @return array
     */
    public static function getStructuredData(?array $breadcrumbs = null): array
    {
        if (!$breadcrumbs) {
            $breadcrumbs = self::generate();
        }
        
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => []
        ];
        
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $structuredData['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['title'],
                'item' => $breadcrumb['url']
            ];
        }
        
        return $structuredData;
    }
    
    /**
     * Render the breadcrumbs HTML.
     *
     * @param array|null $breadcrumbs
     * @param bool $showStructuredData
     * @return string
     */
    public static function render(?array $breadcrumbs = null, bool $showStructuredData = true): string
    {
        if (!$breadcrumbs) {
            $breadcrumbs = self::generate();
        }
        
        if (count($breadcrumbs) <= 1) {
            return '';
        }
        
        $html = '<nav aria-label="breadcrumb">';
        $html .= '<ol class="breadcrumb">';
        
        foreach ($breadcrumbs as $breadcrumb) {
            if ($breadcrumb['active']) {
                $html .= '<li class="breadcrumb-item active" aria-current="page">' . e($breadcrumb['title']) . '</li>';
            } else {
                $html .= '<li class="breadcrumb-item"><a href="' . e($breadcrumb['url']) . '">' . e($breadcrumb['title']) . '</a></li>';
            }
        }
        
        $html .= '</ol>';
        $html .= '</nav>';
        
        if ($showStructuredData) {
            $structuredData = self::getStructuredData($breadcrumbs);
            $html .= '<script type="application/ld+json">';
            $html .= json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            $html .= '</script>';
        }
        
        return $html;
    }
}