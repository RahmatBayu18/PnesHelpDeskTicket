<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\ChatConversation;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    $conversation = ChatConversation::find($conversationId);
    
    if (!$conversation) {
        return false;
    }
    
    // Allow if user is the conversation owner or admin/teknisi
    return $user->id === $conversation->user_id || 
           in_array($user->role, ['admin', 'teknisi']);
});
