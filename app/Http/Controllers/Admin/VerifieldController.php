<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Payment;
use App\Models\HelpPage;
use Google\Type\DateTime;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use App\Models\DeletionRequest;
use App\Http\Controllers\Controller;
use Google\Cloud\Storage\StorageClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Http\Client\RequestException;
use GuzzleHttp\Exception\RequestException;

class VerifieldController extends Controller
{


    public function dashboard() {


        
        // Initialize GuzzleHTTP client
        $client = new Client([
            'base_uri' => 'https://firestore.googleapis.com/v1/projects/genzee-baddies-1/databases/(default)/documents/',
        ]);
    
        try {
            // Fetch complaints data
            $responseComplaints = $client->get('complaints');
            $complaintsData = json_decode($responseComplaints->getBody()->getContents(), true);
    
            $totalComplains = count($complaintsData['documents']);
    
            // Fetch stickers data
            $responseStickers = $client->get('stickers');
            $stickersData = json_decode($responseStickers->getBody()->getContents(), true);
    
            $totalStickers = count($stickersData['documents']);
    
            // Fetch users data
            $responseUsers = $client->get('users');
            $usersData = json_decode($responseUsers->getBody()->getContents(), true);
    
            $totalPhoto = 0; // Initialize total photo count
            $totalPaidUsers = 0; // Initialize total paid users count
    
            // Initialize an empty array to store the count of users for each state
            $usersByState = [];
            $usersByCity = [];

            // Iterate over each user document
            foreach ($usersData['documents'] as $user) {
                // Check if the user has a profileImage field and it's not empty
                if (isset($user['fields']['profileImage']) && !empty($user['fields']['profileImage'])) {
                    $totalPhoto++; // Increment total photo count
                }
    
                // Check if the user is paid
                if (isset($user['fields']['paid']) && $user['fields']['paid'] == true) {
                    $totalPaidUsers++; // Increment total paid users count
                }
    
                // Check if the user has a state field and it's not empty
                if (isset($user['fields']['state']) && !empty($user['fields']['state']['stringValue'])) {
                    // Extract the state value from the user document
                    $state = $user['fields']['state']['stringValue'];
    
                    // Increment the count for the state in the $usersByState array
                    if (isset($usersByState[$state])) {
                        $usersByState[$state]++;
                    } else {
                        $usersByState[$state] = 1;
                    }
                }
                if (isset($user['fields']['city']) && !empty($user['fields']['city']['stringValue'])) {
                    // Extract the city value from the user document
                    $city = $user['fields']['city']['stringValue'];
            
                    // Increment the count for the city in the $usersByCity array
                    if (isset($usersByCity[$city])) {
                        $usersByCity[$city]++;
                    } else {
                        $usersByCity[$city] = 1;
                    }
                }
            }
    
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
    
            // Total users count
            $totalUsers = count($usersData['documents']);
    
            return view('dashboard', compact('totalPhoto', 'totalStickers', 'totalUsers', 'totalComplains', 'totalPaidUsers', 'users', 'usersPaginated', 'usersByState', 'usersByCity'));
        } catch (\Exception $e) {
            // Handle error
            // You can log the error for debugging purposes
            // Log::error($e->getMessage());
            // Redirect back to the dashboard with error message
            return redirect()->route('dashboard')->with('error', 'Failed to fetch data. Please try again.');
        }
    }
    

    public function admin()
    {
        // Fetch users with specific roles (assuming roles are stored as numerical values)
        $admins = User::whereIn('user_role', [1, 2, 3 ,4])->get();

        return view('admin.admin.index', compact('admins'));
    }
  
public function adminstore(Request $request)
{
    // Validate request
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'user_role' => 'required',
    
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Create new user
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->user_role = $request->user_role;


    // Check if user saved successfully
    if ($user->save()) {
        // Redirect with success message
        return redirect()->route('admininistrators')->with('success', 'User created successfully');
    } else {
        // Handle error
        return redirect()->back()->withErrors(['message' => 'Failed to save user'])->withInput();
    }
}


