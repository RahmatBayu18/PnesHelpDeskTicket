# Email Verification Setup Guide

## Gmail SMTP Configuration

To use Gmail for sending verification emails, you need to set up an App Password. Follow these steps:

### Step 1: Enable 2-Step Verification
1. Go to your Google Account: https://myaccount.google.com/
2. Select **Security** from the left menu
3. Under "How you sign in to Google", select **2-Step Verification**
4. Follow the steps to enable 2-Step Verification

### Step 2: Generate App Password
1. Go to: https://myaccount.google.com/apppasswords
2. Select "Mail" for app
3. Select "Other (Custom name)" for device
4. Enter a name like "Laravel Help Desk"
5. Click **Generate**
6. Copy the 16-character password (it will look like: xxxx xxxx xxxx xxxx)

### Step 3: Update .env File
Open your `.env` file and update the following values:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-character-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Important:**
- Replace `your-email@gmail.com` with your actual Gmail address
- Replace `your-16-character-app-password` with the app password generated in Step 2
- Remove all spaces from the app password when pasting it

### Step 4: Clear Configuration Cache
After updating .env, run:
```bash
php artisan config:clear
php artisan cache:clear
```

## Testing Email Verification

### Test Registration Flow
1. Clear your browser cookies/session
2. Visit `/register`
3. Create a new account
4. After registration, you'll be redirected to the email verification page
5. Check your Gmail inbox for the verification email
6. Click the verification link
7. You should be redirected to the dashboard

### Test Resend Functionality
1. From the verification notice page, click "Kirim Ulang Email Verifikasi"
2. A new verification email should be sent
3. You should see a success message

### Troubleshooting

**Email not sending?**
- Check that 2-Step Verification is enabled
- Verify the app password is correct (no spaces)
- Check `storage/logs/laravel.log` for errors
- Make sure your firewall allows outgoing connections on port 587

**"Invalid credentials" error?**
- Double-check the app password (regenerate if needed)
- Ensure you're using the app password, not your regular Gmail password
- Verify MAIL_USERNAME matches the Gmail account that generated the app password

**Email going to spam?**
- This is normal for development
- Ask users to check spam/junk folders
- For production, consider using a dedicated email service (SendGrid, Mailgun, etc.)

## Email Verification Features Implemented

✅ User model implements `MustVerifyEmail` interface
✅ Email verification routes configured
✅ Beautiful verification notice page
✅ Resend verification email functionality
✅ All protected routes require email verification
✅ Automatic email sending on registration
✅ Signed verification URLs for security

## Flow Diagram

```
User Registers
    ↓
Account Created
    ↓
Verification Email Sent (via Gmail SMTP)
    ↓
User Redirected to Verification Notice Page
    ↓
User Clicks Link in Email
    ↓
Email Verified
    ↓
User Redirected to Dashboard
    ↓
Full Access Granted
```

## Security Notes

- Verification links are signed and expire
- Throttling applied to resend functionality (6 attempts per minute)
- App passwords are more secure than using your main Gmail password
- Verification is required before accessing any protected routes
