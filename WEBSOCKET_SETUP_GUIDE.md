# 🔧 WebSocket Server Setup - Quick Guide

## ⚠️ IMPORTANT: You MUST Start the WebSocket Server!

The live chat feature requires **Laravel Reverb** to be running. This is the WebSocket server that handles real-time communication.

## 🚀 Quick Start (3 Commands)

### Option 1: Manual (Recommended for Development)

Open **3 separate terminal windows**:

#### Terminal 1: Start Reverb (WebSocket Server)
```bash
cd /home/avelin/PnesHelpDeskTicket
php artisan reverb:start
```
✅ Leave this running! You should see:
```
INFO  Starting server on 0.0.0.0:8080 (localhost).
```

#### Terminal 2: Start Laravel
```bash
cd /home/avelin/PnesHelpDeskTicket
php artisan serve
```
✅ Leave this running! Access app at: http://localhost:8000

#### Terminal 3: Start Vite (for development)
```bash
cd /home/avelin/PnesHelpDeskTicket
pnpm run dev
```
✅ Leave this running! Hot reload enabled.

### Option 2: Using Scripts (Background Mode)

```bash
# Start all services
./start-servers.sh

# For development (includes Vite)
./start-servers.sh --dev

# Stop all services
./stop-servers.sh
```

## 🔍 Verify Services Are Running

Check if all ports are active:
```bash
# Check Reverb (WebSocket)
lsof -i :8080

# Check Laravel
lsof -i :8000

# Check Vite (if running)
lsof -i :5173
```

## 🌐 Test WebSocket Connection

1. Open your browser: http://localhost:8000
2. Open Developer Tools (F12)
3. Go to Console tab
4. You should see connection messages from Laravel Echo
5. No errors = ✅ Working!

### Expected Console Output:
```javascript
✅ Echo initialized
✅ Connecting to ws://localhost:8080...
✅ Connection established
```

### Error Indicators:
```javascript
❌ WebSocket connection failed
❌ Error connecting to Reverb
```

## 🐛 Troubleshooting

### Error: "WebSocket connection to 'ws://localhost:8080' failed"

**Problem:** Reverb server is not running.

**Solution:**
```bash
# In a new terminal
php artisan reverb:start
```

### Error: "Port 8080 already in use"

**Problem:** Another process is using port 8080.

**Solution:**
```bash
# Find and kill the process
lsof -ti:8080 | xargs kill -9

# Then start Reverb again
php artisan reverb:start
```

### Error: "Connection timeout"

**Problem:** Firewall blocking WebSocket connections.

**Solution:**
```bash
# Allow port 8080 (Ubuntu/Debian)
sudo ufw allow 8080/tcp

# Or temporarily disable firewall for testing
sudo ufw disable
```

### Chat not updating in real-time

**Checklist:**
- [ ] Reverb is running (`php artisan reverb:start`)
- [ ] Laravel is running (`php artisan serve`)
- [ ] Assets are compiled (`pnpm run build` or `pnpm run dev`)
- [ ] Browser console shows no WebSocket errors
- [ ] Both users are logged in
- [ ] You're testing in different browsers/incognito windows

## 📊 Port Usage

| Service | Port | URL/Command |
|---------|------|-------------|
| Reverb (WebSocket) | 8080 | ws://localhost:8080 |
| Laravel App | 8000 | http://localhost:8000 |
| Vite Dev Server | 5173 | http://localhost:5173 |

## 🔄 Restart Services

If things aren't working, try restarting:

```bash
# Stop all services
./stop-servers.sh

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Rebuild assets
pnpm run build

# Start again
./start-servers.sh
```

## 📱 Testing the Chat

### As Mahasiswa (Student):
1. Login as mahasiswa
2. Look for **blue chat button** in bottom-right corner
3. Click it to open chat
4. Send a message: "Hello, I need help!"
5. **Check if Reverb terminal shows activity**

### As Admin/Teknisi:
1. Login as admin or teknisi
2. Click **"Live Chat"** in navbar
3. **The student's message should appear instantly**
4. Reply: "Hi! How can I help you?"
5. **Student should see reply immediately**

## 🎯 Production Deployment

For production, use a process manager to keep Reverb running:

### Using Supervisor (Recommended)

Create `/etc/supervisor/conf.d/reverb.conf`:
```ini
[program:reverb]
process_name=%(program_name)s
command=php /path/to/artisan reverb:start
directory=/path/to/project
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/project/storage/logs/reverb.log
```

Then:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start reverb
```

### Using systemd

Create `/etc/systemd/system/reverb.service`:
```ini
[Unit]
Description=Laravel Reverb WebSocket Server
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/path/to/project
ExecStart=/usr/bin/php /path/to/project/artisan reverb:start
Restart=always

[Install]
WantedBy=multi-user.target
```

Then:
```bash
sudo systemctl daemon-reload
sudo systemctl enable reverb
sudo systemctl start reverb
```

## 🎉 Success Checklist

- [ ] Reverb running on port 8080
- [ ] Laravel running on port 8000
- [ ] Browser console shows "Connection established"
- [ ] Can see floating chat button (mahasiswa)
- [ ] Can access /admin/chat (admin/teknisi)
- [ ] Messages appear instantly on both sides
- [ ] No WebSocket errors in console

## 📝 Quick Commands Reference

```bash
# Start Reverb
php artisan reverb:start

# Start Laravel
php artisan serve

# Build assets (production)
pnpm run build

# Build assets (development with hot reload)
pnpm run dev

# Check if port is in use
lsof -i :8080

# Kill process on port
kill $(lsof -t -i:8080)

# View Reverb logs
tail -f storage/logs/laravel.log

# Test WebSocket connection
wscat -c ws://localhost:8080
```

## 🆘 Still Not Working?

1. **Check .env file:**
   ```env
   BROADCAST_CONNECTION=reverb
   REVERB_HOST="localhost"
   REVERB_PORT=8080
   REVERB_SCHEME=http
   ```

2. **Clear everything:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   pnpm run build
   ```

3. **Restart all services:**
   ```bash
   ./stop-servers.sh
   ./start-servers.sh
   ```

4. **Check browser console** for specific error messages

5. **Check Reverb terminal output** for connection attempts

---

Remember: **Reverb must be running** for real-time chat to work! It's like a phone line - both ends need to be connected. 📞
