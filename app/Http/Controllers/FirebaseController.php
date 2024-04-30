<?php

namespace App\Http\Controllers;


use GuzzleHttp\Client;
use App\Mail\CodeEmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Facades\Mail;
use Google\Cloud\Firestore\FirestoreClient;
use Kreait\Firebase\Factory; // Use correct namespace




class FirebaseController extends Controller
{

    public function index()
    {
        // Initialize GuzzleHTTP client
        $client = new Client([
            'base_uri' => 'https://firestore.googleapis.com/v1/projects/genzee-baddies-1/databases/(default)/documents/',
        ]);
    
        try {
            // Send request to fetch users data
            $response = $client->get('users');
            $usersData = json_decode($response->getBody()->getContents(), true);
    
            $users = [];
    
            // Iterate through each document in the users collection
            foreach ($usersData['documents'] as $document) {
                // Extract user data from the document
                $userData = $document['fields'];
    
                // Add the user data to the array
                $users[] = $userData;
            }
    
            // Paginate the user data
            $perPage = 10; // Number of items per page
            $currentPage = request()->query('page', 1); // Get the current page from the query string
            $usersPaginated = \Illuminate\Pagination\Paginator::resolveCurrentPage('usersPage');
            $usersPaginated = array_slice($users, ($currentPage - 1) * $perPage, $perPage);
            $usersPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
                $usersPaginated,
                count($users),
                $perPage,
                $currentPage,
                ['path' => url()->current()]
            );
    
            // Pass user data to the view
            return view('admin.users.users', [
                'users' => $users,
                'usersPaginated' => $usersPaginated,
            ]); 
        } catch (\Exception $e) {
            // Handle error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
 
    public function useredit($id)
    {
        // Initialize GuzzleHTTP client
        $client = new Client([
            'base_uri' => 'https://firestore.googleapis.com/v1/projects/genzee-baddies-1/databases/(default)/documents/',
        ]);
    
        try {
            // Send request to fetch the user data by ID
            $response = $client->get('users/' . $id);
            $userData = json_decode($response->getBody()->getContents(), true);
    
            // Check if user data exists
            if(isset($userData['fields'])) {
                // Extract user data
                $user = $userData['fields'];
    
                // Include the user's ID in the user data
                $user['id'] = $id;
    
                // Return the edit view with user data
                return view('admin.users.edit', compact('user'));
            } else {
                // Handle case where user data is not found
                return response()->json(['error' => 'User not found'], 404);
            }
        } catch (\Exception $e) {
            // Handle error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

//     public function update(Request $request, $id)
// {
//     // Initialize GuzzleHTTP client
//     $client = new Client([
//         'base_uri' => 'https://firestore.googleapis.com/v1/projects/genzee-baddies-1/databases/(default)/documents/',
//     ]);

//     try {
//         // Convert the string value to boolean
//         $paid = filter_var($request->input('paid'), FILTER_VALIDATE_BOOLEAN);
    
//         // Prepare the user data to be updated
//         $userData = [
//             'fields' => [
//                 'name' => ['stringValue' => $request->input('name') ?? ''],
//                 'about' => ['stringValue' => $request->input('about') ?? ''],
//                 'age' => ['stringValue' => $request->input('age') ?? ''],
//                 'children' => ['stringValue' => $request->input('children') ?? ''],
//                 'city' => ['stringValue' => $request->input('city') ?? ''],
//                 'country' => ['stringValue' => $request->input('country') ?? ''],
//                 'email' => ['stringValue' => $request->input('email') ?? ''],
//                 'dob' => ['stringValue' => $request->input('dob') ?? ''],
//                 'gender' => ['stringValue' => $request->input('gender') ?? ''],
//                 'genotype' => ['stringValue' => $request->input('genotype') ?? ''],
//                 'height' => ['stringValue' => $request->input('height') ?? ''],
//                 'hideAccount' => ['stringValue' => $request->input('hideAccount') ?? ''],
//                 'lastOnline' => ['stringValue' => $request->input('lastOnline') ?? ''],
//                 'online' => ['stringValue' => $request->input('online') ?? ''],
//                 'paid' => ['booleanValue' => $paid],
//                 'phoneNumber' => ['stringValue' => $request->input('phoneNumber') ?? ''],
//                 'preference' => ['stringValue' => $request->input('preference') ?? ''],
//                 'state' => ['stringValue' => $request->input('state') ?? ''],
//                 'status' => ['stringValue' => $request->input('status') ?? ''],
//                 'university' => ['stringValue' => $request->input('university') ?? ''],
//                 'verified' => ['booleanValue' => $request->input('verified')],
//                 'weight' => ['stringValue' => $request->input('weight') ?? ''],
//             ]
//         ];
    
//         // Send request to update the user data
//         $response = $client->patch('users/' . $id, [
//             'json' => $userData,
//         ]);
    
//         // Check if the update was successful
//         if ($response->getStatusCode() === 200) {
//             // Redirect back to the user index page with success message
//             return redirect()->route('users.edit')->with('success', 'User updated successfully');
//         } else {
//             // Handle case where update was not successful
//             return back()->with('error', 'Failed to update user');
//         }
//     } catch (\Exception $e) {
//         // Handle error
//         return back()->with('error', $e->getMessage());
//     }
    
// }
//     // public function update(Request $request, $id)
    // {
    //     // Initialize GuzzleHTTP client
    //     $client = new Client([
    //         'base_uri' => 'https://firestore.googleapis.com/v1/projects/genzee-baddies-1/databases/(default)/documents/',
    //     ]);

    //     try {
    //         // Convert the string value to boolean
    //         $paid = filter_var($request->input('paid'), FILTER_VALIDATE_BOOLEAN);

    //         // Prepare the data to be updated
    //         $userData = [
    //             'name' => $request->input('name'),
    //             'email' => $request->input('email'),
    //             'about' => $request->input('about'),
    //             'age' => $request->input('age'),
    //             'children' => $request->input('children'),
    //             'city' => $request->input('city'),
    //             'country' => $request->input('country'),
    //             'dob' => $request->input('dob'),
    //             'gender' => $request->input('gender'),
    //             'genotype' => $request->input('genotype'),
    //             'height' => $request->input('height'),
    //             'hideAccount' => $request->input('hideAccount'),
    //             'lastOnline' => $request->input('lastOnline'),
    //             // Add more fields as needed
    //             'latitude' => $request->input('latitude'),
    //             'longitude' => $request->input('longitude'),
    //             'online' => $request->input('online'),
    //             'paid' => $paid, // Store as boolean
    //             'phoneNumber' => $request->input('phoneNumber'),
    //             'preference' => $request->input('preference'),
    //             'state' => $request->input('state'),
    //             'status' => $request->input('status'),
    //             'university' => $request->input('university'),
    //             'verified' => $request->input('verified'),
    //             'weight' => $request->input('weight'),
    //         ];

    //         // Send PATCH request to update user data
    //         $response = $client->patch("users/$id", [
    //             'json' => ['fields' => $userData]
    //         ]);

    //         // Check if update was successful
    //         if ($response->getStatusCode() === 200) {
    //             // Redirect back to the users index page after successful update
    //             return redirect()->route('users.index')->with('success', 'User updated successfully');
    //         } else {
    //             // Handle update failure
    //             return redirect()->back()->with('error', 'Failed to update user. Please try again.');
    //         }
    //     } catch (\Exception $e) {
    //         return back()->with('error', $e->getMessage());
    //     }
    // }
 
    public function admob()
    {
        // Initialize GuzzleHTTP client
        $client = new Client([
            'base_uri' => 'https://firestore.googleapis.com/v1/projects/genzee-baddies-1/databases/(default)/documents/',
        ]);
    
        try {
            // Send request to fetch the specific document
            $response = $client->get('admob/f5OIKRALpmXStelHjROW');
            $admobData = json_decode($response->getBody()->getContents(), true);
    
            // Check if the response contains the expected data
            if (!isset($admobData['fields'])) {
                throw new \Exception('The response does not contain the expected "fields" key.');
            }
    
            // Extract the fields from the document
            $fields = $admobData['fields'];
    
            // Prepare an array to store the details
            $admobDetails = [];
    
            // Loop through the fields and extract their values
            foreach ($fields as $fieldName => $fieldData) {
                if (isset($fieldData['stringValue'])) {
                    $admobDetails[$fieldName] = $fieldData['stringValue'];
                } else {
                    // Handle other data types as needed
                    $admobDetails[$fieldName] = ''; // Set default value for now
                }
            }
    
            // Pass the admob details to the view
            return view('admin.users.admob', [
                'admobDetails' => $admobDetails,
            ]);
        } catch (\Exception $e) {
            // Handle error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
    public function admobupdate(Request $request)
    {
        // Extract the updated data from the request
        $adid = $request->input('adid');
        $pubid = $request->input('pubid');
    
        // Initialize GuzzleHTTP client
        $client = new Client([
            'base_uri' => 'https://firestore.googleapis.com/v1/projects/genzee-baddies-1/databases/(default)/documents/',
        ]);
    
        try {
            // Send request to update the "admob" document
            $response = $client->patch('admob/f5OIKRALpmXStelHjROW', [
                'json' => [
                    'fields' => [
                        'adid' => [
                            'stringValue' => $adid
                        ],
                        'pubid' => [
                            'stringValue' => $pubid
                        ]
                    ]
                ]
            ]);
    
            // Handle success response
            return redirect()->back()->with('success', 'Admob details updated successfully.');
        } catch (\Exception $e) {
            // Handle error
            return redirect()->back()->with('error', 'Failed to update Admob details. ' . $e->getMessage());
        }
    }
    
    
   
  
































    public function index2()
    {
        try {
            // Initialize GuzzleHTTP client
            $client = new Client([
                'base_uri' => 'https://firestore.googleapis.com/v1/projects/genzee-baddies-1/databases/(default)/documents/',
            ]);
    
            // Send request to fetch complaints data
            $response = $client->get('complaints');
            $complaintsData = json_decode($response->getBody()->getContents(), true)['documents'];
    
            // Initialize an empty array to store complaint data
            $complaints = [];
    
            // Iterate through each document in the complaints collection
            foreach ($complaintsData as $document) {
                // Extract complaint data from the document
                $complaint = $document['fields'];
                // Add the complaint data to the array
                $complaints[] = $complaint;
            }
    
            // Paginate the complaint data
            $perPage = 10; // Number of items per page
            $currentPage = request()->query('page', 1); // Get the current page from the query string
            $complaintsPaginated = \Illuminate\Pagination\Paginator::resolveCurrentPage('complaintsPage');
            $complaintsPaginated = array_slice($complaints, ($currentPage - 1) * $perPage, $perPage);
            $complaintsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
                $complaintsPaginated,
                count($complaints),
                $perPage,
                $currentPage,
                ['path' => url()->current()]
            );
    
            // Pass complaint data to the view and render the view
            return view('admin.reports.index', ['complaintsPaginated' => $complaintsPaginated]);
        } catch (\Exception $e) {
            // Handle error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
 
 public function edit($id)
    {
    // Get the path to the JSON file
    $firebaseCredentialsFile = storage_path('app/genzee-baddies.json');
   
    if (!file_exists($firebaseCredentialsFile)) {
        throw new \Exception('Firebase credentials file not found');
    }

    // Load Firebase credentials from the JSON file
    $serviceAccount = json_decode(file_get_contents($firebaseCredentialsFile), true);
    // Create Firebase Factory with loaded credentials
    $factory = (new Factory)
    ->withServiceAccount($serviceAccount);
    
    $firestore = $factory->createFirestore();
    // Get Firestore database instance
    $database = $firestore->database();
    // Reference the "users" collection
    $usersCollection = $database->collection('users');
    // Get the document with the specified ID
    $userDocument = $usersCollection->document($id)->snapshot();
    // Check if the user document exists
    if (!$userDocument->exists()) {
        // User not found, handle appropriately (e.g., redirect back)
        return redirect()->back()->with('error', 'User not found');
    }
    // Get the user data
    $userData = $userDocument->data();
    
    // Pass the user data to the view and render the edit view
    return view('admin.users.edit', ['userData' => $userData]);
}

public function uepdate(Request $request, $id)
{
    // Get the path to the JSON file
    $firebaseCredentialsFile = storage_path('app/genzee-baddies.json');
   
    if (!file_exists($firebaseCredentialsFile)) {
        throw new \Exception('Firebase credentials file not found');
    }

    // Load Firebase credentials from the JSON file
    $serviceAccount = json_decode(file_get_contents($firebaseCredentialsFile), true);
    // Create Firebase Factory with loaded credentials
    $factory = (new Factory)
        ->withServiceAccount($serviceAccount);
    
    $firestore = $factory->createFirestore();
    // Get Firestore database instance
    $database = $firestore->database();
    // Reference the "users" collection
    $usersCollection = $database->collection('users');
    // Get the document with the specified ID
    $userDocument = $usersCollection->document($id);
    // Convert the string value to boolean
$paid = filter_var($request->input('paid'), FILTER_VALIDATE_BOOLEAN);
    // Update user data with the data from the form
    $userDocument->set([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'about' => $request->input('about'),
        'age' => $request->input('age'),
        'children' => $request->input('children'),
        'city' => $request->input('city'),
        'country' => $request->input('country'),
        'dob' => $request->input('dob'),
        'gender' => $request->input('gender'),
        'genotype' => $request->input('genotype'),
        'height' => $request->input('height'),
        'hideAccount' => $request->input('hideAccount'),

        'lastOnline' => $request->input('lastOnline'),
        'latitude' => $request->input('latitude'),
        'longitude' => $request->input('longitude'),
        'online' => $request->input('online'),
        'paid' => $paid, // Store as boolean
        'phoneNumber' => $request->input('phoneNumber'),
        'preference' => $request->input('preference'),
        'state' => $request->input('state'),
        'status' => $request->input('status'),
        'university' => $request->input('university'),
        'verified' => $request->input('verified'),
        'weight' => $request->input('weight'),


        
        // Add more fields as needed
    ], ['merge' => true]); // Use merge to update existing fields without overwriting
    
    // Redirect back to the users index page or wherever you want after update
    return redirect()->route('users.index')->with('success', 'User updated successfully');
}

public function destroy($id)
{
    // Get the path to the JSON file
    $firebaseCredentialsFile = storage_path('app/genzee-baddies.json');
   
    if (!file_exists($firebaseCredentialsFile)) {
        throw new \Exception('Firebase credentials file not found');
    }

    // Load Firebase credentials from the JSON file
    $serviceAccount = json_decode(file_get_contents($firebaseCredentialsFile), true);
    // Create Firebase Factory with loaded credentials
    $factory = (new Factory)
        ->withServiceAccount($serviceAccount);
    
    $firestore = $factory->createFirestore();
    // Get Firestore database instance
    $database = $firestore->database();
    // Reference the "users" collection
    $usersCollection = $database->collection('users');
    // Delete the document with the specified ID
    $usersCollection->document($id)->delete();
    
    // Redirect back to the users index page or wherever you want after deletion
    return redirect()->route('users.index')->with('success', 'User deleted successfully');
}



        public function sendCode(Request $request) {

            $code = $this->generateRandomCode();
            $userEmail = $request->input('email'); 
            
            Mail::to($userEmail)->send(new CodeEmail($code));

            return response()->json(['message' => 'Code sent successfully'], 200);
        }

        private function generateRandomCode() {
            return str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        }

        
}
