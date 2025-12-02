# 🔐 Fitur Lupa Password - PNES Help Desk

## ✨ Fitur yang Telah Diimplementasikan

Fitur lupa password lengkap dengan:
- ✅ Form lupa password
- ✅ Pengiriman email reset password
- ✅ Token keamanan dengan expiry 60 menit
- ✅ Form reset password dengan validasi
- ✅ Password strength indicator
- ✅ Password match validation real-time
- ✅ Notifikasi email custom

---

## 📝 Cara Menggunakan

### 1. **Pengguna Request Reset Password**

1. Di halaman login, klik link "Forgot your password?"
2. Masukkan email yang terdaftar
3. Sistem akan mengirim link reset password ke email

### 2. **Pengguna Mereset Password**

1. Buka email dan klik link yang diterima
2. Masukkan password baru (minimal 8 karakter)
3. Konfirmasi password
4. Klik "Reset Password"
5. Login dengan password baru

---

## 🔧 Setup yang Sudah Dilakukan

### 1. **Database Migration**
File: `database/migrations/2025_12_01_235612_create_password_reset_tokens_table.php`

Tabel `password_reset_tokens` dengan struktur:
- `email` (primary key)
- `token` (hashed)
- `created_at` (timestamp)

### 2. **Notification Email**
File: `app/Notifications/ResetPasswordNotification.php`

Email notification kustom dengan:
- Subjek: "Reset Password - PNES Help Desk"
- Link reset password
- Informasi expiry (60 menit)

### 3. **Controller Methods**
File: `app/Http/Controllers/AuthControl.php`

Methods yang ditambahkan:
- `showForgotPasswordForm()` - Menampilkan form lupa password
- `sendResetLinkEmail()` - Mengirim link reset ke email
- `showResetPasswordForm()` - Menampilkan form reset password
- `resetPassword()` - Memproses reset password

### 4. **Routes**
File: `routes/web.php`

Routes yang ditambahkan:
```php
Route::get('/forgot-password', [AuthControl::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthControl::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthControl::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthControl::class, 'resetPassword'])->name('password.update');
```

### 5. **Views**
- `resources/views/auth/forgot-password.blade.php` - Form lupa password
- `resources/views/auth/reset-password.blade.php` - Form reset password
- `resources/views/auth/login.blade.php` - Updated dengan link lupa password

---

## 🚀 Cara Menjalankan di Docker

### 1. **Run Migration**
```bash
# Jika menggunakan Docker
docker-compose exec app php artisan migrate

# Atau masuk ke container dulu
docker-compose exec app bash
php artisan migrate
```

### 2. **Pastikan Email Configuration Benar**
Check file `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_gmail_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your_email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**PENTING:** Untuk Gmail, gunakan App Password, bukan password biasa:
1. Buka https://myaccount.google.com/security
2. Aktifkan 2-Step Verification
3. Buka "App passwords"
4. Generate password untuk "Mail"
5. Copy password tersebut ke `MAIL_PASSWORD` di `.env`

### 3. **Test Email di Docker**
```bash
# Masuk ke container
docker-compose exec app bash

# Test email
php artisan tinker

# Di tinker, jalankan:
Mail::raw('Test email', function ($message) {
    $message->to('your_test_email@gmail.com')
            ->subject('Test Email dari PENS Help Desk');
});
```

### 4. **Clear Cache**
```bash
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
```

---

## 🧪 Testing

### Test 1: Request Reset Password
1. Akses: `http://localhost:8000/forgot-password`
2. Masukkan email user yang ada (misal: `mahasiswa1@pens.ac.id`)
3. Klik "Kirim Link Reset Password"
4. Cek email

### Test 2: Reset Password dengan Valid Token
1. Klik link di email
2. Masukkan password baru
3. Konfirmasi password
4. Klik "Reset Password"
5. Coba login dengan password baru

### Test 3: Token Expired
1. Request reset password
2. Tunggu 61 menit
3. Coba gunakan link
4. Harus muncul error "Token telah kadaluarsa"

### Test 4: Invalid Email
1. Di form forgot password, masukkan email yang tidak terdaftar
2. Harus muncul error "Email tidak ditemukan"

