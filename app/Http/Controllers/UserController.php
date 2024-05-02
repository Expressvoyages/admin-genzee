<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class UserController extends Controller
{
   
    public function index(Request $request)
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
    
            // Filter users based on search query
            $search = $request->input('search');
            if ($search) {
                $users = array_filter($users, function ($user) use ($search) {
                    return stripos($user['name']['stringValue'], $search) !== false ||
                           stripos($user['email']['stringValue'], $search) !== false;
                });
            }
    
            // Paginate the user data
            $perPage = 10; // Number of items per page
            $currentPage = $request->query('page', 1); // Get the current page from the query string
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
                'users' => $users, // Pass the $users variable to the view
                'usersPaginated' => $usersPaginated,
            ]);
        } catch (\Exception $e) {
            // Handle error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function edit($id)
    {
        // Initialize GuzzleHTTP client
        $client = new Client([
            'base_uri' => 'https://firestore.googleapis.com/v1/projects/genzee-baddies-1/databases/(default)/documents/',
        ]);
    
        try {
            // Send request to fetch user data by ID
            $response = $client->get("users/$id");
            $userData = json_decode($response->getBody()->getContents(), true);
    
            if(isset($userData['fields'])) {
                // Extract user data from the document
                $userData = $userData['fields'];
                
                // Pass user data to the view for editing
                return view('admin.users.edit', ['user' => $userData]);
            } else {
                // User not found
                return response()->json(['error' => 'User not found'], 404);
            }
        } catch (\Exception $e) {
            // Handle error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }    
    public function update(Request $request, $id)
    {
        // Initialize GuzzleHTTP client
        $client = new Client([
            'base_uri' => 'https://firestore.googleapis.com/v1/projects/genzee-baddies-1/databases/(default)/documents/',
        ]);
    
        try {
            // Fetch existing user data from Firebase
            $response = $client->get('users/' . $id);
            $existingUserData = json_decode($response->getBody()->getContents(), true);
    
            // Convert the string value to boolean
            $paid = $request->has('paid') ? filter_var($request->input('paid'), FILTER_VALIDATE_BOOLEAN) : (isset($existingUserData['fields']['paid']) ? $existingUserData['fields']['paid']['booleanValue'] : false);
            $verified = isset($existingUserData['fields']['verified']) ? $existingUserData['fields']['verified']['booleanValue'] : false;
            $hideAccount = isset($existingUserData['fields']['hideAccount']) ? $existingUserData['fields']['hideAccount']['booleanValue'] : false;
            $notificationLikes = isset($existingUserData['fields']['notificationLikes']) ? $existingUserData['fields']['notificationLikes']['booleanValue'] : false;
            $notificationMatches = isset($existingUserData['fields']['notificationMatches']) ? $existingUserData['fields']['notificationMatches']['booleanValue'] : false;
            $notificationViews = isset($existingUserData['fields']['notificationViews']) ? $existingUserData['fields']['notificationViews']['booleanValue'] : false;
            $online = isset($existingUserData['fields']['online']) ? $existingUserData['fields']['online']['booleanValue'] : false;
            


            // Handle array fields
            $arrayFields = ['blockedList', 'chatList', 'hobbies', 'languages', 'likes', 'matches', 'profileImage', 'profileViews'];
            foreach ($arrayFields as $field) {
                if (isset($existingUserData['fields'][$field]['arrayValue']['values'])) {
                    // If the field exists in the Firebase data, use its value
                    ${$field} = array_map(function ($value) {
                        return $value['stringValue'];
                    }, $existingUserData['fields'][$field]['arrayValue']['values']);
                } else {
                    // If the field does not exist in the Firebase data, initialize as an empty array
                    ${$field} = [];
                }
            }


        // Handle lastOnline timestamp
        $lastOnline = isset($existingUserData['fields']['lastOnline']['timestampValue']) ? $existingUserData['fields']['lastOnline']['timestampValue'] : null;

                    
            // Handle longitude as a number
            $longitude = isset($existingUserData['fields']['longitude']['doubleValue']) ? $existingUserData['fields']['longitude']['doubleValue'] : null;
            $latitude = isset($existingUserData['fields']['latitude']['doubleValue']) ? $existingUserData['fields']['latitude']['doubleValue'] : null;
            

            // Prepare the user data to be updated
            $userData = [
                'fields' => [
                   
                    'blockedList' => ['arrayValue' => ['values' => array_map(function ($value) {
                        return ['stringValue' => $value];
                    }, $blockedList)]],
                    'chatList' => ['arrayValue' => ['values' => array_map(function ($value) {
                        return ['stringValue' => $value];
                    }, $chatList)]],
                    'hobbies' => ['arrayValue' => ['values' => array_map(function ($value) {
                        return ['stringValue' => $value];
                    }, $hobbies)]],
                    'languages' => ['arrayValue' => ['values' => array_map(function ($value) {
                        return ['stringValue' => $value];
                    }, $languages)]],
                    'likes' => ['arrayValue' => ['values' => array_map(function ($value) {
                        return ['stringValue' => $value];
                    }, $likes)]],
                    'matches' => ['arrayValue' => ['values' => array_map(function ($value) {
                        return ['stringValue' => $value];
                    }, $matches)]],
                    'profileImage' => ['arrayValue' => ['values' => array_map(function ($value) {
                        return ['stringValue' => $value];
                    }, $profileImage)]],
                    'profileViews' => ['arrayValue' => ['values' => array_map(function ($value) {
                        return ['stringValue' => $value];
                    }, $profileViews)]],

                    'lastOnline' => ['timestampValue' => $lastOnline],

                    'longitude' => ['doubleValue' => $longitude],
                    'latitude' => ['doubleValue' => $latitude],

                    'about' => ['stringValue' => $request->input('about') ?? $existingUserData['fields']['about']['stringValue'] ?? ''],
                 
                    
                    'age' => ['stringValue' => $request->input('age') ?? $existingUserData['fields']['age']['stringValue'] ?? ''],
                    'alcohol' => ['stringValue' => $request->input('alcohol') ?? $existingUserData['fields']['alcohol']['stringValue'] ?? ''],
                    'bodyType' => ['stringValue' => $request->input('bodyType') ?? $existingUserData['fields']['bodyType']['stringValue'] ?? ''],
                    'city' => ['stringValue' => $request->input('city') ?? $existingUserData['fields']['city']['stringValue'] ?? ''],
                    'children' => ['stringValue' => $request->input('children') ?? $existingUserData['fields']['children']['stringValue'] ?? ''],
                    'country' => ['stringValue' => $request->input('country') ?? $existingUserData['fields']['country']['stringValue'] ?? ''],
                    'email' => ['stringValue' => $request->input('email') ?? $existingUserData['fields']['email']['stringValue'] ?? ''],
                    'dob' => ['stringValue' => $request->input('dob') ?? $existingUserData['fields']['dob']['stringValue'] ?? ''],
                    'gender' => ['stringValue' => $request->input('gender') ?? $existingUserData['fields']['gender']['stringValue'] ?? ''],
                    'genotype' => ['stringValue' => $request->input('genotype') ?? $existingUserData['fields']['genotype']['stringValue'] ?? ''],
                    'height' => ['stringValue' => $request->input('height') ?? $existingUserData['fields']['height']['stringValue'] ?? ''],
                    'name' => ['stringValue' => $request->input('name') ?? $existingUserData['fields']['name']['stringValue'] ?? ''],
                    'paid' => ['booleanValue' => $paid],
                    'online' => ['booleanValue' => $online],
                    'notificationLikes' => ['booleanValue' => $notificationLikes],
                    'notificationMatches' => ['booleanValue' => $notificationMatches],
                    'notificationViews' => ['booleanValue' => $notificationViews],
                    'hideAccount' => ['booleanValue' => $hideAccount],
                    'partnerType' => ['stringValue' => $request->input('partnerType') ?? $existingUserData['fields']['partnerType']['stringValue'] ?? ''],
                    'phoneNumber' => ['stringValue' => $request->input('phoneNumber') ?? $existingUserData['fields']['phoneNumber']['stringValue'] ?? ''],
                    'preference' => ['stringValue' => $request->input('preference') ?? $existingUserData['fields']['preference']['stringValue'] ?? ''],
                    'state' => ['stringValue' => $request->input('state') ?? $existingUserData['fields']['state']['stringValue'] ?? ''],
                    'smoking' => ['stringValue' => $request->input('smoking') ?? $existingUserData['fields']['smoking']['stringValue'] ?? ''],
                    'uid' => ['stringValue' => $request->input('uid') ?? $existingUserData['fields']['uid']['stringValue'] ?? ''],
                    'referralId' => ['stringValue' => $request->input('referralId') ?? $existingUserData['fields']['referralId']['stringValue'] ?? ''],
                    'referrerId' => ['stringValue' => $request->input('referrerId') ?? $existingUserData['fields']['referrerId']['stringValue'] ?? ''],
                    'verified' => ['booleanValue' => $verified],
                    'weight' => ['stringValue' => $request->input('weight') ?? $existingUserData['fields']['weight']['stringValue'] ?? ''],
                    'longitude' => ['stringValue' => $request->input('longitude') ?? $existingUserData['fields']['longitude']['stringValue'] ?? ''],
                ]
            ];
    
            // Send request to update the user data
            $response = $client->patch('users/' . $id, [
                'json' => $userData,
            ]);
    
            // Check if the update was successful
            if ($response->getStatusCode() === 200) {
                // Redirect back to the user index page with success message
                return redirect()->route('users.index')->with('success', 'User updated successfully');
            } else {
                // Handle case where update was not successful
                return back()->with('error', 'Failed to update user');
            }
        } catch (\Exception $e) {
            // Handle error
            return back()->with('error', $e->getMessage());
        }
    }
    public function referals(Request $request)
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
    
                // Check if the user has a referrerId
                if (isset($userData['referralId']['stringValue'])) {
                    // Fetch referrals for this user
                    $referrals = $this->fetchReferralsForUser($userData['referrerId']['stringValue'], $client);

                    // Add user and their referrals to the list
                    $users[] = [
                        'name' => $userData['name']['stringValue'],
                        'email' => $userData['email']['stringValue'],
                        'referrals' => $referrals,
                    ];
                }
            }
    
            // Pass user data to the view
            return view('admin.users.referals', [
                'users' => $users,
            ]);
        } catch (\Exception $e) {
            // Handle error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    // Function to fetch referrals for a user
    private function fetchReferralsForUser($referrerId, $client)
    {
        // Initialize an empty array to store referrals
        $referrals = [];
    
        try {
            // Send request to fetch referrals data for this referrerId
            // Assuming your Firestore structure allows querying referrals by referrerId
            // Adjust the Firestore query based on your actual database structure
            $response = $client->get('users?where=referrerId==' . $referrerId);
            $referralsData = json_decode($response->getBody()->getContents(), true);
    
            // Iterate through each document in the referrals collection
            foreach ($referralsData['documents'] as $document) {
                // Extract referral data from the document
                $referralData = $document['fields'];
    
                // Add referral data to the array
                $referrals[] = [
                    'name' => $referralData['name']['stringValue'],
                    'email' => $referralData['email']['stringValue'],
                ];
            }
        } catch (\Exception $e) {
            // Log the error or handle it as required
            // Returning an empty array in case of error
            return [];
        }
    
        return $referrals;
    }
    
    
    
    
    
 public function destroy($Id)
{
    // Initialize GuzzleHTTP client
    $client = new Client([
        'base_uri' => 'https://firestore.googleapis.com/v1/projects/genzee-baddies-1/databases/(default)/documents/',
    ]);

    try {
        // Send DELETE request to delete user
        $response = $client->delete("users/{$Id}");

        // Check if the request was successful
        if ($response->getStatusCode() === 200) {
            return redirect()->route('users.index')->with('success', 'User deleted successfully');
        } else {
            // Handle other status codes if needed
            return redirect()->route('users.index')->with('error', 'Failed to delete user');
        }
    } catch (\Exception $e) {
        // Handle error
        return redirect()->route('users.index')->with('error', $e->getMessage());
    }
}

}