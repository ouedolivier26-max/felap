<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TrackingController extends Controller
{
    public function track($trackingNumber)
    {
        // Exemple DHL
        $dhlResponse = Http::withHeaders([
            'DHL-API-Key' => env('DHL_API_KEY'),
        ])->get('https://api.dhl.com/track/shipments', [
            'trackingNumber' => $trackingNumber,
        ]);

        if ($dhlResponse->successful() && !empty($dhlResponse->json()['shipments'])) {
            return response()->json([
                'carrier' => 'DHL',
                'data' => $dhlResponse->json(),
            ]);
        }

        // Exemple UPS
        $upsResponse = Http::withHeaders([
            'AccessLicenseNumber' => env('UPS_API_KEY'),
            'Username' => env('UPS_USERNAME'),
            'Password' => env('UPS_PASSWORD'),
        ])->post('https://onlinetools.ups.com/track/v1/details/'.$trackingNumber);

        if ($upsResponse->successful()) {
            return response()->json([
                'carrier' => 'UPS',
                'data' => $upsResponse->json(),
            ]);
        }

        return response()->json([
            'error' => 'Colis introuvable chez DHL ou UPS',
        ], 404);
    }
}
