<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Mail\CustomVerificationEmail; // Tambahan
use Illuminate\Support\Facades\Mail;  // Tambahan
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'nim',
        'email',
        'password',
        'role',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tickets() {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    public function assignedTickets() {
        return $this->hasMany(Ticket::class, 'technician_id');
    }

    public function hasRole($role) {
        return $this->role === $role;
    }

    public function sendEmailVerificationNotification()
    {
        Mail::to($this->email)->send(new CustomVerificationEmail($this));
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token, $this->email));
    }
}
