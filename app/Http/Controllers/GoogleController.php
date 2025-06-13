<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class GoogleController extends Controller
{
    /**
     * Get Google reviews for a place
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReviews(Request $request)
    {
        // Google Place ID (you can pass this as a request parameter or hard-code it)
        $placeId = 'ChIJYcGuC85jcVMR8QCt0elMLtE'; // Replace with your Place ID or make it dynamic

        // Google Places API key from the .env file
        $apiKey = '477464924947-8bothhj4igsrhi1ep9f67s1n6mh93e2n.apps.googleusercontent.com';

        // Google Places API endpoint
        $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id={$placeId}&fields=name,rating,reviews&key={$apiKey}";

        // Initialize Guzzle HTTP client
        $client = new Client();

        try {
            // Make the GET request to Google Places API
            $response = $client->get($url);
            $responseData = json_decode($response->getBody()->getContents(), true);

            // Check if reviews are available
            if (isset($responseData['result']['reviews'])) {
                return response()->json($responseData['result']['reviews']);
            } else {
                return response()->json(['message' => 'No reviews found'], 404);
            }
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json(['error' => 'Failed to fetch reviews', 'details' => $e->getMessage()], 500);
        }
    }
}
