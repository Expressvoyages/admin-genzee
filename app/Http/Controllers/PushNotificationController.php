<?php

namespace App\Http\Controllers;

use App\Models\cr;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\PushNotification;


class PushNotificationController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $push_notifications = PushNotification::orderBy('created_at', 'desc')->get();
        return view('admin.admin.push', compact('push_notifications'));
    }
    public function bulksend( Request $request) {

       // Initialize GuzzleHTTP client
    $client = new Client([
        'base_uri' => 'https://firestore.googleapis.com/v1/projects/genzee-baddies-1/databases/(default)/documents/',
    ]);
// Retrieve FCM tokens from Firestore
try {
    $response = $client->get('fcmtoken');
    $data = json_decode($response->getBody()->getContents(), true);

    // Extract FCM tokens from the response
    $tokens = [];
    foreach ($data['documents'] as $document) {
        $fields = $document['fields'];
        if (isset($fields['fcmToken']['stringValue'])) {
            $tokens[] = $fields['fcmToken']['stringValue'];
        }
    }

//     // Debugging: Check if tokens are properly retrieved
// var_dump($tokens); // or print_r($tokens);

    // Check if tokens array is empty
    if (empty($tokens)) {
        echo 'No FCM tokens found.';
        return;
    }
} catch (\Exception $e) {
    // Handle error
    echo 'Failed to retrieve FCM tokens: ' . $e->getMessage();
    return;
}

        // Send Push Notifications
        $apiKey = 'AAAAvrkG4f4:APA91bFsBgQJRUQJze8tYZNEFDEHiE8b8v3LBPLPapeSl3RxHuEeOticUS3U4ICVcMELcwb1mnqSUs2MokYAMvl4VbQRTtm_JfEJhIj59uRbLALpYAcEFMyC-WKxjVmH0fHFleySwLIF';
        $url = 'https://fcm.googleapis.com/fcm/send';
    
        $headers = [
            'Authorization' => 'key=' . $apiKey,
            'Content-Type' => 'application/json',
        ];
    
        $notification = [
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'image' => $request->input('img'),
        ];
    
        $notificationData = [
            'notification' => $notification,
            'registration_ids' => $tokens, // Array of FCM tokens
            'time_to_live' => 604800,
        ];
    
        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $notificationData,
            ]);
    // dd($response);
                    // Extract success and failure counts from the response
            $responseData = json_decode($response->getBody(), true);
            $successCount = isset($responseData['success']) ? $responseData['success'] : 0;
            $failureCount = isset($responseData['failure']) ? $responseData['failure'] : 0;

            // Store success and failure counts in session
            session()->flash('successCount', $successCount);
            session()->flash('failureCount', $failureCount);

            // Redirect back
            return redirect()->route('admins.push');
        } catch (\Exception $e) {
            // Handle error
            echo 'Failed to send notifications: ' . $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('notification.create');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PushNotification  $pushNotification
     * @return \Illuminate\Http\Response
     */
    // public function destroy(PushNotification $pushNotification)
    // {
    //     //
    // }

}
