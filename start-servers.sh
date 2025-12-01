#!/bin/bash

# PensHelpDesk - Start All Servers Script
# This script starts all required services for the live chat feature

echo "╔═══════════════════════════════════════════════════════════════════════════╗"
echo "║              Starting PensHelpDesk with Live Chat Feature                ║"
echo "╚═══════════════════════════════════════════════════════════════════════════╝"
echo ""

# Check if running in tmux or screen
if [ -z "$TMUX" ] && [ -z "$STY" ]; then
    echo "⚠️  Warning: Not running in tmux or screen."
    echo "   Recommended: Use 'tmux' for better terminal management"
    echo ""
fi

# Function to check if port is in use
check_port() {
    if lsof -Pi :$1 -sTCP:LISTEN -t >/dev/null 2>&1; then
        return 0
    else
        return 1
    fi
}

# Check and start Reverb (WebSocket Server - Port 8080)
if check_port 8080; then
    echo "✅ Reverb WebSocket server already running on port 8080"
else
    echo "🚀 Starting Reverb WebSocket server..."
    php artisan reverb:start > storage/logs/reverb.log 2>&1 &
    sleep 2
    if check_port 8080; then
        echo "✅ Reverb started successfully on port 8080"
    else
        echo "❌ Failed to start Reverb"
        exit 1
    fi
fi

# Check and start Laravel (Port 8000)
if check_port 8000; then
    echo "✅ Laravel server already running on port 8000"
else
    echo "🚀 Starting Laravel application..."
    php artisan serve > storage/logs/laravel-serve.log 2>&1 &
    sleep 2
    if check_port 8000; then
        echo "✅ Laravel started successfully on port 8000"
    else
        echo "❌ Failed to start Laravel"
        exit 1
    fi
fi

# Check and start Vite (Port 5173 - only for development)
if [ "$1" = "--dev" ]; then
    if check_port 5173; then
        echo "✅ Vite dev server already running on port 5173"
    else
        echo "🚀 Starting Vite dev server..."
        pnpm run dev > storage/logs/vite.log 2>&1 &
        sleep 3
        if check_port 5173; then
            echo "✅ Vite started successfully on port 5173"
        else
            echo "❌ Failed to start Vite"
        fi
    fi
else
    echo "ℹ️  Vite dev server not started (use --dev flag to start)"
fi

echo ""
echo "╔═══════════════════════════════════════════════════════════════════════════╗"
echo "║                          ✅ All Services Running                          ║"
echo "╚═══════════════════════════════════════════════════════════════════════════╝"
echo ""
echo "📡 Services Status:"
echo "   • Laravel App:        http://localhost:8000"
echo "   • Reverb WebSocket:   ws://localhost:8080"
if [ "$1" = "--dev" ]; then
    echo "   • Vite Dev Server:    http://localhost:5173"
fi
echo ""
echo "🔍 Check logs:"
echo "   tail -f storage/logs/reverb.log"
echo "   tail -f storage/logs/laravel-serve.log"
if [ "$1" = "--dev" ]; then
    echo "   tail -f storage/logs/vite.log"
fi
echo ""
echo "🛑 To stop all services, run: ./stop-servers.sh"
echo ""
