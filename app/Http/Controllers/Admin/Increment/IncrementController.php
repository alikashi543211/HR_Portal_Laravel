<?php

namespace App\Http\Controllers\Admin\Increment;

use App\Http\Controllers\Controller;
use App\Increment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IncrementController extends Controller
{
    private $model, $user;

    /*
    |
    |   Controller Instance
    |
    */

    public function __construct()
    {
        $this->model = new Increment();
        $this->user = new User();
        $this->defaultRedirectPath = URL_ADMIN . "increments/";
        $this->defaultViewPath = "admin.increments.";
    }

    public function listing(Request $request)
    {
        $increments = $this->model->newQuery()->orderBy('created_at', 'DESC');
        $increments = $increments->paginate(DATA_PER_PAGE);
        return $this->successListView(NULL, $this->defaultRedirectPath . 'listing', 'Increments', $increments);
    }


    public function add()
    {
        $users = $this->user->newQuery()->where('status', '!=', 'TERMINATED')->get();
        return $this->successView(NULL, $this->defaultViewPath . 'add', 'Add Increment', $users);
    }


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $inputs['month'] = str_replace(',', '', $inputs['month']);
            $model = $this->model->newInstance();
            $model->created_by = Auth::id();
            $model->fill($inputs);
            if ($model->save()) {
                DB::commit();
                return $this->redirectBack(SUCCESS, __('default_label.saved'), $this->defaultRedirectPath . "listing");
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

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $model = $this->model->newQuery()->where('id', $id)->with('user')->first();

            // $this->model->newQuery()->where('user_id', $model->user_id)->where('month', '>', $model->getOriginal('month'))->decrement('previous', $model->increment);

            if ($model->delete()) {
                DB::commit();
                return $this->redirectBack(SUCCESS, __('default_label.saved'), $this->defaultRedirectPath . "listing");
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
}
