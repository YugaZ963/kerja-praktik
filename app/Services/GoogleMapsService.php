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
        return "https://www.google.com/maps/place/Pasar+Baru,+Bandung/@-6.9175278,107.6017623,17z";
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