    public function showDeletionForm()
    {
        return view('delete');
    }
    public function fetchImages(Request $request)
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
                return view('admin.users.images', [
                    'users' => $users, // Pass the $users variable to the view
                    'usersPaginated' => $usersPaginated,
                ]);
            } catch (\Exception $e) {
                // Handle error
                return response()->json(['error' => $e->getMessage()], 500);
            }
    
      
    }
    
    public function deleteImage($userId, $imageUrl)
    {
       // Initialize GuzzleHTTP client
    $client = new Client([
        'base_uri' => 'https://firestore.googleapis.com/v1/projects/genzee-baddies-1/databases/(default)/documents/',
    ]);

    try {
        // Send DELETE request to delete image
        $response = $client->delete("users/{$userId}/images/{$imageUrl}");

        // Check if the request was successful
        if ($response->getStatusCode() === 204) {
            // Image successfully deleted, redirect back with a success message
            return back()->with('success', 'Image deleted successfully.');
        } else {
            // Failed to delete the image, return an error message
            return back()->with('error', 'Failed to delete image.');
        }
    } catch (RequestException $e) {
        // Handle request exception
        $errorMessage = $e->getResponse()->getBody()->getContents();
        return back()->with('error', 'Failed to delete image: ' . $errorMessage);
    }
    }
    

    
    
    
    
    public function adminsedit($id)
    {
        // Fetch the admin data from the database
        $admin = User::find($id);
        
        // Check if $admin exists
        if (!$admin) {
            // If $admin is null, return an error message or redirect to an error page
            return redirect()->route('admininistrators')->with('error', 'Admin not found.');
        }
    
        // If $admin exists, pass it to the view
        return view('admin.admin.edit', compact('admin'));
    }
    

    public function admiupdate(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'user_role' => 'required|in:1,2,3,4',
        ]);
    
        // Fetch the admin data from the database
        $admin = User::find($id);
    
        // Check if $admin exists
        if (!$admin) {
            // If $admin is null, return an error message or redirect to an error page
            return redirect()->route('admininistrators')->with('error', 'Admin not found.');
        }
    
        // Update the admin data with the validated request data
        $admin->name = $validatedData['name'];
        $admin->email = $validatedData['email'];
        $admin->user_role = $validatedData['user_role'];
        $admin->save();
    
        // Redirect back to the admin edit page with a success message
        return redirect()->route('admininistrators')->with('success', 'Admin updated successfully.');
    }
    
    public function admindestroy($id)
    {
        // Find the user by ID
        $user = User::find($id);
    
        // Check if $user exists
        if (!$user) {
            // If $user is null, return an error message or redirect to an error page
            return redirect()->route('admininistrators')->with('error', 'User not found.');
        }
    
        // Delete the user
        $user->delete();
    
        // Redirect back to the administrators page with a success message
        return redirect()->route('admininistrators')->with('success', 'User deleted successfully.');
    }
   
    public function role()
    {
        // Fetch users with specific roles (assuming roles are stored as numerical values)
        $admins = User::whereIn('user_role', [1, 2, 3 ,4])->get();

        return view('admin.admin.role', compact('admins'));
    }


    public function push () {
        return view('admin.admin.push');
    }

    public function help () {
        $helpData = HelpPage::all();
        return view('admin.admin.help', compact('helpData'));
    }
    public function payment () {
        $payments = Payment::latest()->paginate(10);
        return view('admin.admin.payment', compact('payments'));
    }
    

    public function sendNotification(Request $request)
    {
        // Load Firebase API key
        $firebaseApiKey = "AIzaSyCCaXd0EQ3fSCpDknQZkY0xn_gKSlejRGg"; // Replace with your actual API key from google-services.json
    
        // Prepare message data
        $message = [
            'notification' => [
                'title' => $request->input('title'),
                'body' => $request->input('body')
            ],
            'topic' => 'global'
        ];
    
        // Create GuzzleHTTP client
        $client = new Client([
            'base_uri' => 'https://fcm.googleapis.com/',
            'headers' => [
                'Authorization' => 'key=' . $firebaseApiKey,
                'Content-Type' => 'application/json',
            ]
        ]);
    
        try {
            // Send the request
            $response = $client->post('fcm/send', [
                'json' => ['message' => $message]
            ]);
    
            // Check if the request was successful
            if ($response->getStatusCode() === 200) {
                return response()->json(['message' => 'Push notification sent successfully']);
            } else {
                // Handle error
                return response()->json(['error' => 'Failed to send push notification'], $response->getStatusCode());
            }
        } catch (RequestException $e) {
            // Handle request exceptions
            return response()->json(['error' => 'Request Exception: ' . $e->getMessage()], $e->getCode());
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['error' => 'Exception: ' . $e->getMessage()]);
        }
    }
        



    
    public function home() {
        return view('welcome');
    }
    public function about() {
        return view('about');
    }

    public function price() {
        return view('pricing');
    }
    public function index()
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
        
        // Query users where paid is true
        $query = $usersCollection->where('paid', '=', true)->documents();
        
        // Initialize an empty array to store user data
        $usersData = [];
        // Iterate over the documents and store the data in the array
        foreach ($query as $document) {
            // Get the document data
            $userData = $document->data();
            // Append the user data to the array
            $usersData[] = $userData;
        }

        // Pass user data to the view and render the view
        return view('admin.verifield.index', ['usersData' => $usersData]);
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
        return view('admin.verifield.edit', ['userData' => $userData]);
    }

    public function update(Request $request, $id)
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
        
        // Update user data with the data from the request
        $userDocument->set([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            // Add more fields as needed
        ], ['merge' => true]); // Use merge to update existing fields without overwriting
        
        // Redirect back to the users index page or wherever you want after update
        return redirect()->route('users.verify')->with('success', 'User updated successfully');
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
        return redirect()->route('users.verify')->with('success', 'User deleted successfully');
    }
}
