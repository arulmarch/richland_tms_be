<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class CartrackApiService
{
    protected $client;
    protected $username;
    protected $password;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->username = env('CARTRACK_USERNAME');
        $this->password = env('CARTRACK_PASSWORD');
        $this->baseUrl = env('CARTRACK_BASE_URL');  // Update to your base URL
    }

    /**
     * Make an API request to the Cartrack API and fetch data from a given endpoint.
     *
     * @param string $endpoint
     * @return mixed
     */

     public function getData($endpoint)
    {
        $url = $this->baseUrl . $endpoint; // Construct the full URL

        try {
            // Create the Authorization header manually
            $credentials = base64_encode("{$this->username}:{$this->password}");
            $headers = [
                'Authorization' => "Basic {$credentials}",
            ];

            // Make the GET request with the custom header
            $response = $this->client->request('GET', $url, [
                'headers' => $headers
            ]);

            $data = json_decode($response->getBody(), true);

             // Add the URL to the response array
            $data['url'] = $url;
            return $data;
        } catch (\Exception $e) {
            Log::error('Cartrack API Request Failed: ' . $e->getMessage());
            return null;
        }
    }


    // public function getData($endpoint)
    // {
    //     $url = $this->baseUrl . $endpoint;  // Construct the full URL

    //     try {
    //         $response = $this->client->request('GET', $url, [
    //             'auth' => [$this->username, $this->password]  // Use 'auth' for automatic Basic Auth handling
    //         ]);

    //         $data = json_decode($response->getBody(), true);
    //         return $data;
    //     } catch (\Exception $e) {
    //         Log::error('Cartrack API Request Failed: ' . $e->getMessage());
    //         return null;
    //     }

    // }
}
