<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Payment;
use App\Models\HelpPage;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Client\RequestException;


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
    
            return view('dashboard', compact('totalPhoto', 'totalStickers', 'totalUsers', 'totalComplains', 'totalPaidUsers','users','usersPaginated'));
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
        'description' => 'required',
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
    $user->description = $request->description;

    // Check if user saved successfully
    if ($user->save()) {
        // Redirect with success message
        return redirect()->route('admins.index')->with('success', 'User created successfully');
    } else {
        // Handle error
        return redirect()->back()->withErrors(['message' => 'Failed to save user'])->withInput();
    }
}

    public function adminsedit(User $admin)
    {
        return view('admin.admin.edit', compact('admin'));
    }

    public function admiupdate(Request $request, User $admin)
    {
        // Validate request
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'user_role' => 'required',
            'description' => 'required',
        ]);

        // Update admin
        $admin->update($request->all());

        // Redirect to admin index page
        return redirect()->route('admins.index');
    }

    public function admindestroy(User $admin)
    {
        // Delete admin
        $admin->delete();

        // Redirect back
        return redirect()->back()->with('success', 'Admin deleted successfully.');
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
