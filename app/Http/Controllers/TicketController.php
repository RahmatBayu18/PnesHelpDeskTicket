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

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Ticket::with(['category', 'user', 'technician'])->latest();

        // LOGIKA ROLE:
        // Jika Mahasiswa: Hanya lihat tiket sendiri
        if ($user->role === 'mahasiswa') {
            $query->where('user_id', $user->id);
        }
        // Jika Teknisi: Lihat tiket yang ditugaskan ke dia ATAU tiket yang belum ada teknisinya (Open)
        elseif ($user->role === 'teknisi') {
            $query->where(function($q) use ($user) {
                $q->where('technician_id', $user->id)
                  ->orWhere('technician_id', null);
            });
        }
        // Jika Admin: Lihat SEMUA tiket (tidak perlu filter tambahan)

        // Filter Tambahan (Status/Kategori) sama seperti sebelumnya...
        if ($request->filled('status')) $query->where('status', $request->status);

        $tickets = $query->paginate(10);
        
        // Ambil list teknisi untuk dropdown assign (hanya untuk Admin)
        $technicians = User::where('role', 'teknisi')->get();

        return view('tickets.index', compact('tickets', 'technicians'));
    }

    // Update Status & Assign Teknisi (Khusus Admin/Teknisi)
    public function update(Request $request, Ticket $ticket)
    {
        // Validasi akses (bisa dipindah ke Middleware/Policy)
        if (Auth::user()->role === 'mahasiswa') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:Open,In Progress,Resolved,Closed',
            'technician_id' => 'nullable|exists:users,id'
        ]);

        $oldStatus = $ticket->status;
        
        // Update Data
        $ticket->update([
            'status' => $request->status,
            'technician_id' => $request->technician_id ?? $ticket->technician_id
        ]);

        // KIRIM NOTIFIKASI jika status berubah
        if ($oldStatus !== $request->status) {
            // Kirim ke Pemilik Tiket (Mahasiswa)
            $ticket->user->notify(new TicketStatusUpdated($ticket, $request->status));
        }

        return back()->with('success', 'Tiket berhasil diperbarui.');
    }

    // Halaman Form Buat Tiket
    public function create()
    {
        $categories = Category::all();
        return view('tickets.create', compact('categories'));
    }

    // Simpan Tiket
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
            // ticket_code dibuat otomatis di Model
        ]);

        return redirect()->route('tickets.index')->with('success', 'Tiket berhasil dibuat!');
    }

    // Halaman Detail Tiket
    public function show(Ticket $ticket)
    {
        $ticket->load(['comments.user', 'category']);
        return view('tickets.show', compact('ticket'));
    }

    // Simpan Komentar
    public function storeComment(Request $request, Ticket $ticket)
    {
        $request->validate(['message' => 'required']);

        Comment::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return back()->with('success', 'Komentar ditambahkan.');
    }

    // Destroyyyyy!!!
    public function destroy(Ticket $ticket)
    {
        // Hanya Admin yang boleh menghapus
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
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
