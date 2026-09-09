<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleMapsService
{
    /**
     * Preset distance fallbacks for common Greater Jakarta / West Java areas
     * from Soekarno-Hatta Terminal 3 (Garage baseline).
     */
    protected static array $presetDistances = [
        'bandara soekarno-hatta' => ['km' => 0.0, 'duration' => '0 Menit (Ambil di Garasi/Pool)'],
        'cgk' => ['km' => 0.0, 'duration' => '0 Menit (Ambil di Garasi/Pool)'],
        'pool rental' => ['km' => 0.0, 'duration' => '0 Menit (Ambil di Garasi/Pool)'],
        'jakarta barat' => ['km' => 22.5, 'duration' => '35 Menit'],
        'jakarta pusat' => ['km' => 31.2, 'duration' => '45 Menit'],
        'scbd' => ['km' => 33.4, 'duration' => '50 Menit'],
        'thamrin' => ['km' => 29.8, 'duration' => '45 Menit'],
        'gambir' => ['km' => 32.0, 'duration' => '48 Menit'],
        'halim' => ['km' => 42.1, 'duration' => '1 Jam 5 Menit'],
        'jakarta selatan' => ['km' => 35.0, 'duration' => '50 Menit'],
        'jakarta utara' => ['km' => 27.3, 'duration' => '40 Menit'],
        'jakarta timur' => ['km' => 45.0, 'duration' => '1 Jam 10 Menit'],
        'tangerang' => ['km' => 14.5, 'duration' => '25 Menit'],
        'bsd' => ['km' => 34.0, 'duration' => '45 Menit'],
        'serpong' => ['km' => 33.0, 'duration' => '45 Menit'],
        'bekasi' => ['km' => 52.0, 'duration' => '1 Jam 25 Menit'],
        'depok' => ['km' => 48.5, 'duration' => '1 Jam 20 Menit'],
        'bogor' => ['km' => 78.0, 'duration' => '1 Jam 50 Menit'],
        'puncak' => ['km' => 105.0, 'duration' => '2 Jam 45 Menit'],
        'bandung' => ['km' => 175.0, 'duration' => '3 Jam 15 Menit'],
        'karawang' => ['km' => 88.0, 'duration' => '2 Jam'],
    ];

    /**
     * Calculate driving distance and delivery cost from garage to customer destination.
     */
    public static function calculateDelivery(string $destination): array
    {
        $garage = config('services.google_maps.garage_address', 'Bandara Soekarno-Hatta Terminal 3, Tangerang');
        $ratePerKm = (int) config('services.google_maps.rate_per_km', 4000);
        $apiKey = config('services.google_maps.api_key');

        $destinationTrimmed = trim($destination);
        if (empty($destinationTrimmed)) {
            return [
                'success' => false,
                'message' => 'Alamat penjemputan tidak boleh kosong.',
                'distance_km' => 0,
                'duration_text' => '-',
                'delivery_cost' => 0,
                'rate_per_km' => $ratePerKm,
                'is_fallback' => false,
            ];
        }

        // Check if Google Maps Distance Matrix API Key is configured
        if (!empty($apiKey)) {
            try {
                $response = Http::timeout(5)->get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                    'origins' => $garage,
                    'destinations' => $destinationTrimmed,
                    'mode' => 'driving',
                    'language' => 'id',
                    'key' => $apiKey,
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (($data['status'] ?? '') === 'OK' && !empty($data['rows'][0]['elements'][0])) {
                        $element = $data['rows'][0]['elements'][0];

                        if (($element['status'] ?? '') === 'OK') {
                            $distanceMeters = $element['distance']['value'] ?? 0;
                            $durationText = $element['duration']['text'] ?? 'Estimasi waktu perjalanan';
                            $distanceKm = round($distanceMeters / 1000, 1);

                            // Waive delivery cost if less than 3 km (around garage/pool area)
                            $cost = ($distanceKm <= 3.0) ? 0 : (int) ($distanceKm * $ratePerKm);

                            return [
                                'success' => true,
                                'distance_km' => $distanceKm,
                                'duration_text' => $durationText,
                                'delivery_cost' => $cost,
                                'rate_per_km' => $ratePerKm,
                                'origin' => $garage,
                                'destination' => $destinationTrimmed,
                                'is_fallback' => false,
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Google Maps Distance Matrix API error: {$e->getMessage()}. Using fallback calculation.");
            }
        }

        // Heuristic & Preset Fallback (Ensures 100% system availability without API block)
        $lowerDest = strtolower($destinationTrimmed);
        $matchedKm = null;
        $matchedDuration = '45 Menit';

        foreach (self::$presetDistances as $keyword => $info) {
            if (str_contains($lowerDest, $keyword)) {
                $matchedKm = $info['km'];
                $matchedDuration = $info['duration'];
                break;
            }
        }

        if ($matchedKm === null) {
            // General city estimation fallback
            if (str_contains($lowerDest, 'bandara') || str_contains($lowerDest, 'airport') || str_contains($lowerDest, 'pool')) {
                $matchedKm = 0.0;
                $matchedDuration = '0 Menit (Ambil di Pool/Garasi)';
            } elseif (str_contains($lowerDest, 'luar kota') || str_contains($lowerDest, 'jawa barat') || str_contains($lowerDest, 'banten')) {
                $matchedKm = 65.0;
                $matchedDuration = '1 Jam 40 Menit';
            } else {
                // Default Jabodetabek average distance from Soetta
                $matchedKm = 30.0;
                $matchedDuration = '45 Menit';
            }
        }

        $cost = ($matchedKm <= 3.0) ? 0 : (int) ($matchedKm * $ratePerKm);

        return [
            'success' => true,
            'distance_km' => $matchedKm,
            'duration_text' => $matchedDuration,
            'delivery_cost' => $cost,
            'rate_per_km' => $ratePerKm,
            'origin' => $garage,
            'destination' => $destinationTrimmed,
            'is_fallback' => true,
            'note' => empty($apiKey) ? 'Kalkulasi berbasis geolokasi cerdas (Google Maps API Key belum dikonfigurasi di .env).' : null,
        ];
    }
}
