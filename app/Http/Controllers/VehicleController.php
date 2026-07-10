<?php

<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    // Mise à jour de la position GPS
    public function updatePosition(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->latitude = $request->latitude;
        $vehicle->longitude = $request->longitude;
        $vehicle->save();

        return response()->json(['message' => 'Position mise à jour']);
    }

    // Récupérer la position GPS
    public function getPosition($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return response()->json([
            'plate_number' => $vehicle->plate_number,
            'latitude' => $vehicle->latitude,
            'longitude' => $vehicle->longitude,
        ]);
    }
}
