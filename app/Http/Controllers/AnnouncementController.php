<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AnnouncementNotification;

class AnnouncementController extends Controller
{
    /**
     * Tampilkan halaman manajemen pengumuman (Admin only)
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang dapat mengakses halaman ini.');
        }

        $announcements = Announcement::latest()->paginate(10);
        return view('announcements.index', compact('announcements'));
    }

    /**
     * Store pengumuman baru
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang dapat membuat pengumuman.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:info,warning,danger',
        ]);

        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'is_active' => true,
        ]);

        // Kirim notifikasi ke semua mahasiswa
        if ($request->has('send_notification')) {
            $students = User::where('role', 'mahasiswa')->get();
            foreach ($students as $student) {
                $student->notify(new AnnouncementNotification($announcement));
            }
        }

        return back()->with('success', 'Pengumuman berhasil dibuat!');
    }

    /**
     * Update status aktif/nonaktif
     */
    public function toggleStatus(Announcement $announcement)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $announcement->update([
            'is_active' => !$announcement->is_active
        ]);

        return back()->with('success', 'Status pengumuman berhasil diubah.');
    }

    /**
     * Hapus pengumuman
     */
    public function destroy(Announcement $announcement)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $announcement->delete();
        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}