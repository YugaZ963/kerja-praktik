<?php

namespace App\Services;

class GoogleMapsService
{
    /**
     * Get Google Maps API key from configuration
     */
    public static function getApiKey(): string
    {
        return config('googlemaps.api_key');
    }

    /**
     * Get store location coordinates from configuration
     */
    public static function getStoreLocation(): array
    {
        return config('googlemaps.store_location');
    }

    /**
     * Get map settings from configuration
     */
    public static function getMapSettings(): array
    {
        return config('googlemaps.map_settings');
    }

    /**
     * Generate Google Maps URL for directions
     */
    public static function getDirectionsUrl(): string
    {
        // URL Google Maps spesifik untuk Pasar Baru, Bandung
        return "https://www.google.com/maps/place/Pasar+Baru/@-6.9175278,107.6017623,17z/data=!4m10!1m2!2m1!1spasar+baru+bandung!3m6!1s0x2e68e7b221d5a3fb:0x973f88ac86b287cb!8m2!3d-6.9174201!4d107.604071!15sChJwYXNhciBiYXJ1IGJhbmR1bmeSAQ9zaG9wcGluZ19jZW50ZXKqAToQATIeEAEiGpqO-8GFwkw9c-Hnd-paBfu99tFHOZjdvceDMhYQAiIScGFzYXIgYmFydSBiYW5kdW5n4AEA!16s%2Fg%2F11fm4_3t0k?entry=ttu&g_ep=EgoyMDI1MDczMC4wIKXMDSoASAFQAw%3D%3D";
    }

    /**
     * Generate simple Google Maps URL for coordinates
     */
    public static function getSimpleDirectionsUrl(): string
    {
        $location = self::getStoreLocation();
        return "https://maps.google.com/?q={$location['lat']},{$location['lng']}";
    }

    /**
     * Generate Google Maps embed URL
     */
    public static function getEmbedUrl(): string
    {
        $location = self::getStoreLocation();
        $apiKey = self::getApiKey();
        
        return "https://www.google.com/maps/embed/v1/place?key={$apiKey}&q={$location['lat']},{$location['lng']}&zoom=16";
    }
}