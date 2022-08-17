<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeacherSignupMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $email;
    public $password;
    public $institution_name;
    public $logo;
    public function __construct($email,$password,$institution_name,$logo)
    {
       $this->email=$email;
       $this->password=$password;
       $this->institution_name=$institution_name;
       $this->logo=$logo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.TeacherSignUpMail');
    }
}
