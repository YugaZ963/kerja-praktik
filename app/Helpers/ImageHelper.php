<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class ImageHelper
 *
 * A helper class for handling images.
 */
class ImageHelper
{
    /**
     * Generate an optimized image tag with SEO attributes.
     *
     * @param string $imagePath
     * @param string $altText
     * @param array $options
     * @return string
     */
    public static function optimizedImage(string $imagePath, string $altText = '', array $options = []): string
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
        
        $imageUrl = self::getImageUrl($imagePath, $options['fallback']);
        
        if (empty($altText)) {
            $altText = self::generateAltText($imagePath);
        }
        
        $attributes = [
            'src' => $imageUrl,
            'alt' => $altText,
            'class' => $options['class'],
            'loading' => $options['loading']
        ];
        
        if ($options['width']) {
            $attributes['width'] = $options['width'];
        }
        
        if ($options['height']) {
            $attributes['height'] = $options['height'];
        }
        
        if ($options['sizes']) {
            $attributes['sizes'] = $options['sizes'];
        }
        
        if ($options['srcset']) {
            $attributes['srcset'] = $options['srcset'];
        }
        
        if ($options['placeholder'] && $options['loading'] === 'lazy') {
            $attributes['data-src'] = $imageUrl;
            $attributes['src'] = self::generatePlaceholder($options['width'], $options['height']);
        }
        
        return self::buildImageTag($attributes);
    }
    
    /**
     * Get the URL for an image with a fallback.
     *
     * @param string $imagePath
     * @param string $fallback
     * @return string
     */
    public static function getImageUrl(string $imagePath, string $fallback = 'images/no-image.jpg'): string
    {
        if (empty($imagePath)) {
            return asset($fallback);
        }
        
        if (Str::startsWith($imagePath, 'storage/') || Str::startsWith($imagePath, 'public/')) {
            $storagePath = Str::startsWith($imagePath, 'storage/') 
                ? Str::after($imagePath, 'storage/') 
                : Str::after($imagePath, 'public/');
                
            if (Storage::disk('public')->exists($storagePath)) {
                return asset($imagePath);
            }
        }
        
        if (file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }
        
        return asset($fallback);
    }
    
    /**
     * Generate alt text from an image path.
     *
     * @param string $imagePath
     * @return string
     */
    public static function generateAltText(string $imagePath): string
    {
        if (empty($imagePath)) {
            return 'RAVAZKA school uniform product image';
        }
        
        $filename = pathinfo($imagePath, PATHINFO_FILENAME);
        $altText = str_replace(['-', '_'], ' ', $filename);
        $altText = ucwords($altText);
        
        if (!Str::contains(strtolower($altText), ['uniform', 'shirt', 'pants', 'skirt'])) {
            $altText .= ' - RAVAZKA School Uniform';
        }
        
        return $altText;
    }
    
    /**
     * Generate a placeholder image for lazy loading.
     *
     * @param int|null $width
     * @param int|null $height
     * @return string
     */
    public static function generatePlaceholder(?int $width = 300, ?int $height = 200): string
    {
        $width = $width ?: 300;
        $height = $height ?: 200;
        
        $svg = '<svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">';
        $svg .= '<rect width="100%" height="100%" fill="#f8f9fa"/>';
        $svg .= '<text x="50%" y="50%" font-family="Arial, sans-serif" font-size="14" fill="#6c757d" text-anchor="middle" dy=".3em">Loading...</text>';
        $svg .= '</svg>';
        
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
    
    /**
     * Build an image tag from an array of attributes.
     *
     * @param array $attributes
     * @return string
     */
    private static function buildImageTag(array $attributes): string
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
     * Generate a product image with SEO optimization.
     *
     * @param \App\Models\Product $product
     * @param array $options
     * @return string
     */
    public static function productImage(\App\Models\Product $product, array $options = []): string
    {
        $altText = $product->name . ' - ' . $product->category . ' Uniform RAVAZKA';
        
        if ($product->size) {
            $altText .= ' Size ' . $product->size;
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
     * Generate a thumbnail image.
     *
     * @param string $imagePath
     * @param string $altText
     * @param int $size
     * @return string
     */
    public static function thumbnail(string $imagePath, string $altText = '', int $size = 150): string
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
     * Generate a hero image with optimization.
     *
     * @param string $imagePath
     * @param string $altText
     * @param array $options
     * @return string
     */
    public static function heroImage(string $imagePath, string $altText = '', array $options = []): string
    {
        $defaultOptions = [
            'class' => 'hero-image img-fluid w-100',
            'loading' => 'eager',
            'sizes' => '100vw',
            'placeholder' => false
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        return self::optimizedImage($imagePath, $altText, $options);
    }
}