<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Generate optimized image tag with SEO attributes
     */
    public static function optimizedImage($imagePath, $altText = '', $options = [])
    {
        $defaults = [
            'class' => 'img-fluid',
            'loading' => 'lazy',
            'width' => null,
            'height' => null,
            'sizes' => null,
            'srcset' => null,
            'placeholder' => true,
            'fallback' => 'images/no-image.jpg'
        ];
        
        $options = array_merge($defaults, $options);
        
        // Generate image URL
        $imageUrl = self::getImageUrl($imagePath, $options['fallback']);
        
        // Generate alt text if not provided
        if (empty($altText)) {
            $altText = self::generateAltText($imagePath);
        }
        
        // Build image attributes
        $attributes = [
            'src' => $imageUrl,
            'alt' => $altText,
            'class' => $options['class'],
            'loading' => $options['loading']
        ];
        
        // Add dimensions if provided
        if ($options['width']) {
            $attributes['width'] = $options['width'];
        }
        
        if ($options['height']) {
            $attributes['height'] = $options['height'];
        }
        
        // Add responsive attributes
        if ($options['sizes']) {
            $attributes['sizes'] = $options['sizes'];
        }
        
        if ($options['srcset']) {
            $attributes['srcset'] = $options['srcset'];
        }
        
        // Add placeholder for lazy loading
        if ($options['placeholder'] && $options['loading'] === 'lazy') {
            $attributes['data-src'] = $imageUrl;
            $attributes['src'] = self::generatePlaceholder($options['width'], $options['height']);
        }
        
        return self::buildImageTag($attributes);
    }
    
    /**
     * Get image URL with fallback
     */
    public static function getImageUrl($imagePath, $fallback = 'images/no-image.jpg')
    {
        if (empty($imagePath)) {
            return asset($fallback);
        }
        
        // Check if it's a storage path
        if (Str::startsWith($imagePath, 'storage/') || Str::startsWith($imagePath, 'public/')) {
            $storagePath = Str::startsWith($imagePath, 'storage/') 
                ? Str::after($imagePath, 'storage/') 
                : Str::after($imagePath, 'public/');
                
            if (Storage::disk('public')->exists($storagePath)) {
                return asset($imagePath);
            }
        }
        
        // Check if it's a public asset
        if (file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }
        
        // Return fallback
        return asset($fallback);
    }
    
    /**
     * Generate alt text from image path
     */
    public static function generateAltText($imagePath)
    {
        if (empty($imagePath)) {
            return 'Gambar produk seragam sekolah RAVAZKA';
        }
        
        $filename = pathinfo($imagePath, PATHINFO_FILENAME);
        $altText = str_replace(['-', '_'], ' ', $filename);
        $altText = ucwords($altText);
        
        // Add context for better SEO
        if (!Str::contains(strtolower($altText), ['seragam', 'baju', 'celana', 'rok'])) {
            $altText .= ' - Seragam Sekolah RAVAZKA';
        }
        
        return $altText;
    }
    
    /**
     * Generate placeholder image for lazy loading
     */
    public static function generatePlaceholder($width = 300, $height = 200)
    {
        $width = $width ?: 300;
        $height = $height ?: 200;
        
        // Generate a simple SVG placeholder
        $svg = '<svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">';
        $svg .= '<rect width="100%" height="100%" fill="#f8f9fa"/>';
        $svg .= '<text x="50%" y="50%" font-family="Arial, sans-serif" font-size="14" fill="#6c757d" text-anchor="middle" dy=".3em">Loading...</text>';
        $svg .= '</svg>';
        
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
    
    /**
     * Build image tag from attributes
     */
    private static function buildImageTag($attributes)
    {
        $html = '<img';
        
        foreach ($attributes as $key => $value) {
            if ($value !== null) {
                $html .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
            }
        }
        
        $html .= '>';
        
        return $html;
    }
    
    /**
     * Generate product image with SEO optimization
     */
    public static function productImage($product, $options = [])
    {
        $altText = $product->name . ' - Seragam ' . $product->category . ' RAVAZKA';
        
        if ($product->size) {
            $altText .= ' Ukuran ' . $product->size;
        }
        
        $defaultOptions = [
            'class' => 'product-image img-fluid',
            'width' => 300,
            'height' => 300,
            'sizes' => '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw'
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        return self::optimizedImage($product->image, $altText, $options);
    }
    
    /**
     * Generate thumbnail image
     */
    public static function thumbnail($imagePath, $altText = '', $size = 150)
    {
        $options = [
            'class' => 'thumbnail img-fluid',
            'width' => $size,
            'height' => $size,
            'loading' => 'lazy'
        ];
        
        return self::optimizedImage($imagePath, $altText, $options);
    }
    
    /**
     * Generate hero image with optimization
     */
    public static function heroImage($imagePath, $altText = '', $options = [])
    {
        $defaultOptions = [
            'class' => 'hero-image img-fluid w-100',
            'loading' => 'eager', // Hero images should load immediately
            'sizes' => '100vw',
            'placeholder' => false
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        return self::optimizedImage($imagePath, $altText, $options);
    }
}