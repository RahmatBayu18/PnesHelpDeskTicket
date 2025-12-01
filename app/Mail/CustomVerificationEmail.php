<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class CustomVerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationUrl;

    public function __construct(User $user)
    {
        $this->user = $user;

        // Generate signed URL verifikasi dengan signature yang valid
        $this->verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60), // URL valid untuk 60 menit
            [
                'id' => $user->id,
                'hash' => sha1($user->email)
            ]
        );
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
