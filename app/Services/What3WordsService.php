<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class What3WordsService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.what3words.com/v3';

    public function __construct()
    {
        $this->apiKey = config('services.what3words.api_key');
    }

    /**
     * Convert coordinates to what3words address
     * 
     * @param float $latitude
     * @param float $longitude
     * @return string|null Returns 3 words address or null if failed
     */
    public function convertToWords(float $latitude, float $longitude): ?string
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/convert-to-3wa", [
                'coordinates' => "{$latitude},{$longitude}",
                'key' => $this->apiKey,
                'format' => 'json',
                'language' => 'id' // Bahasa Indonesia
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['words'] ?? null;
            }

            Log::warning('What3Words API failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('What3Words API error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Convert what3words address to coordinates
     * 
     * @param string $words 3 words address (e.g., "index.home.raft")
     * @return array|null Returns ['latitude' => float, 'longitude' => float] or null if failed
     */
    public function convertToCoordinates(string $words): ?array
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/convert-to-coordinates", [
                'words' => $words,
                'key' => $this->apiKey,
                'format' => 'json',
                'language' => 'id'
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['coordinates'])) {
                    return [
                        'latitude' => $data['coordinates']['lat'],
                        'longitude' => $data['coordinates']['lng']
                    ];
                }
            }

            Log::warning('What3Words coordinate conversion failed', [
                'words' => $words,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('What3Words coordinate conversion error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Validate what3words address format
     * 
     * @param string $words
     * @return bool
     */
    public function isValidFormat(string $words): bool
    {
        // What3Words format: word.word.word (each word 1-64 chars, alphanumeric)
        return preg_match('/^[a-zA-Z0-9]+\.[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/', $words) === 1;
    }

    /**
     * Generate Google Maps URL from coordinates
     * 
     * @param float $latitude
     * @param float $longitude
     * @return string
     */
    public function getGoogleMapsUrl(float $latitude, float $longitude): string
    {
        return "https://maps.google.com/?q={$latitude},{$longitude}";
    }

    /**
     * Get fallback location text when What3Words fails
     * 
     * @param float $latitude
     * @param float $longitude
     * @return string
     */
    public function getFallbackLocationText(float $latitude, float $longitude): string
    {
        return "Koordinat: {$latitude}, {$longitude}";
    }
}
