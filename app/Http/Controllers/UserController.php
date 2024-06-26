<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

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
    
                // Check if createdAt field exists and convert it to Carbon date
                if (isset($userData['createdAt'])) {
                    $userData['createdAt']['dateValue'] = Carbon::parse($userData['createdAt']['stringValue']);
                } else {
                    $userData['createdAt']['dateValue'] = Carbon::now(); // Set a default value if createdAt is missing
                }
    
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
    
            // Sort users by createdAt in descending order
            usort($users, function($a, $b) {
                return $b['createdAt']['dateValue']->timestamp - $a['createdAt']['dateValue']->timestamp;
            });
    
            // Group users by createdAt date
            $usersGrouped = collect($users)->groupBy(function($user) {
                return $user['createdAt']['dateValue']->format('Y-m-d');
            });
    
            // Flatten the grouped users for pagination
            $usersFlattened = $usersGrouped->flatten(1)->toArray();
    
            // Paginate the user data
            $perPage = 10; // Number of items per page
            $currentPage = LengthAwarePaginator::resolveCurrentPage('usersPage');
            $usersPaginated = new LengthAwarePaginator(
                array_slice($usersFlattened, ($currentPage - 1) * $perPage, $perPage),
                count($usersFlattened),
                $perPage,
                $currentPage,
                ['path' => url()->current()]
            );
    
            // Pass user data to the view
            return view('admin.users.users', [
                'usersGrouped' => $usersGrouped,
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
            $banned = isset($existingUserData['fields']['banned']) ? $existingUserData['fields']['banned']['booleanValue'] : false;
    
            // Handle array fields
            $arrayFields = ['blockedList', 'chatList', 'hobbies', 'languages', 'likes', 'matches','profileImage','profileViews'];
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
                // Handle removal of images from profileImage array
            if ($request->has('deletedImages')) {
                $deletedImages = explode(',', $request->input('deletedImages'));
                foreach ($deletedImages as $deletedImage) {
                    // Remove the deleted image from the profileImage array
                    $profileImage = array_diff($profileImage, [$deletedImage]);
                }
                
                // Reindex the profileImage array
                $profileImage = array_values($profileImage);
            }

    
            // Prepare the user data to be updated
            $userData = [
                'fields' => [
                    'profileImage' => ['arrayValue' => ['values' => array_map(function ($value) {
                        return ['stringValue' => $value];
                    }, $profileImage)]],
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
                    'profileViews' => ['arrayValue' => ['values' => array_map(function ($value) {
                        return ['stringValue' => $value];
                    }, $profileViews)]],
                    'profileImage' => ['arrayValue' => ['values' => array_map(function ($value) {
                        return ['stringValue' => $value];
                    }, $profileImage)]],
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
                    'banned' => ['booleanValue' => $banned],
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
                    'createdAt' => ['stringValue' => $request->input('createdAt') ?? $existingUserData['fields']['createdAt']['stringValue'] ?? ''],
                ]
            ];




//   dd($userData);
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
    
            // Initialize an array to hold referral information
            $referralsMap = [];
    
            // Initialize an array to hold users with their referrals
            $users = [];
    
            // Initialize total referrals count
            $totalReferrals = 0;
    
            // Build Referral Map: Iterate through the retrieved user documents
            foreach ($usersData['documents'] as $document) {
                // Extract user data from the document
                $userData = $document['fields'];
                $userId = $document['name']; // Assuming this gives the user ID
    
                // Check if the user has a referrerId
                if (isset($userData['referrerId']['stringValue']) && !empty($userData['referrerId']['stringValue'])) {
                    $referrerId = $userData['referrerId']['stringValue'];
    
                    // Get the name of the user
                    $userName = isset($userData['name']['stringValue']) ? $userData['name']['stringValue'] : 'Unknown';
    
                    // Add the current user's name to the referrer's list of referrals
                    $referralsMap[$referrerId][] = $userName;
    
                    // Increment total referrals count
                    $totalReferrals++;
                }
            }
    
            // Iterate through the users again and attach referrals to each user
            foreach ($usersData['documents'] as $document) {
                // Extract user data from the document
                $userData = $document['fields'];
                $userId = $document['name']; // Assuming this gives the user ID
    
                // Get the user's name from the Firestore document fields
                $userName = isset($userData['name']['stringValue']) ? $userData['name']['stringValue'] : 'Unknown';
    
                // Check if the user has a referralId
                if (isset($userData['referralId']['stringValue'])) {
                    $referralId = $userData['referralId']['stringValue'];
    
                    // Get referrals for this user
                    $referrals = isset($referralsMap[$referralId]) ? $referralsMap[$referralId] : [];
    
                    // Add user and their referrals to the list
                    $users[] = [
                        'user_id' => $userId,
                        'name' => $userName, // Use the extracted user's name
                        'email' => $userData['email']['stringValue'],
                        'referrals' => $referrals, // Array of referral names
                    ];
                } elseif (empty($userData['referralId']['stringValue'])) {
                    // If referralId is empty, treat it as no referrals
                    $users[] = [
                        'user_id' => $userId,
                        'name' => $userName,
                        'email' => $userData['email']['stringValue'],
                        'referrals' => [],
                    ];
                }
            }
    
            // Pass user data, total referrals count, and the GuzzleHTTP client to the view
            return view('admin.users.referals', [
                'users' => $users,
                'totalReferrals' => $totalReferrals,
            ]);
        } catch (\Exception $e) {
            // Handle error
            return response()->json(['error' => $e->getMessage()], 500);
        }
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