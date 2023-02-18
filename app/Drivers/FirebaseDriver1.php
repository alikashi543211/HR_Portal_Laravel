<?php

namespace App\Drivers;

use App\RolePermission;
use App\User;
use App\UserNotification;
use Illuminate\Http\Request;
use Mpdf\Tag\Details;
use Kreait\Firebase\Contract\Messaging;


class FirebaseDriver1
{
    public $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    public function sendNotificationToUser($to, $id = null)
    {
        $firebaseToken =  $this->getIds($to, $id);
        foreach ($firebaseToken as $token) {
            $data = [
                "to" => $token->device_token,
                "data" => [
                    "title" => $this->title,
                    "body" =>  $this->body,
                    "content_available" => true,
                    "priority" => "high",
                ]
            ];
            $this->callNotificationApi($data);

            UserNotification::create([
                'title' => $this->title,
                'message' => $this->body,
                'read' => false,
                'user_id' => $token->id,
                'type' => $this->type,
                'detail' => $this->detail
            ]);
        }
    }

    // public function sendNotificationToAll($to)
    // {
    //     $firebaseToken =  $this->getPermissionIds($to);

    //     $this->callNotificationApi($data);
    // }

    public function callNotificationApi($data)
    {
        $data = json_encode($data);

        $headers = [
            'Authorization: key=' . $this->SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        return response()->json($response, 200);
    }

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
