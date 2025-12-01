<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthControl;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;

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

// Echo test route (accessible to all authenticated users)
Route::get('/echo-test', function () {
    return view('echo-test');
})->middleware('auth')->name('echo.test');

// API endpoint for notification count
Route::get('/api/notifications/count', function () {
    return response()->json([
        'count' => Auth::user()->unreadNotifications->count()
    ]);
})->middleware('auth');

// GROUP GUEST
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthControl::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthControl::class, 'register']);
    Route::get('/login', [AuthControl::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthControl::class, 'login']);
});

// EMAIL VERIFICATION ROUTES
// Email verification handler - TIDAK memerlukan auth middleware
Route::get('/email/verify/{id}/{hash}', function (Request $request) {
    $user = \App\Models\User::findOrFail($request->route('id'));

    // Verifikasi hash email
    if (! hash_equals(sha1($user->email), (string) $request->route('hash'))) {
        abort(403, 'Invalid verification link');
    }

    // Verifikasi signature
    if (! $request->hasValidSignature()) {
        abort(403, 'Invalid or expired verification link');
    }

    // Tandai email sebagai terverifikasi
    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    // Redirect dengan pesan sukses
    return redirect('/login')->with('success', 'Email berhasil diverifikasi! Silakan login.');
})->middleware(['signed'])->name('verification.verify');

// Routes yang memerlukan auth
Route::middleware('auth')->group(function () {
    // Email verification notice
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // Resend verification email
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Link verifikasi telah dikirim ulang!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// Logout route (accessible by any authenticated user, even unverified)
Route::post('/logout', [AuthControl::class, 'logout'])->middleware('auth')->name('logout');

// GROUP AUTH (VERIFIED USERS ONLY)
Route::middleware(['auth', 'verified'])->group(function () {

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

    // --- 6. LIVE CHAT ---
    // Admin chat interface
    Route::get('/admin/chat', [ChatController::class, 'index'])->name('chat.index')
        ->middleware('role:admin,teknisi');
    
    // Chat API endpoints
    Route::post('/chat/conversation', [ChatController::class, 'getOrCreateConversation'])->name('chat.conversation');
    Route::get('/chat/{conversationId}/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/{conversationId}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/{conversationId}/assign', [ChatController::class, 'assignAdmin'])->name('chat.assign');
    Route::post('/chat/{conversationId}/close', [ChatController::class, 'closeConversation'])->name('chat.close');

    // --- 7. AREA ADMIN ---
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