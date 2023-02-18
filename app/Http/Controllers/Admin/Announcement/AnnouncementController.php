<?php

namespace App\Http\Controllers\Admin\Announcement;

use App\Announcement;
use App\Drivers\FirebaseDriver;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Announcement\StoreRequest;
use App\Http\Requests\Admin\Announcement\UpdateRequest;
use App\Mail\SendMail;
use App\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $query = $this->model->newQuery();
        if (!empty($inputs['search'])) {
            $query->where(function ($q) use ($inputs) {
                $this->search($q, $inputs['search'], getSearchColoumns('announcements'));
            });
        }
        $announcements = $query->orderBy('created_at', 'DESC')->paginate(DATA_PER_PAGE);
        return $this->successListView(NULL, $this->defaultViewPath . 'listing', __('announcement.page_heading'), $announcements);
    }

    /** 
     * Return new annoucemnet page
     */
    public function add()
    {
        return $this->successView(NULL, $this->defaultViewPath . 'add', __('announcement.add_page_heading'));
    }


    /**
     * Store new announcement
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $announcement = $this->model->newInstance();
            $announcement->fill($inputs);
            if ($announcement->save()) {
                DB::commit();
                $notify = new FirebaseDriver();
                $notify->setMessageBody("New Announcement", $announcement->title, NOTIFICATION_ANNOUNCEMENT);
                $notify->sendNotificationToUser("Employees");
                return $this->redirectBack(SUCCESS, __('default_label.saved'), 'admin/announcements/listing');
            }
            DB::rollback();
            return $this->redirectBack(ERROR, __('default_label.save'));
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->redirectBack(ERROR, $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->redirectBack(ERROR, $e->getMessage());
        }
    }

    /** 
     * Return new annoucemnet page
     */
    public function edit($id)
    {
        $announcement = $this->model->newQuery()->where('id', $id)->first();
        return $this->successView(NULL, $this->defaultViewPath . 'edit', __('announcement.edit_page_heading'), ['announcement' => $announcement]);
    }

    /**
     * update announcement
     */
    public function update(UpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $announcement = $this->model->newQuery()->where('id', $inputs['id'])->first();
            $announcement->fill($inputs);
            if ($announcement->save()) {
                DB::commit();
                return $this->redirectBack(SUCCESS, __('default_label.updated'), 'admin/announcements/listing');
            }
            DB::rollback();
            return $this->redirectBack(ERROR, __('default_label.update'));
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->redirectBack(ERROR, $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->redirectBack(ERROR, $e->getMessage());
        }
    }

    /**
     * delete announcement
     */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $announcement = $this->model->newQuery()->where('id', $id)->first();
            if ($announcement->delete()) {
                DB::commit();
                return $this->redirectBack(SUCCESS, __('default_label.deleted'), 'admin/announcements/listing');
            }
            DB::rollback();
            return $this->redirectBack(ERROR, __('default_label.delete'));
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->redirectBack(ERROR, $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return $this->redirectBack(ERROR, $e->getMessage());
        }
    }

    public function sendEmail($id)
    {
        $announcement = $this->model->newQuery()->where('id', $id)->first();
        $emails = $this->user->newQuery()->where('status', '!=', USER_STATUS_TERMINATE)->whereIn('role_id', [MANAGER, EMPLOYEE])->pluck('email')->toArray();
        if (count($emails)) {
            new SendMail($emails, 'Announcement - ' . $announcement->title, 'emails.mailbody', ['body' => $announcement->description]);
        }
        return $this->redirectBack(SUCCESS, __('default_label.email_sent'), 'admin/announcements/listing');
    }
}
