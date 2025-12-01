@extends('layouts.app')

@section('title', 'Echo Test')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-4">Laravel Echo Connection Test</h1>
            
            <div id="echo-status" class="mb-4 p-4 rounded-lg bg-gray-100">
                <p class="font-semibold">Checking Echo status...</p>
            </div>
            
            <div id="reverb-status" class="mb-4 p-4 rounded-lg bg-gray-100">
                <p class="font-semibold">Checking Reverb connection...</p>
            </div>
            
            <div class="space-y-2">
                <h3 class="font-bold">Environment:</h3>
                <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                    <li>VITE_REVERB_APP_KEY: {{ env('VITE_REVERB_APP_KEY') }}</li>
                    <li>VITE_REVERB_HOST: {{ env('VITE_REVERB_HOST') }}</li>
                    <li>VITE_REVERB_PORT: {{ env('VITE_REVERB_PORT') }}</li>
                    <li>VITE_REVERB_SCHEME: {{ env('VITE_REVERB_SCHEME') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const echoStatus = document.getElementById('echo-status');
    const reverbStatus = document.getElementById('reverb-status');
    
    // Check if Echo is loaded
    if (typeof window.Echo !== 'undefined') {
        echoStatus.innerHTML = '<p class="font-semibold text-green-600">✅ Laravel Echo is loaded!</p>';
        echoStatus.classList.remove('bg-gray-100');
        echoStatus.classList.add('bg-green-50');
        
        // Try to connect
        try {
            const connector = window.Echo.connector;
            const pusher = connector.pusher;
            
            pusher.connection.bind('state_change', function(states) {
                console.log('Pusher state:', states);
                
                if (states.current === 'connected') {
                    reverbStatus.innerHTML = '<p class="font-semibold text-green-600">✅ Connected to Reverb WebSocket!</p>';
                    reverbStatus.classList.remove('bg-gray-100');
                    reverbStatus.classList.add('bg-green-50');
                } else if (states.current === 'connecting') {
                    reverbStatus.innerHTML = '<p class="font-semibold text-yellow-600">⏳ Connecting to Reverb...</p>';
                    reverbStatus.classList.remove('bg-gray-100');
                    reverbStatus.classList.add('bg-yellow-50');
                } else if (states.current === 'unavailable' || states.current === 'failed') {
                    reverbStatus.innerHTML = `
                        <p class="font-semibold text-red-600">❌ Failed to connect to Reverb!</p>
                        <p class="text-sm text-red-600 mt-2">Make sure Reverb is running:</p>
                        <code class="block mt-1 p-2 bg-red-100 rounded">php artisan reverb:start</code>
                    `;
                    reverbStatus.classList.remove('bg-gray-100');
                    reverbStatus.classList.add('bg-red-50');
                }
            });
            
            pusher.connection.bind('error', function(err) {
                console.error('Connection error:', err);
                reverbStatus.innerHTML = `
                    <p class="font-semibold text-red-600">❌ Connection Error!</p>
                    <p class="text-sm text-red-600 mt-2">${err.error?.data?.message || 'Unknown error'}</p>
                `;
                reverbStatus.classList.remove('bg-gray-100');
                reverbStatus.classList.add('bg-red-50');
            });
            
        } catch (error) {
            console.error('Echo error:', error);
            reverbStatus.innerHTML = '<p class="font-semibold text-red-600">❌ Error accessing Echo connector</p>';
            reverbStatus.classList.remove('bg-gray-100');
            reverbStatus.classList.add('bg-red-50');
        }
    } else {
        echoStatus.innerHTML = `
            <p class="font-semibold text-red-600">❌ Laravel Echo is NOT loaded!</p>
            <p class="text-sm text-red-600 mt-2">Run: <code class="bg-red-100 px-1">pnpm run dev</code> or <code class="bg-red-100 px-1">pnpm run build</code></p>
        `;
        echoStatus.classList.remove('bg-gray-100');
        echoStatus.classList.add('bg-red-50');
    }
});
</script>
@endsection
