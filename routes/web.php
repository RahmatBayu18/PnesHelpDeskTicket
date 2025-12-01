<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthControl;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\RoleController;
// use App\Http\Controllers\AnnouncementController; // Aktifkan jika sudah buat controller ini

Route::get('/', function () {
    return redirect()->route('login');
});

// GROUP GUEST
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthControl::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthControl::class, 'register']);
    Route::get('/login', [AuthControl::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthControl::class, 'login']);
});

// GROUP AUTH
Route::middleware(['auth'])->group(function () {
    
    // Logout
    Route::post('/logout', [AuthControl::class, 'logout'])->name('logout');

    // --- 1. DASHBOARD REDIRECTOR (LOGIC PENGARAHAN) ---
    Route::get('/dashboard', function () {
        // Jika Mahasiswa -> Ke Halaman Home (Dashboard Student)
        if (Auth::user()->role === 'mahasiswa') {
            return redirect()->route('student.dashboard');
        }
        // Jika Admin/Teknisi -> Ke Halaman Index Tiket (Dashboard Admin)
        return redirect()->route('tickets.index');
    })->name('dashboard');
    
    // MENU 1: HOME (Berisi Pengumuman & Statistik)
    // Pastikan di TicketController ada method 'studentDashboard'
    Route::get('/student/dashboard', [TicketController::class, 'studentDashboard'])
         ->name('student.dashboard');

    // MENU 2: TIKET SAYA (Berisi List Tiket, Filter, Search)
    // Pastikan di TicketController ada method 'myTickets'
    Route::get('/my-tickets', [TicketController::class, 'myTickets'])
         ->name('tickets.my_tickets');


    // --- 3. CRUD TIKET (GLOBAL) ---
    Route::resource('tickets', TicketController::class);
    
    // Route khusus untuk kirim komentar
    Route::post('tickets/{ticket}/comment', [TicketController::class, 'storeComment'])
         ->name('tickets.comment');


    // --- 4. AREA ADMIN (ROLE MANAGEMENT) ---
    // Sebaiknya tambahkan middleware check role admin di sini jika ingin lebih aman
    Route::prefix('admin')->group(function() { 
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::put('/roles/{user}', [RoleController::class, 'update'])->name('roles.update');
        
    });
});