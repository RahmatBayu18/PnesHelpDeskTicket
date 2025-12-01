<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class CustomVerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationUrl;

    public function __construct(User $user)
    {
        $this->user = $user;

        // Generate URL verifikasi standar Laravel
        $this->verificationUrl = url("/email/verify/" . $user->id . "/" . sha1($user->email));
    }

    public function build()
    {
        return $this->subject('Verifikasi Email Anda')
                    ->markdown('emails.verify')
                    ->with([
                        'user' => $this->user,
                        'verificationUrl' => $this->verificationUrl,
                    ]);
    }
}
