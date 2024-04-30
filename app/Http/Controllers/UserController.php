<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class UserController extends Controller
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
//     // Initialize GuzzleHTTP client
    $client = new Client([
        'base_uri' => 'https://firestore.googleapis.com/v1/projects/genzee-baddies-1/databases/(default)/documents/',
    ]);

    try {
//         // Convert the string value to boolean
       $paid = filter_var($request->input('paid'), FILTER_VALIDATE_BOOLEAN);
       $hideAccount = filter_var($request->input('hideAccount'), FILTER_VALIDATE_BOOLEAN);
       $online = filter_var($request->input('online'), FILTER_VALIDATE_BOOLEAN);
       $verified = filter_var($request->input('verified'), FILTER_VALIDATE_BOOLEAN);
       
        // Prepare the user data to be updated
        $userData = [
           'fields' => [
               'name' => ['stringValue' => $request->input('name') ?? ''],
              'about' => ['stringValue' => $request->input('about') ?? ''],
            'age' => ['stringValue' => $request->input('age') ?? ''],
                'children' => ['stringValue' => $request->input('children') ?? ''],
             'city' => ['stringValue' => $request->input('city') ?? ''],
             'country' => ['stringValue' => $request->input('country') ?? ''],
               'email' => ['stringValue' => $request->input('email') ?? ''],
                 'dob' => ['stringValue' => $request->input('dob') ?? ''],
                 'gender' => ['stringValue' => $request->input('gender') ?? ''],
                 'genotype' => ['stringValue' => $request->input('genotype') ?? ''],
                 'height' => ['stringValue' => $request->input('height') ?? ''],
                 'hideAccount' => ['booleanValue' => $hideAccount],
                 'online' =>  ['booleanValue' => $online],
                 'paid' => ['booleanValue' => $paid],
                 'phoneNumber' => ['stringValue' => $request->input('phoneNumber') ?? ''],
                 'preference' => ['stringValue' => $request->input('preference') ?? ''],
                 'state' => ['stringValue' => $request->input('state') ?? ''],
                 'status' => ['stringValue' => $request->input('status') ?? ''],
                 'university' => ['stringValue' => $request->input('university') ?? ''],
                 'uid' => ['stringValue' => $request->input('uid') ?? ''],
                 'verified' => ['booleanValue' => $verified],
                 'weight' => ['stringValue' => $request->input('weight') ?? ''],
             ]
         ];
    
//         // Send request to update the user data
         $response = $client->patch('users/' . $id, [
             'json' => $userData,
         ]);
    
//         // Check if the update was successful
         if ($response->getStatusCode() === 200) {
//             // Redirect back to the user index page with success message
             return redirect()->route('users.index')->with('success', 'User updated successfully');
         } else {
//             // Handle case where update was not successful
             return back()->with('error', 'Failed to update user');
         }
     } catch (\Exception $e) {
//         // Handle error
         return back()->with('error', $e->getMessage());
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