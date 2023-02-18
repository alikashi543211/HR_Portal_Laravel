<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Shipment;
use Illuminate\Support\Facades\Mail;

class SendMail
{
    public $body;
    public $subject;
    public $view;
    public $email;
    public $cc;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $subject, $view, $body, $cc = [])
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->view = $view;
        $this->body = $body;
        $this->cc = $cc;

        $this->send();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function send()
    {
        $email      = $this->email;
        $subject    = $this->subject;
        // dd(env('MAIL_FROM_ADDRESS'));
        Mail::send($this->view, $this->body, function ($message) use ($email, $subject) {
            $message->to($email)->cc($this->cc)->subject($subject)->replyTo(env('MAIL_FROM_ADDRESS'));
        });
    }
}
