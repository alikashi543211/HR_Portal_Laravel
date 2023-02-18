<?php

namespace App\Http\Controllers\Employee\Announcement;

use App\Announcement;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    private $model, $user;
    /*
    |
    |   Controller Instance
    |
    */
    public function __construct()
    {
        $this->model = new Announcement();
        $this->user = new User();
    }

    public function listing(Request $request)
    {
        $inputs = $request->all();
        $query = $this->model->newQuery();
        if (!empty($inputs['search'])) {
            $query->where(function ($q) use ($inputs) {
                $this->search($q, $inputs['search'], ['title']);
            })->orWhere('description', 'LIKE', '%' . $inputs['search'] . '%');
        }

        $announcements = $query->orderBy('id', 'DESC')->paginate(3);
        $artilces = '';
        if ($request->ajax()) {

            foreach ($announcements as $announcement) {
                $artilces .= '<div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="post-details">
                                    <h3 class="mb-2 text-black">' . $announcement->title . '</h3>
                                    <ul class="mb-4 post-meta">
                                        <li class="post-author"><i class="fa-regular fa-user"></i>' . $announcement->updatedBy->first_name . ' ' . $announcement->updatedBy->last_name . '</li>
                                        <li class="post-date"><i class="fa-light fa-calendar-days"></i>' . \Carbon\Carbon::parse($announcement->created_at)->format('d M Y') . '</li>
                                        <li class="post-date"><i class="fa-regular fa-clock"></i>' . \Carbon\Carbon::parse($announcement->created_at)->format('g:i A') . '</li>

                                    </ul>
                                    <div class="row mt-2">
                                        ' . $announcement->description . '
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }
            return response()->json($artilces);
        }
        return view('employees.announcements.index');
    }
    // public function listing(Request $request)
    // {
    //     $inputs = $request->all();
    //     $query = $this->model->newQuery();
    //     if (!empty($inputs['search'])) {
    //         $query->where(function ($q) use ($inputs) {
    //             $this->search($q, $inputs['search'], ['title']);
    //         });
    //     }
    //     $announcements = $query->orderBy('id', 'DESC')->paginate(3);
    //     $html = view('employees.announcements.row', compact('announcements'))->render();
    //     if ($request->ajax()) {
    //         return response()->json(['html' => $html]);
    //     } else {
    //         return view('employees.announcements.index', compact('announcements'));
    //     }
    // }

    public function details($id)
    {
        $announcement = $this->model->newQuery()->where('id', $id)->first();
        return view('employees.announcements.detail', compact('announcement'));
    }
}
