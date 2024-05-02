<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class FirebaseNotification extends Notification
{
    protected $fcmToken;
    protected $payload;

    public function __construct($fcmToken, $payload)
    {
        $this->fcmToken = $fcmToken;
        $this->payload = $payload;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->payload['title'],
            'body' => $this->payload['body'],
        ];
    }

    public function toFcm()
    {
        return [
            'to' => $this->fcmToken,
            'data' => $this->payload,
        ];
    }

    public function send($notifiable)
    {
        $response = Http::withHeaders([
            'Authorization' => 'key=' . config('app.fcm_server_key'),
            'Content-Type' => 'application/json',
        ])->post(config('app.fcm_send_url'), $this->toFcm());

        // Handle the response
        return $response->json();
    }
}