<?php

namespace App\Http\Controllers;

use App\UserNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function successView($msg, $view, $page_title, $data = NULL)
    {
        if (!empty($msg)) {
            Session::flash('success', $msg);
        }
        return view("partials.base")->with('partials', $view)->with('page_title', $page_title)->with('data', $data);
    }

    public function successListView($msg, $view, $page_title, $data = NULL, $search = true, $add = true)
    {
        if (!empty($msg)) {
            Session::flash('success', $msg);
        }
        return view("partials.list-base")->with('partials', $view)->with('page_title', $page_title)->with('data', $data)->with('search', $search)->with('add', $add);
    }

    public function redirectBack($type, $msg, $url = NULL)
    {
        Session::flash($type, $msg);
        if (!empty($url)) {
            return redirect($url);
        }

        return Redirect()->back();
    }

    public function uploadFile($folderName, $requestParam)
    {
        $nameImg = time() . '.' . request($requestParam)->getClientOriginalExtension();
        request($requestParam)->move(public_path('uploads/' . $folderName), $nameImg);
        return $nameImg;
    }
    public function uploadFiles($parentFolder, $file, $fileName)
    {

        $folderName = 'uploads/' . $parentFolder;
        // // make file path structure
        $filePath = $folderName . '/' . date('Y') . '/' . date('m') . '/';
        // //renaming the file
        $name =  $fileName . '_' . time() . '_' . rand(5000, 100000) . "." . $file->getClientOriginalExtension();
        $folderPath = public_path('/') . $filePath;

        // !$file->move($folderPath, $name);
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        if (!$file->move($folderPath, $name)) {
            return false;
        }
        return $filePath . $name;
    }

    /**
     * Description: The following method is used to add filter into query
     * @author Muhammad Abid - I2L
     * @param $query
     * @param $filters = Array
     * @param $with = table name
     * @return bool
     */
    protected function search($query, $keyword, $filters, $with = null)
    {

        if ($with) {

            $query->orWhereHas($with, function ($q) use ($filters, $keyword) {

                foreach ($filters as $key => $column) {
                    if ($key == 0) {
                        $q->where($column, 'LIKE', '%' . $keyword . '%');
                    } else {
                        $q->orWhere($column, 'LIKE', '%' . $keyword . '%');
                    }
                }
            });
        } else {

            foreach ($filters as $key => $column) {
                $query->orWhere($column, 'LIKE', '%' . $keyword . '%');
            }
        }


        return $query;
    }

    /*
    |
    |   API RESPONSES
    |
    */
    public function apiError($message, $code)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }

    public function apiSuccess($message)
    {
        return response()->json([
            'success' => true,
            'message' => $message
        ], 200);
    }

    public function apiSuccessWithData($message, $data)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    public function deleteFile($file)
    {
        if (env('AWS_ENV')) {
            if (Storage::delete($file)) {
                return true;
            } else return false;
        } else {
            if (file_exists(public_path($file))) {
                if (unlink(public_path($file))) {
                    return true;
                } else return false;
            } else return true;
        }
    }

    public function NotificationStatus(Request $request)
    {
        $notification = UserNotification::find($request->id);
        $notification->read = true;
        $notification->save();
        return response()->json(['notificaiton status updated...'], 200);
    }
    public function getNotifications(Request $request)
    {
        $notifications = UserNotification::where('user_id', $request->id)->orderBy('created_at', 'desc')->limit(5)->get();
        $html = "";
        foreach ($notifications as $key => $value) {
            // $html = $html . '<li style=" ' .  ? 'background: #ddd' : "" . '">
            $notificationUrl = '';
            if ($value->type == NOTIFICATION_LOAN) {
                $notificationUrl = route('employee.loans.detail', $value->detail);
            } else if ($value->type == NOTIFICATION_LEAVE) {
                $notificationUrl = route('employee.leaves.details', $value->detail);
            } else if ($value->type == NOTIFICATION_DEVICE) {
                $notificationUrl = route('employee.inventories.listing');
            } else if ($value->type == NOTIFICATION_ANNOUNCEMENT) {
                $notificationUrl = route('employee.announcements.listing');
            }
            $placeholder = strlen($value->message) > 30 ? "..." : '';
            $html = $html . '<li class="notification-list" style="' . (($value->read == 1) ? "background: #ddd" : "") . '">
                                <span class="readStatus" style="' . (($value->read == 1) ? "background: transparent" : "") . '" id="status"></span>
                                <div class="timeline-panel">
                                    <a href="' . $notificationUrl . '" data-id="' . $value->id . '" class="notification-click">
                                        <div class="media-body notification">
                                            <h5 class="mb-1">' . $value->title . '</h5>
                                            <h6 class="mb-1">' . substr($value->message, 0, 30) . $placeholder .  '</h6>
                                            <small class="d-block">' . $value->created_at->diffForHumans() . '</small>
                                            </div>
                                            </a>
                                            </div>
                                            </li>';
            // <small class="d-block">' . \Carbon\Carbon::parse($value->created_at)->format('d M Y - h:m a') . '</small>
        }
        // $html = $html . '<li><a class="all-notification" href="#">See all notifications <i class="ti-arrow-right"></i></a></li>';
        // dd(count($notifications));
        return response()->json(['html' => $html], 200);
    }

    public function getAllNotificationList(Request $request)
    {
        $notifications = UserNotification::where('user_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(5);
        $html = '';
        if ($request->ajax()) {

            foreach ($notifications as $notification) {
                $notificationUrl = '';
                if ($notification->type == NOTIFICATION_LOAN) {
                    $notificationUrl = route('employee.loans.detail', $notification->detail);
                } else if ($notification->type == NOTIFICATION_LEAVE) {
                    $notificationUrl = route('employee.leaves.details', $notification->detail);
                } else if ($notification->type == NOTIFICATION_DEVICE) {
                    $notificationUrl = route('employee.inventories.listing');
                } else if ($notification->type == NOTIFICATION_ANNOUNCEMENT) {
                    $notificationUrl = route('employee.announcements.listing');
                }
                $html .= '
                <a href="' . $notificationUrl . '" class="notification-link" data-notif-id="' . $notification->id . '">
                    <div class="row ">
                        <div class="col-sm-12">
                            <div class="card notification-row">
                                ' . (($notification->read != 1) ? '<span></span>' : '&nbsp;') . '
                                <div class="card-body">
                                    <div class="post-details">
                                        <h3 class="mb-2">' . $notification->title . '</h3>
                                        <ul class="mb-4 post-meta">
                                        <li class="post-date"><i class="fa fa-calender"></i>' . $notification->created_at->diffForHumans() . '</li>
                                    </div>
                                        <div class="row mt-2 ml-1">
                                            ' . $notification->message . '
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>';
            }
            return $html;
        }
        return view('employees.notifications', compact('notifications'));
    }

    public function readNotification()
    {
        try {
            DB::beginTransaction();
            $readNotification = DB::table('user_notifications')->where('user_id', Auth::user()->id)->where('read', '=', 0)->update(array('read' => 1));
            DB::commit();
            return redirect()->back()->with('success', "All Notifications Marked as read");
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e);
        }
    }



    public static function paginate(Collection $results, $showPerPage)
    {
        $pageNumber = Paginator::resolveCurrentPage('page');

        $totalPageNumber = $results->count();

        return self::paginator($results->forPage($pageNumber, $showPerPage), $totalPageNumber, $showPerPage, $pageNumber, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @param  int  $total
     * @param  int  $perPage
     * @param  int  $currentPage
     * @param  array  $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected static function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items',
            'total',
            'perPage',
            'currentPage',
            'options'
        ));
    }
}
