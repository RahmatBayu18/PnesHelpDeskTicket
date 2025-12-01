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

        // Eager load 'user' agar query lebih efisien saat menampilkan nama pembuat
        $announcements = Announcement::with('user')->latest()->paginate(10);
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

        // Validasi input
        $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|string', // Kategori wajib diisi
            'content'  => 'required|string',
            'type'     => 'required|in:info,warning,danger',
        ]);

        // Simpan ke Database
        $announcement = Announcement::create([
            'user_id'   => Auth::id(), // Simpan ID Admin yang login
            'category'  => $request->category, // Simpan kategori
            'title'     => $request->title,
            'content'   => $request->content,
            'type'      => $request->type,
            'is_active' => true,
        ]);

        // Kirim notifikasi ke semua mahasiswa
        if ($request->has('send_notification')) {
            $students = User::where('role', 'mahasiswa')->get();
            foreach ($students as $student) {
                // Pastikan class Notification sudah dibuat & diimport
                try {
                    $student->notify(new AnnouncementNotification($announcement));
                } catch (\Exception $e) {
                    // Abaikan error notifikasi agar tidak menggangu proses simpan
                }
            }
        }

        return back()->with('success', 'Pengumuman berhasil dibuat!');
    }

    /**
     * TAMPILKAN DETAIL PENGUMUMAN (Halaman Baru)
     */
    public function show(Announcement $announcement)
    {
        // Cek akses: Admin bebas akses, Mahasiswa hanya jika aktif
        if (!$announcement->is_active && Auth::user()->role !== 'admin') {
            abort(404);
        }

        return view('announcements.show', compact('announcement'));
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