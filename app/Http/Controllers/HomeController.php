<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $apiUrl = 'https://killnetswitch.com/monitor/stan/api/v1/stats.php';

        // Make GET request to the API
        $apiResponse = file_get_contents($apiUrl);

        // Check if response is valid
        if ($apiResponse !== false) {
            // Decode JSON response
            $data = json_decode($apiResponse, true);

            // Pass API data to the view
            return view('home', compact('data'));
        } else {
            // Handle failed API request
            return response()->json(['error' => 'Failed to fetch data from the API'], 500);
        }

        // return view('home', compact($data));
    }

    private function fetchApiData()
    {
        try {
            // API URL
            $apiUrl = 'https://killnetswitch.com/monitor/stan/api/v1/stats.php';

            // Make GET request to the API
            $apiResponse = file_get_contents($apiUrl);

            // Check if response is valid
            if ($apiResponse !== false) {
                // Decode JSON response
                $apiData = json_decode($apiResponse, true);

                // Pass API data to the view
                return view('api.index', compact('apiData'));
            } else {
                // Handle failed API request
                return response()->json(['error' => 'Failed to fetch data from the API'], 500);
            }
        } catch (\Exception $e) {
            // Handle exceptions (e.g., API not available)
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
