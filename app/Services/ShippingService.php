<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShippingService
{
    private $rajaOngkirApiKey;
    private $rajaOngkirBaseUrl;
    private $originCityId; // ID kota asal (Yogyakarta)

    public function __construct()
    {
        $this->rajaOngkirApiKey = config('services.rajaongkir.api_key', 'your-api-key-here');
        $this->rajaOngkirBaseUrl = config('services.rajaongkir.base_url', 'https://api.rajaongkir.com/starter');
        $this->originCityId = config('services.rajaongkir.origin_city_id', 501); // Yogyakarta
    }

    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        try {
            $response = Http::withHeaders([
                'key' => $this->rajaOngkirApiKey
            ])->get($this->rajaOngkirBaseUrl . '/province');

            if ($response->successful()) {
                return $response->json()['rajaongkir']['results'];
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Error fetching provinces: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get cities by province ID
     */
    public function getCitiesByProvince($provinceId)
    {
        try {
            $response = Http::withHeaders([
                'key' => $this->rajaOngkirApiKey
            ])->get($this->rajaOngkirBaseUrl . '/city', [
                'province' => $provinceId
            ]);

            if ($response->successful()) {
                return $response->json()['rajaongkir']['results'];
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Error fetching cities: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Calculate shipping cost
     */
    public function calculateShippingCost($destinationCityId, $weight, $couriers = ['jne', 'jnt'])
    {
        try {
            $results = [];

            foreach ($couriers as $courier) {
                $response = Http::withHeaders([
                    'key' => $this->rajaOngkirApiKey,
                    'content-type' => 'application/x-www-form-urlencoded'
                ])->post($this->rajaOngkirBaseUrl . '/cost', [
                    'origin' => $this->originCityId,
                    'destination' => $destinationCityId,
                    'weight' => $weight,
                    'courier' => $courier
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['rajaongkir']['results'][0])) {
                        $results[$courier] = $data['rajaongkir']['results'][0];
                    }
                }
            }

            return $results;
        } catch (\Exception $e) {
            Log::error('Error calculating shipping cost: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get formatted shipping options
     */
    public function getShippingOptions($destinationCityId, $weight)
    {
        $shippingData = $this->calculateShippingCost($destinationCityId, $weight);
        $options = [];

        foreach ($shippingData as $courierCode => $courierData) {
            $courierName = strtoupper($courierCode);
            
            if (isset($courierData['costs'])) {
                foreach ($courierData['costs'] as $service) {
                    $options[] = [
                        'courier' => $courierCode,
                        'courier_name' => $courierName,
                        'service' => $service['service'],
                        'description' => $service['description'],
                        'cost' => $service['cost'][0]['value'],
                        'etd' => $service['cost'][0]['etd'],
                        'formatted_cost' => 'Rp ' . number_format($service['cost'][0]['value'], 0, ',', '.'),
                        'display_name' => $courierName . ' - ' . $service['service'] . ' (' . $service['cost'][0]['etd'] . ' hari)'
                    ];
                }
            }
        }

        // Sort by cost
        usort($options, function($a, $b) {
            return $a['cost'] - $b['cost'];
        });

        return $options;
    }

    /**
     * Get city name by ID
     */
    public function getCityById($cityId)
    {
        try {
            $response = Http::withHeaders([
                'key' => $this->rajaOngkirApiKey
            ])->get($this->rajaOngkirBaseUrl . '/city', [
                'id' => $cityId
            ]);

            if ($response->successful()) {
                $results = $response->json()['rajaongkir']['results'];
                return !empty($results) ? $results[0] : null;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error fetching city: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Calculate total weight from cart items
     */
    public function calculateTotalWeight($cartItems)
    {
        $totalWeight = 0;
        
        foreach ($cartItems as $item) {
            // Asumsi berat per item adalah 500 gram, bisa disesuaikan
            $itemWeight = $item->product->weight ?? 500; // gram
            $totalWeight += $itemWeight * $item->quantity;
        }

        // Minimal weight 1000 gram (1 kg) untuk pengiriman
        return max($totalWeight, 1000);
    }
}