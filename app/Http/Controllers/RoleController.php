<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    // Menampilkan daftar user dan role-nya
    public function index()
    {
        // Ambil semua user kecuali user yang sedang login (biar gak hapus diri sendiri)
        $users = User::where('id', '!=', Auth::id())->latest()->get();
        return view('roles.index', compact('users'));
    }

    // Mengupdate role user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,teknisi,mahasiswa',
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Role pengguna berhasil diperbarui!');
    }
}