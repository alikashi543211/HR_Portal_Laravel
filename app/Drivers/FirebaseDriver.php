<?php

namespace App\Drivers;

use App\RolePermission;
use App\User;
use App\UserNotification;
use Illuminate\Http\Request;
use Mpdf\Tag\Details;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseDriver
{
    private $SERVER_API_KEY;
    private $title;
    private $body;
    private $type;
    private $detail;
    private $cloudMessaging;



    public function __construct()
    {
        // $this->SERVER_API_KEY = env('FIREBASE_API_SECRET');
        $factory = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
        $this->cloudMessaging = $factory->createMessaging();
    }

    public function setMessageBody($title, $body, $type, $detail = null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->type = $type;
        $this->detail = $detail;
    }


    public function sendNotificationToUser($to, $id = null)
    {
        $firebaseToken =  $this->getIds($to, $id);
        foreach ($firebaseToken as $token) {
            $data = [
                "title" => $this->title,
                "body" =>  $this->body,
            ];

            UserNotification::create([
                'title' => $this->title,
                'message' => $this->body,
                'read' => false,
                'user_id' => $token->id,
                'type' => $this->type,
                'detail' => $this->detail
            ]);

            $message = CloudMessage::withTarget('token', $token->device_token)
                ->withNotification(['Title', 'Body'])
                ->withData($data);
            $this->cloudMessaging->send($message);
        }
    }

    // public function sendNotificationToAll($to)
    // {
    //     $firebaseToken =  $this->getPermissionIds($to);

    //     $this->callNotificationApi($data);
    // }

    // public function callNotificationApi($data)
    // {
    //     $data = json_encode($data);

    //     $headers = [
    //         'Authorization: key=' . $this->SERVER_API_KEY,
    //         'Content-Type: application/json',
    //     ];

    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    //     $response = curl_exec($ch);
    //     return response()->json($response, 200);
    // }

    public function getIds($to, $id = null)
    {
        $userIds = null;
        if ($to == "Employees") {
            if ($id != null) {
                $userIds = User::where('id', $id)->where('status', '!=', USER_STATUS_TERMINATE)->get();
            } else {
                $userIds = User::where('status', '!=', USER_STATUS_TERMINATE)->get();
            }
        }
        if ($to == "Admins") {
            $userIds = User::whereIn('role_id', [ADMIN, SUPER_ADMIN, HUMAN_RESOURCE, MANAGER, ACCOUNTANT])->get();
        }
        return $userIds;
    }
}
