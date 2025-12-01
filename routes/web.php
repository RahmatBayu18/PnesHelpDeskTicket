<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthControl;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    // Show landing page for guests, redirect to dashboard for authenticated users
    if (Auth::check()) {
        if (Auth::user()->role === 'mahasiswa') {
            return redirect()->route('student.dashboard');
        }
        return redirect()->route('tickets.index');
    }
    return view('landing');
})->name('landing');

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
        if (Auth::user()->role === 'mahasiswa') {
            return redirect()->route('student.dashboard');
        }
        return redirect()->route('tickets.index');
    })->name('dashboard');
    
    // MENU 1: HOME (Berisi Pengumuman & Statistik)
    Route::get('/student/dashboard', [TicketController::class, 'studentDashboard'])
         ->name('student.dashboard');

    // MENU 2: TIKET SAYA (Berisi List Tiket, Filter, Search)
    Route::get('/my-tickets', [TicketController::class, 'myTickets'])
         ->name('tickets.my_tickets');

    // --- 3. CRUD TIKET (GLOBAL) ---
    Route::resource('tickets', TicketController::class);
    
    // Route khusus untuk kirim komentar
    Route::post('tickets/{ticket}/comment', [TicketController::class, 'storeComment'])
         ->name('tickets.comment');

    // --- 4. NOTIFICATIONS ---
    Route::get('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])
         ->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])
         ->name('notifications.mark-all-read');
    // Route Detail Pengumuman (Bisa diakses Mahasiswa & Admin)
    Route::get('/announcements/{announcement}', [App\Http\Controllers\AnnouncementController::class, 'show'])
         ->name('announcements.show');

    // --- 5. PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('/profile/picture', [ProfileController::class, 'updatePicture'])->name('profile.picture.update');
    Route::delete('/profile/picture', [ProfileController::class, 'deletePicture'])->name('profile.picture.delete');

    // --- 6. AREA ADMIN ---
    Route::prefix('admin')->group(function() { 
        
        // Role Management
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::put('/roles/{user}', [RoleController::class, 'update'])->name('roles.update');
        
        // Announcement Management
        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::patch('/announcements/{announcement}/toggle', [AnnouncementController::class, 'toggleStatus'])->name('announcements.toggle');
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    });
});