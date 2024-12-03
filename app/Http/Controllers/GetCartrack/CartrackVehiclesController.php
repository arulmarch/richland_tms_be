<?php

namespace App\Http\Controllers\GetCartrack;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\CartrackApiService;

class CartrackVehiclesController extends Controller
{

    protected $cartrackApiService;

    public function __construct(CartrackApiService $cartrackApiService)
    {
        $this->cartrackApiService = $cartrackApiService;
    }

    /**
     * Get the list of vehicles from the Cartrack API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVehicles()
    {
        try {
            // Make the API request to the vehicles endpoint
            $data = $this->cartrackApiService->getData('/vehicles');  // Endpoint '/vehicles' appended to the base URL

            // Check if the data is returned correctly
            if ($data) {
                return response()->json($data, 200);
            } else {
                return response()->json(['error' => 'Unable to fetch data from Cartrack API'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching vehicles from Cartrack API: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching vehicles'], 500);
        }
    }


    public function getVehiclesStatus($vehicle_id)
    {
        try {
            // Make the API request to the vehicles endpoint
            $data = $this->cartrackApiService->getData('/vehicles/status');  // Endpoint '/vehicles' appended to the base URL

            // Check if the data is returned correctly
            if ($data) {
                return response()->json($data, 200);
            } else {
                return response()->json(['error' => 'Unable to fetch data from Cartrack API'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching vehicles from Cartrack API: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching vehicles'], 500);
        }
    }

    public function getVehicleTracking(Request $request)
    {
        try {
            // Ambil vehicle_id dari request dan pastikan itu adalah array
            $vehicleIds = $request->input('vehicle_id'); //result bentuk array = ["B9438UEJ","B9586SEI","B9584SEI"]
            implode(',', $vehicleIds); //diubah = B9438UEJ,B9586SEI,B9584SEI

            // Endpoint URL
            $endpoint = '/vehicles/status';

            // ambil semua data dari API
            $data = $this->cartrackApiService->getData($endpoint);
            // $data = $this->cartrackApiService->getData($endpoint, ['filter[registration]' => implode(',', $vehicleIds)]);

            // Periksa apakah data berhasil diterima
            if ($data && isset($data['data'])) {
                // Ubah data menjadi koleksi untuk memudahkan manipulasi
                $vehicles = collect($data['data']);

                // Filter kendaraan berdasarkan registrasi yang diminta
                $filteredVehicles = $vehicles->whereIn('registration', $vehicleIds);

                // Group berdasarkan registration dan ambil data terakhir berdasarkan 'event_ts'
                $latestVehicles = $filteredVehicles->groupBy('registration')->map(function ($group) {
                    return $group->sortByDesc('event_ts')->first();
                });

                // Hapus data electric, fuel, dan driver
                $latestVehicles = $latestVehicles->map(function ($vehicle) {
                    // Hapus properti yang tidak perlu
                    unset($vehicle['driver'], $vehicle['fuel'], $vehicle['electric']);
                    return $vehicle;
                });

                return response()->json($latestVehicles->values(), 200); // Kembalikan sebagai array
            } else {
                return response()->json(['error' => 'Unable to fetch or process data from Cartrack API'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching vehicles from Cartrack API: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching vehicles: ' . $e->getMessage()], 500);
        }
    }





    public function getVehiclesGroups()
    {
        try {
            // Make the API request to the vehicles endpoint
            $data = $this->cartrackApiService->getData('/vehicles/groups');  // Endpoint '/vehicles' appended to the base URL

            // Check if the data is returned correctly
            if ($data) {

                return response()->json($data, 200);
            } else {
                return response()->json(['error' => 'Unable to fetch data from Cartrack API'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching vehicles from Cartrack API: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching vehicles'], 500);
        }
    }
}
