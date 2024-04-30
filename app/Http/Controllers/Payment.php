<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Payment extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Verify webhook request
        $payload = $request->getContent();
        $signature = $request->header('X-Paystack-Signature');

        if (!$this->isValidSignature($payload, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Process webhook payload
        $data = json_decode($payload, true);

        // Update database with payment details
        Payment::create([
            'transaction_id' => $data['data']['id'],
            'amount' => $data['data']['amount'] / 100, // Paystack amount is in kobo
            'status' => $data['event']
        ]);

        // Respond to Paystack
        return response()->json(['message' => 'Webhook received'], 200);
    }

    private function isValidSignature($payload, $signature)
    {
        // Implement signature verification logic here
        // Compare the computed signature with the signature provided in the request header
    }
}
