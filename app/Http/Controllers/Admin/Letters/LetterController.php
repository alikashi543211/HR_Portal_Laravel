<?php

namespace App\Http\Controllers\Admin\Letters;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Letters\SendMailRequest;
use App\Http\Requests\Admin\Letters\StoreRequest;
use App\Http\Requests\Admin\Letters\UpdateRequest;
use App\Letter;
use App\Mail\SendMail;
use App\User;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MPDF;

class LetterController extends Controller
{
    private $letter, $user;
    public function __construct()
    {
        $this->letter = new Letter();
        $this->user = new User();
        $this->defaultRedirectPath = URL_ADMIN . "letters/";
        $this->defaultViewPath = "admin.letters.";
    }

    public function listing(Request $request)
    {
        try {
            $letters = $this->letter->newQuery()->orderBy('id', 'DESC');
            $inputs = $request->all();

            if (!empty($inputs['search'])) {
                $letters->where(function ($q) use ($inputs) {
                    $this->search($q, $inputs['search'], ['title']);
                });
            }

            return $this->successListView("", $this->defaultViewPath.'.listing', __('letter.page_heading'), $letters->paginate(DATA_PER_PAGE), true, true);
        } catch (QueryException $e) {
            Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }

    public function create(Request $req)
    {
        try {
            return $this->successView("", $this->defaultViewPath . "add", __('letter.add_page_heading'), "");
        } catch (QueryException $e) {
            Session::flash($e->getMessage());
            return Redirect()->back();
        } catch (Exception $e) {
            Session::flash($e->getMessage());
            return Redirect()->back();
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();

            $letter = $this->letter->newInstance();
            $letter->fill($inputs);
            if ($letter->save()) {
                DB::commit();
                return $this->redirectBack(SUCCESS, __('default_label.saved'), 'admin/letters/listing');
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


    public function update(UpdateRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            // $this->getLetterVariables($inputs['body']);
            $letter = $this->letter->newQuery()->where('id', $inputs['id'])->first();
            $letter->fill($inputs);
            if ($letter->save()) {
                DB::commit();
                return $this->redirectBack(SUCCESS, __('default_label.updated'), 'admin/letters/listing');
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

    public function edit($id)
    {
        $letter = $this->letter->newQuery()->where('id', $id)->first();
        return $this->successView(NULL, $this->defaultViewPath . 'edit', __('letter.edit_page_heading'), ['letter' => $letter]);
    }

    public function mail($id)
    {
        $letter = $this->letter->newQuery()->whereId($id)->first();
        $users = $this->user->newQuery()->get();
        return $this->successView(NULL, $this->defaultViewPath . 'mail', __('letter.mail_page_heading'), ['letter' => $letter, 'users' => $users]);
    }

    public function sendMail(SendMailRequest $request)
    {
        $inputs = $request->all();
        $body = ($inputs['body']);
        if(isset($inputs['variable']))
        {
            foreach($inputs['variable'] as $key => $var)
            {
                $word = '['.$key.']';
                $body = str_replace($word, '<b>'.$var.'</b>', $body);
            }
        }

        // Generate PDF
        $name = ($inputs['title']).'-'.date('d-m-Y H:i:s') . '.pdf';
        $pdf = MPDF::loadView('partials.admin.exports.letter-pdf', ['data' => $body], [], ['title' => $inputs['title']]);
        return $pdf->download($name);
    }

    private function generateLetterPdf($body, $title)
    {

    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $letter = $this->letter->newQuery()->where('id', $id)->first();
            if ($letter->delete()) {
                DB::commit();
                return $this->redirectBack(SUCCESS, __('default_label.deleted'), 'admin/letters/listing');
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
}
