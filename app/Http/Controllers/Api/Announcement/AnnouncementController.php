<?php

namespace App\Http\Controllers\Api\Announcement;

use App\Announcement;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    private $model, $user;
    public function __construct()
    {
        $this->model = new Announcement();
        $this->user = new User();
        $this->defaultRedirectPath = URL_ADMIN . "announcements/";
        $this->defaultViewPath = "admin.announcements.";
    }
    /**
     * List all announcemnts
     */
    public function listing(Request $request)
    {
        $inputs = $request->all();
        $query = $this->model->newQuery()->with('updatedBy');
        if (!empty($inputs['search'])) {
            $query->where(function ($q) use ($inputs) {
                $this->search($q, $inputs['search'], ['title', 'description']);
            });
        }
        $announcements = $query->orderBy('created_at', 'DESC')->paginate(5);
        return $this->apiSuccessWithData('', $announcements);
    }
}
