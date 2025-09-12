<?php

namespace App\Services;

/**
 * Class GoogleMapsService
 *
 * A service class for handling Google Maps related functionality.
 */
class GoogleMapsService
{
    /**
     * Get the Google Maps API key from the configuration.
     *
     * @return string
     */
    public static function getApiKey(): string
    {
        return config('googlemaps.api_key');
    }

    /**
     * Get the store location coordinates from the configuration.
     *
     * @return array
     */
    public static function getStoreLocation(): array
    {
        return config('googlemaps.store_location');
    }

    /**
     * Get the map settings from the configuration.
     *
     * @return array
     */
    public static function getMapSettings(): array
    {
        return config('googlemaps.map_settings');
    }

    /**
     * Generate the Google Maps URL for directions.
     *
     * @return string
     */
    public static function getDirectionsUrl(): string
    {
        return "https://www.google.com/maps/place/Pasar+Baru,+Bandung/@-6.9175278,107.6017623,17z";
    }

    /**
     * Generate a simple Google Maps URL for the store's coordinates.
     *
     * @return string
     */
    public static function getSimpleDirectionsUrl(): string
    {
        $location = self::getStoreLocation();
        return "https://maps.google.com/?q={$location['lat']},{$location['lng']}";
    }

    /**
     * Generate the Google Maps embed URL.
     *
     * @return string
     */
    public static function getEmbedUrl(): string
    {
        $location = self::getStoreLocation();
        $apiKey = self::getApiKey();
        
        return "https://www.google.com/maps/embed/v1/place?key={$apiKey}&q={$location['lat']},{$location['lng']}&zoom=16";
    }
}