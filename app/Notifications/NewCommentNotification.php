<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    public $ticket;
    public $comment;
    public $commenter;

    /**
     * Create a new notification instance.
     */
    public function __construct($ticket, $comment, $commenter)
    {
        $this->ticket = $ticket;
        $this->comment = $comment;
        $this->commenter = $commenter;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database']; // Simpan ke database
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'comment_id' => $this->comment->id,
            'title' => 'Pesan Baru di Tiket #' . $this->ticket->ticket_code,
            'message' => $this->commenter->username . ' menambahkan pesan baru: "' . \Illuminate\Support\Str::limit($this->comment->message, 50) . '"',
            'url' => route('tickets.show', $this->ticket->id),
            'commenter_name' => $this->commenter->username,
            'commenter_role' => $this->commenter->role,
        ];
    }
}
