<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthControl;
use App\Http\Controllers\TicketController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthControl::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthControl::class, 'register']);
    Route::get('/login', [AuthControl::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthControl::class, 'login']);
});

Route::middleware(['auth'])->group(function () {
    
    // Logout
    Route::post('/logout', [AuthControl::class, 'logout'])->name('logout');

    // Dashboard -> Redirect otomatis ke daftar tiket
    Route::get('/dashboard', function () {
        return redirect()->route('tickets.index');
    })->name('dashboard');

    // Resource Controller untuk Tiket (CRUD lengkap)
    Route::resource('tickets', TicketController::class);

    // Route khusus untuk kirim komentar
    Route::post('tickets/{ticket}/comment', [TicketController::class, 'storeComment'])->name('tickets.comment');

    // Route SU
    Route::get('/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('roles.index');
    Route::put('/roles/{user}', [App\Http\Controllers\RoleController::class, 'update'])->name('roles.update');
});