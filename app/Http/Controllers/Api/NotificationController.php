<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\UserNotification;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        try {
            $notifications = UserNotification::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(10);
            $toReturn['notifications'] = $notifications;
            return $this->apiSuccessWithData('', $toReturn);
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        }
    }

    public function read($id)
    {
        try {
            DB::beginTransaction();
            $notification = UserNotification::where('id', $id)->where('user_id', Auth::id())->first();
            $notification->read = true;
            $notification->save();
            DB::commit();
            return $this->apiSuccess('notificaiton status updated...');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        }
    }
    public function readAllAnnouncement()
    {
        try {
            DB::beginTransaction();
            $notification = UserNotification::where('type', NOTIFICATION_ANNOUNCEMENT)->where('user_id', Auth::id())->first();
            $notification->read = true;
            $notification->save();
            DB::commit();
            return $this->apiSuccess('notificaiton status updated...');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        }
    }
    public function unreadAnnouncementAndNotificationCount()
    {
        try {
            $annoncementCount = UserNotification::where('type', NOTIFICATION_ANNOUNCEMENT)->where('user_id', Auth::id())->get()->count();
            $notificationCount = UserNotification::where('user_id', Auth::id())->where('read', 0)->get()->count();
            return $this->apiSuccessWithData('', ['announcementCount' => $annoncementCount, 'notificationCount' => $notificationCount]);
        } catch (QueryException $e) {
            dd($e);
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        }
    }

    public function readAllnotification()
    {
        try {
            DB::beginTransaction();
            $readNotification = DB::table('user_notifications')->where('user_id', Auth::user()->id)->where('read', '=', 0)->update(array('read' => 1));
            DB::commit();
            return $this->apiSuccess('notificaiton status updated...');
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->apiError('Something went wrong', ERROR_400);
        }
    }
}
