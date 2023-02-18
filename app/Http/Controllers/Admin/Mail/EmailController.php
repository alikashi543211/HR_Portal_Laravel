<?php

namespace App\Http\Controllers\Admin\Mail;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\User;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
        $this->defaultRedirectPath = URL_ADMIN . "emails/";
        $this->defaultViewPath = "admin.";
    }

    public function emailPage()
    {
        $users = $this->user->newQuery()->where('role_id', '!=', SUPER_ADMIN)->get();
        return $this->successView(NULL, $this->defaultViewPath . 'send-email', 'Send Emails', ['data' => $users]);
    }

    public function sendEmails(Request $request)
    {
        $inputs = $request->all();
        $emails = $this->user->newQuery()->whereIn('id', $inputs['emails'])->pluck('email')->toArray();
        $cc = $this->user->newQuery()->whereIn('id', $inputs['cc'])->pluck('email')->toArray();
        if (new SendMail($emails, $inputs['subject'], 'emails.mailbody', ['body' => $inputs['description']], $cc)) {
            return $this->redirectBack(SUCCESS, __('default_label.email_sent'));
        } else return $this->redirectBack(ERROR, __('default_label.something_went_wrong'));
    }
}
