<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_code', 
        'user_id', 
        'category_id', 
        'title', 
        'description', 
        'location', 
        'image_path', 
        'technician_id', // Penting untuk assignment
        'status'
    ];

    // Otomatis Generate Ticket Code 6 Digit saat Create
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            // Cek biar tidak error kalau ticket_code sudah diisi manual (misal saat seeding)
            if (empty($ticket->ticket_code)) {
                do {
                    $code = strtoupper(Str::random(6));
                } while (self::where('ticket_code', $code)->exists());
                
                $ticket->ticket_code = $code;
            }
        });
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class)->latest();
    }

    public function technician() {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
