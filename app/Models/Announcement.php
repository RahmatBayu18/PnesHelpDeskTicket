<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Announcement extends Model
{
    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);
        
        Announcement::create($request->all());
        return back()->with('success', 'Pengumuman diterbitkan');
    }
}
