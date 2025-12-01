#!/bin/bash

# PensHelpDesk - Stop All Servers Script

echo "🛑 Stopping PensHelpDesk services..."
echo ""

# Stop Reverb (Port 8080)
if lsof -Pi :8080 -sTCP:LISTEN -t >/dev/null 2>&1; then
    echo "Stopping Reverb WebSocket server..."
    kill $(lsof -t -i:8080) 2>/dev/null
    echo "✅ Reverb stopped"
else
    echo "ℹ️  Reverb not running"
fi

# Stop Laravel (Port 8000)
if lsof -Pi :8000 -sTCP:LISTEN -t >/dev/null 2>&1; then
    echo "Stopping Laravel server..."
    kill $(lsof -t -i:8000) 2>/dev/null
    echo "✅ Laravel stopped"
else
    echo "ℹ️  Laravel not running"
fi

# Stop Vite (Port 5173)
if lsof -Pi :5173 -sTCP:LISTEN -t >/dev/null 2>&1; then
    echo "Stopping Vite dev server..."
    kill $(lsof -t -i:5173) 2>/dev/null
    echo "✅ Vite stopped"
else
    echo "ℹ️  Vite not running"
fi

echo ""
echo "✅ All services stopped"
