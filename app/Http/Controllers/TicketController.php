<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TicketStatusUpdated;
use App\Notifications\NewCommentNotification;
use App\Models\Announcement;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        // 1. Query Dasar
        $query = Ticket::with(['category', 'user', 'technician'])->latest();

        // 2. Filter Status (Jika ada request filter)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Ambil Data Tiket dengan Pagination
        $tickets = $query->paginate(10);
        
        // 4. Hitung Statistik (Global)
        // Kita gunakan query terpisah agar filter di atas tidak mempengaruhi total statistik keseluruhan
        $stats = [
            'total' => Ticket::count(),
            'open' => Ticket::where('status', 'Open')->count(),
            'progress' => Ticket::where('status', 'In Progress')->count(),
            'resolved' => Ticket::where('status', 'Resolved')->count(),
            'closed' => Ticket::where('status', 'Closed')->count(),
        ];

        // 5. Data untuk Grafik (Contoh: Tiket per Bulan selama tahun berjalan)
        $dbDriver = \Illuminate\Support\Facades\DB::connection()->getDriverName();

        if ($dbDriver === 'sqlite') {
            // Syntax khusus SQLite: strftime('%m', ...) ambil bulan angka 01-12
            // Kita cast ke integer agar "01" jadi 1, supaya cocok dengan looping php
            $selectQuery = "CAST(strftime('%m', created_at) AS INTEGER) as month, COUNT(*) as count";
        } else {
            // Syntax MySQL (Default)
            $selectQuery = 'MONTH(created_at) as month, COUNT(*) as count';
        }

        $chartData = Ticket::selectRaw($selectQuery)
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Format array agar indeks 1-12 selalu ada (meski nilai 0)
        $chartFormatted = [];
        for ($i = 1; $i <= 12; $i++) {
            // Karena di SQLite kita sudah CAST as INTEGER, key-nya akan berupa angka 1, 2, dst.
            $chartFormatted[] = $chartData[$i] ?? 0;
        }

        // Ambil list teknisi untuk dropdown
        $technicians = User::where('role', 'teknisi')->get();

        return view('tickets.index', compact('tickets', 'technicians', 'stats', 'chartFormatted'));
    }

   public function studentDashboard(Request $request)
    {
        // 1. Ambil Pengumuman
        $announcements = Announcement::where('is_active', true)->latest()->get();

        // 2. Query SEMUA TIKET (Global)
        // Eager load 'user' agar kita bisa tampilkan siapa pelapornya
        $query = Ticket::with(['category', 'user', 'technician']); 
        
        // Filter Search (Judul atau Kode)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('ticket_code', 'like', "%{$search}%");
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // 3. Pagination (9 per halaman)
        $tickets = $query->latest()->paginate(9);

        return view('student.dashboard', compact('announcements', 'tickets'));
    }

    public function myTickets(Request $request)
    {
        // Method ini HANYA fokus ke Search, Filter, dan Pagination Tiket
        $query = Ticket::where('user_id', Auth::id())
                    ->with(['category', 'user', 'technician']);

        // Filter Logic (Sama seperti sebelumnya)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                ->orWhere('ticket_code', 'like', "%{$request->search}%");
            });
        }
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('created_at', '<=', $request->date_to);

        $tickets = $query->latest()->paginate(9);

        // Kita tidak kirim $announcements ke sini lagi, karena sudah ada di Home
        return view('tickets.my_tickets', compact('tickets'));
    }

    /**
     * CREATE FORM
     */
    public function create()
    {
        $categories = Category::all();
        return view('tickets.create', compact('categories'));
    }

    /**
     * STORE TIKET
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string',
            'description' => 'required|string',
            'foto' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $imagePath = null;
        if ($request->hasFile('foto')) {
            $imagePath = $request->file('foto')->store('ticket-images', 'public');
        }

        Ticket::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'image_path' => $imagePath,
            'status' => 'Open', // Default status
            // ticket_code dibuat otomatis di Model Ticket (boot method)
        ]);

        // Redirect ke 'Tiket Saya' agar user melihat tiket barunya
        return redirect()->route('tickets.my_tickets')->with('success', 'Tiket berhasil dibuat!');
    }

    /**
     * SHOW DETAIL
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['comments.user', 'category']);
        return view('tickets.show', compact('ticket'));
    }

    /**
     * UPDATE TIKET (Logika Role)
     */
    public function update(Request $request, Ticket $ticket)
    {
        $user = Auth::user();

        // 1. Mahasiswa TIDAK BOLEH update tiket
        if ($user->role === 'mahasiswa') {
            abort(403, 'Akses ditolak.');
        }

        $oldStatus = $ticket->status;

        // 2. Logika TEKNISI (Hanya Progress & Auto Assign)
        if ($user->role === 'teknisi') {
            $request->validate([
                'status' => 'required|in:Open,In Progress,Resolved,Closed'
            ]);
            
            // Jika tiket belum ada teknisinya, otomatis ambil alih
            if (!$ticket->technician_id) {
                $ticket->technician_id = $user->id;
            }

            $ticket->update(['status' => $request->status]);
        }
        
        // 3. Logika ADMIN (Full Control: Status & Ganti Teknisi)
        elseif ($user->role === 'admin') {
            $validated = $request->validate([
                'status' => 'required|in:Open,In Progress,Resolved,Closed',
                'technician_id' => 'nullable|exists:users,id'
            ]);

            $ticket->update($validated);
        }

        // KIRIM NOTIFIKASI (Jika status berubah)
        if ($oldStatus !== $request->status) {
            try {
                $ticket->user->notify(new TicketStatusUpdated($ticket, $request->status));
            } catch (\Exception $e) {
                // Jangan biarkan error notifikasi menghentikan proses update
                // Log error jika perlu
            }
        }

        return back()->with('success', 'Tiket berhasil diperbarui.');
    }

    /**
     * STORE KOMENTAR
     */
    public function storeComment(Request $request, Ticket $ticket)
    {
        $request->validate(['message' => 'required']);

        $comment = Comment::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Get the commenter info
        $commenter = Auth::user();

        // Send notifications to relevant users (excluding the commenter)
        $usersToNotify = collect();

        // 1. If commenter is mahasiswa, notify admin and assigned teknisi
        if ($commenter->role === 'mahasiswa') {
            // Notify all admins
            $admins = User::where('role', 'admin')->get();
            $usersToNotify = $usersToNotify->merge($admins);

            // Notify assigned technician if exists
            if ($ticket->technician_id && $ticket->technician_id !== $commenter->id) {
                $usersToNotify->push($ticket->technician);
            }
        }
        // 2. If commenter is teknisi, notify admin and ticket creator
        elseif ($commenter->role === 'teknisi') {
            // Notify all admins
            $admins = User::where('role', 'admin')->get();
            $usersToNotify = $usersToNotify->merge($admins);

            // Notify ticket creator (mahasiswa)
            if ($ticket->user_id !== $commenter->id) {
                $usersToNotify->push($ticket->user);
            }
        }
        // 3. If commenter is admin, notify teknisi and ticket creator
        elseif ($commenter->role === 'admin') {
            // Notify assigned technician
            if ($ticket->technician_id) {
                $usersToNotify->push($ticket->technician);
            }

            // Notify ticket creator (mahasiswa)
            if ($ticket->user_id !== $commenter->id) {
                $usersToNotify->push($ticket->user);
            }
        }

        // Remove duplicates and send notifications
        $usersToNotify = $usersToNotify->unique('id')->filter();

        foreach ($usersToNotify as $user) {
            $user->notify(new NewCommentNotification($ticket, $comment, $commenter));
        }

        return back()->with('success', 'Komentar ditambahkan.');
    }

    /**
     * DESTROY (Hapus Tiket)
     */
    public function destroy(Ticket $ticket)
    {
        // Hanya Admin yang boleh menghapus
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang bisa menghapus tiket.');
        }

        // Hapus gambar dari storage jika ada
        if ($ticket->image_path) {
            Storage::disk('public')->delete($ticket->image_path);
        }

        // Hapus data tiket
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Tiket berhasil dihapus.');
    }
}