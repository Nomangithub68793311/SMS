<?php

namespace App\Jobs;
use App\Mail\AdminSignupMail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AdminEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to( $this->email)->send(new AdminSignupMail( $this->email,$this->password,$this->institution_name,$this->logo  ));
        // foreach (['taylor@example.com', 'dries@example.com'] as $recipient) {
        //     Mail::to($recipient)->send(new OrderShipped($order));
        // }
    }
}
