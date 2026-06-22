<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class CareerGuidanceRegistrationMail extends Mailable
{
    public $banner;
    public $user;

    public function __construct($banner,$user)
    {
        $this->banner = $banner;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Career Guidance Session Registration')
            ->view('emails.career_guidance_registration');
    }
}