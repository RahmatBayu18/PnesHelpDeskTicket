<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    // Tambahkan kolom yang boleh diisi secara massal di sini
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
    ];

    // Relasi ke User (Penulis komentar)
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function tickets() {
        return $this->hasMany(Ticket::class);
    }
}
