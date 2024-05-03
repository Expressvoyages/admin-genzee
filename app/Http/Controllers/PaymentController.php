<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function handleWebhook(Request $request)
    {
       // Retrieve the payload and signature from the request
    $payload = $request->getContent();
    $signature = $request->header('X-Paystack-Signature');

    // Process webhook payload
    $data = json_decode($payload, true);
 
    // Update database with payment details
    Payment::create([
        'transaction_id' => $data['data']['id'],
        'user_id' => $data['data']['metadata']['user_id'],
        'amount' => $data['data']['amount'] / 100, 
        'status' => $data['event']
    ]);

    // Respond to Paystack
    return response()->json(['message' => 'Webhook received'], 200);
    }
}
