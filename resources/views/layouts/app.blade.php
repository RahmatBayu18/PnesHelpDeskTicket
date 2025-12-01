<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>PensHelpDesk</title>
</head>
<body class="{{ request()->routeIs('chat.index') ? 'overflow-hidden' : '' }}">

    <x-header />

    <div class="{{ request()->routeIs('chat.index') ? '' : 'pt-32' }}">
        @yield('content')
    </div>

    @unless(request()->routeIs('chat.index'))
        <x-footer />
    @endunless

    <!-- Floating Chat Widget for Mahasiswa -->
    @include('components.chat-widget')

    @stack('scripts')
    
    @auth
    <!-- Notification Sound System -->
    <script>
        let lastNotificationCount = {{ auth()->user()->unreadNotifications->count() }};
        
        // Notification sound function
        function playNotificationSound() {
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.value = 800;
                oscillator.type = 'sine';
                
                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
                
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.5);
            } catch (error) {
                console.error('Error playing notification sound:', error);
            }
        }
        
        // Check for new notifications every 30 seconds
        setInterval(function() {
            fetch('/api/notifications/count')
                .then(response => response.json())
                .then(data => {
                    if (data.count > lastNotificationCount) {
                        playNotificationSound();
                        // Reload page to update notification badge
                        location.reload();
                    }
                    lastNotificationCount = data.count;
                })
                .catch(error => console.error('Error checking notifications:', error));
        }, 30000); // Check every 30 seconds
    </script>
    @endauth
</body>
</html>