### Test 5: Password Validation
1. Di form reset password, coba password kurang dari 8 karakter
2. Coba password yang tidak match dengan konfirmasi
3. Harus ada validasi error

---

## 🔒 Keamanan

### Fitur Keamanan yang Diimplementasikan:

1. **Token Hashing**
   - Token di-hash sebelum disimpan di database
   - Tidak ada plain text token di database

2. **Token Expiry**
   - Token otomatis kadaluarsa setelah 60 menit
   - Token dihapus setelah digunakan

3. **Email Validation**
   - Validasi email harus ada di database
   - Tidak ada information leakage untuk email yang tidak ada

4. **Password Requirements**
   - Minimal 8 karakter
   - Harus di-confirm
   - Password di-hash dengan bcrypt

5. **CSRF Protection**
   - Semua form dilindungi dengan CSRF token

---

## 📧 Format Email yang Dikirim

Subject: **Reset Password - PNES Help Desk**

Content:
```
Halo!

Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.

[Button: Reset Password]

Link reset password ini akan kadaluarsa dalam 60 menit.

Jika Anda tidak meminta reset password, abaikan email ini.

Salam,
Tim PNES Help Desk
```

---

## 🐛 Troubleshooting

### Email Tidak Terkirim

**Problem:** Email tidak diterima

**Solution:**
1. Check email configuration di `.env`
2. Pastikan menggunakan App Password untuk Gmail
3. Check log: `storage/logs/laravel.log`
4. Test koneksi SMTP:
   ```bash
   php artisan tinker
   Mail::raw('Test', function($m) { $m->to('test@test.com')->subject('Test'); });
   ```

### Token Invalid

**Problem:** "Token reset password tidak valid"

**Solution:**
- Link mungkin sudah pernah digunakan
- Token sudah kadaluarsa (> 60 menit)
- Request reset password ulang

### Migration Error

**Problem:** Table `password_reset_tokens` not found

**Solution:**
```bash
# Check migrations
php artisan migrate:status

# Run migrations
php artisan migrate

# Jika ada error, reset migrations (HATI-HATI: akan hapus semua data!)
php artisan migrate:fresh --seed
```

### Permission Denied

**Problem:** Permission denied saat run migration di Docker

**Solution:**
```bash
# Dari host, fix ownership
docker-compose exec app chown -R www-data:www-data /var/www/storage
docker-compose exec app chmod -R 775 /var/www/storage

# Run migration
docker-compose exec app php artisan migrate
```

---

## 📱 UI Features

### Forgot Password Page
- ✨ Modern gradient design
- 🎨 Icon-based interface
- ✅ Success/error messages
- 📝 Helpful instructions
- 🔙 Back to login link

### Reset Password Page
- 🔐 Password visibility toggle
- 📊 Real-time password strength indicator
- ✅ Password match validation
- 📋 Password requirements checklist
- 🎯 Interactive Alpine.js validation
- 🚫 Submit button disabled until valid

---

## 🎯 Next Steps (Optional Enhancements)

Jika ingin menambahkan fitur lanjutan:

1. **Rate Limiting**
   - Batasi jumlah request reset password per IP
   - Batasi jumlah request per email

2. **Notification History**
   - Log semua password reset attempts
   - Notifikasi ke admin untuk aktivitas mencurigakan

3. **Multi-Language Support**
   - Terjemahkan email ke Bahasa Indonesia
   - Customize untuk  PENS branding

4. **2FA (Two-Factor Authentication)**
   - Tambah layer keamanan extra
   - Require OTP saat reset password

---

## ✅ Checklist Implementasi

- [x] Create migration untuk `password_reset_tokens`
- [x] Buat Notification class
- [x] Update User model dengan custom notification
- [x] Tambah methods di AuthControl
- [x] Buat routes untuk forgot/reset password
- [x] Buat view `forgot-password.blade.php`
- [x] Buat view `reset-password.blade.php`
- [x] Update login view dengan link lupa password
- [x] Implementasi validasi dan keamanan
- [x] Buat dokumentasi

## 🎉 Status: READY TO USE!

Fitur lupa password sudah lengkap dan siap digunakan. Jalankan migration dan test!

```bash
# Di Docker
docker-compose exec app php artisan migrate

# Test langsung di browser
http://localhost:8000/forgot-password
```
