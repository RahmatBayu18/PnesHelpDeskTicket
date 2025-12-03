<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Admin chat index page
     */
    public function index()
    {
        $conversations = ChatConversation::with(['user', 'latestMessage'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10); 

        return view('admin.chat.index', compact('conversations'));
    }

    /**
     * Get or create conversation for current user
     */
    public function getOrCreateConversation()
    {
        $user = Auth::user();

        $conversation = ChatConversation::firstOrCreate(
            ['user_id' => $user->id],
            [
                'status' => 'open',
                'last_message_at' => now(),
            ]
        );

        return response()->json([
            'conversation' => $conversation,
        ]);
    }

    /**
     * Get messages for a conversation
     */
    public function getMessages($conversationId)
    {
        $conversation = ChatConversation::findOrFail($conversationId);
        
        // Check authorization
        if (Auth::user()->role === 'mahasiswa' && $conversation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        $conversation->messages()
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'messages' => $messages,
            'conversation' => $conversation->load(['user', 'admin']),
        ]);
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $conversation = ChatConversation::findOrFail($conversationId);

        // Check authorization
        if (Auth::user()->role === 'mahasiswa' && $conversation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Create message
        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Update conversation last message time
        $conversation->update([
            'last_message_at' => now(),
        ]);

        // Broadcast the message
        broadcast(new MessageSent($message->load('user')))->toOthers();

        return response()->json([
            'message' => $message->load('user'),
        ]);
    }

    /**
     * Assign admin to conversation
     */
    public function assignAdmin(Request $request, $conversationId)
    {
        $conversation = ChatConversation::findOrFail($conversationId);

        if (Auth::user()->role !== 'admin') {
            $conversation->update(['admin_id' => Auth::id()]);
        }

        return response()->json([
            'conversation' => $conversation->load('admin'),
        ]);
    }

    /**
     * Close conversation
     */
    public function closeConversation($conversationId)
    {
        $conversation = ChatConversation::findOrFail($conversationId);

        if (in_array(Auth::user()->role, ['admin', 'teknisi'])) {
            $conversation->update(['status' => 'closed']);
        }

        return response()->json([
            'conversation' => $conversation,
        ]);
    }
}
