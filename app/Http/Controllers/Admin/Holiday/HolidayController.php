<?php

namespace App\Http\Controllers\Admin\Holiday;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Holiday\StoreRequest;
use App\Http\Requests\Admin\Holiday\UpdateRequest;
use App\User;
use App\Holiday;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Str;

class HolidayController extends Controller
{
    /*
    |
    |   Controller Instance
    |
    */

    public function __construct()
    {
        $this->model = new Holiday();
        $this->user = new User();
        $this->defaultRedirectPath = URL_ADMIN . "holidays/";
        $this->defaultViewPath = "admin.holidays.";
    }

    /*
    |
    |   Creating Admin
    |
    */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $model = $this->model->newInstance();
            $inputs['created_by'] = Auth::id();
            $model->fill($inputs);

            if ($model->save()) {
                DB::commit();
                return $this->redirectBack(SUCCESS, __('default_label.saved'), $this->defaultRedirectPath . "listing", "url");
            }

            DB::rollback();
            return Redirect()->back();
        } catch (QueryException $e) {
            DB::rollBack();
            return Redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect()->back();
        }
    }

    /*
    |
    |   Updating admin
    |
    */
    public function update(UpdateRequest $request)
    {
        try {

            DB::beginTransaction();
            $inputs = $request->all();
            $model = $this->model->newQuery()->whereId($inputs['id'])->first();

            $model->fill($inputs);

            if ($model->save()) {
                DB::commit();
                return $this->redirectBack("success", __('default_label.updated'), $this->defaultRedirectPath . "listing");
            }

            DB::rollback();
            return $this->redirectBack("error", __('default_label.update'));
        } catch (QueryException $e) {
            DB::rollBack();
            \Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            \Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }

    /*
    |
    |   Getting delete admin
    |
    */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $model = $this->model->newQuery()->whereId($id)->first();

            if ($model && $model->delete()) {
                DB::commit();
                return $this->redirectBack("", __('default_label.deleted'), $this->defaultRedirectPath . "listing");
            }

            DB::rollback();
            return $this->redirectBack("", __('default_label.delete'));
        } catch (QueryException $e) {
            DB::rollBack();
            \Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            \Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }

    /*
    |
    |   Getting Listing admin
    |
    */
    public function listing(Request $request)
    {
        try {
            $model = $this->model->newQuery()->orderBy('id', 'DESC');
            $inputs = $request->all();

            if (!empty($inputs['search'])) {

                $model->where(function ($q) use ($inputs) {
                    $this->search($q, $inputs['search'], ['name', 'date']);
                });
            }

            return $this->successListView("", $this->defaultRedirectPath . "listing", __('holiday.page_heading'), $model->paginate(DATA_PER_PAGE));
        } catch (QueryException $e) {
            \Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            \Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }

    /*
    |
    |   Creating view to add User
    |
    */
    public function create(Request $req)
    {
        try {
            return $this->successView("", $this->defaultViewPath . "add", __('holiday.add_page_heading'), "");
        } catch (QueryException $e) {
            \Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            \Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }

    /*
    |
    |   EDIT view to add User
    |
    */
    public function edit(Request $req, $id)
    {
        try {

            DB::beginTransaction();
            $inputs = $req->all();
            $model = $this->model->newQuery()->whereId($id)->first();
            if ($model) {

                return $this->successView("", $this->defaultViewPath . "edit", __('holiday.edit_page_heading'), $model);
            }

            DB::rollback();
            return $this->redirectBack("", __('default_label.fetch'));
        } catch (QueryException $e) {
            DB::rollBack();
            \Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            \Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }
}
