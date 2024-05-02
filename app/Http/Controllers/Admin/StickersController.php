<?php

namespace App\Http\Controllers\Admin;

use GuzzleHttp\Client;
use App\Models\Sticker;
use Illuminate\Http\Request;
use Kreait\Firebase\ServiceAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Google\Cloud\Firestore\FirestoreClient;
use Kreait\Firebase\Factory; // Use correct namespace


class StickersController extends Controller
{


    public function index()
    {
        try {
            // Initialize GuzzleHTTP client
            $client = new Client([
                'base_uri' => 'https://firestore.googleapis.com/v1/projects/genzee-baddies-1/databases/(default)/documents/',
            ]);
        
            // Send request to fetch stickers data
            $response = $client->get('stickers'); // Change 'complaints' to 'stickers'
            $stickersData = json_decode($response->getBody()->getContents(), true)['documents'];
        
            // Initialize an empty array to store sticker URLs
            $stickerUrls = [];
        
            // Iterate through each document in the stickers collection
            foreach ($stickersData as $document) {
                // Extract stickerUrls from the document
                $urls = $document['fields']['stickerUrls']['arrayValue']['values'];
                $urls = array_map(function ($url) {
                    return $url['stringValue'];
                }, $urls);
                // Add the sticker URLs to the array
                $stickerUrls = array_merge($stickerUrls, $urls);
            }
        
            // Paginate the sticker URLs
            $perPage = 10; // Number of items per page
            $currentPage = request()->query('page', 1); // Get the current page from the query string
            $totalUrls = count($stickerUrls);
            $offset = ($currentPage - 1) * $perPage;
            $stickerUrlsPaginated = array_slice($stickerUrls, $offset, $perPage);
            $stickerUrlsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
                $stickerUrlsPaginated,
                $totalUrls,
                $perPage,
                $currentPage,
                ['path' => url()->current()]
            );
        
            // Pass sticker URLs to the view and render the view
            return view('admin.stickers.index', ['stickerUrlsPaginated' => $stickerUrlsPaginated]);
        } catch (\Exception $e) {
            // Handle error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function destroy(Request $request, $url)
    {
        try {
            // Find the sticker by URL
            $sticker = Sticker::where('url', $url)->first();

            if ($sticker) {
                // Delete the sticker
                $sticker->delete();

                return redirect()->route('admin.stickers.index')->with('success', 'Sticker deleted successfully');
            } else {
                return redirect()->route('admin.stickers.index')->with('error', 'Sticker not found');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.stickers.index')->with('error', 'Failed to delete sticker');
        }
    }

    // public function index()
    // {
    //     // Set your GIPHY API key
    //     $apiKey = "UGwce8H7Oc4dQuhXDCw81IyWHWRdq6pM";
    
    //     // Set request parameters
    //     $limit = 600; // Number of stickers to return
    
    //     // Build the API endpoint URL
    //     $endpoint = "https://api.giphy.com/v1/stickers/trending?api_key=$apiKey&limit=$limit";
    
    //     // Initialize cURL session
    //     $curl = curl_init();
    
    //     // Set cURL options
    //     curl_setopt($curl, CURLOPT_URL, $endpoint);
    //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    //     // Execute cURL request
    //     $response = curl_exec($curl);
    
    //     // Check for errors
    //     if ($response === false) {
    //         $errorMessage = "Failed to fetch stickers from the GIPHY API: " . curl_error($curl);
    //         // Handle error
    //         curl_close($curl);
    //         return view('admin.stickers.index')->with('errorMessage', $errorMessage);
    //     }
    
    //     // Close cURL session
    //     curl_close($curl);
    
    //     // Decode JSON response
    //     $stickerData = json_decode($response, true);
    
    //     // Check if stickers were successfully retrieved
    //     if (isset($stickerData['data'])) {
    //         $stickers = $stickerData['data'];

    //                     // Paginate the fetched stickers after uploading to Firebase Storage
    //             $perPage = 10; // Number of items per page
    //             $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
    //             $totalItems = count($stickers);

    //             // Calculate the starting index for the current page
    //             $start = ($page - 1) * $perPage;

    //             // Get only the items for the current page
    //             $currentPageItems = array_slice($stickers, $start, $perPage);

    //             // Create a LengthAwarePaginator instance with the current page's items
    //             $stickers = new \Illuminate\Pagination\LengthAwarePaginator(
    //                 $currentPageItems,
    //                 $totalItems,
    //                 $perPage,
    //                 $page,
    //                 ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
    //             );

            
    //         return view('admin.stickers.index', compact('stickers'));
    //         }else {
    //         $errorMessage = "Failed to fetch stickers from the GIPHY API.";
    //         return view('admin.stickers.index')->with('errorMessage', $errorMessage);
    //     }
    // }

}
