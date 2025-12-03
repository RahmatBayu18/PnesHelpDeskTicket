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
        'technician_id', 
        'status'
    ];

    /**
     * Get full URL for the ticket image.
     * Returns storage URL when image_path exists and file is present, otherwise returns public asset placeholder.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            try {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->image_path)) {
                    return asset('storage/' . $this->image_path);
                }
            } catch (\Throwable $e) {
                
            }
        }

        return asset('aset/placeholder.jpg');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
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
        return $this->hasMany(Comment::class)->oldest();
    }

    public function technician() {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
