<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Comment;

class CommentPosted implements ShouldBroadcast
{
    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function broadcastOn()
    {
        return new Channel('post.' . $this->comment->post_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->comment->id,
            'user' => $this->comment->user->only('id', 'username'),
            'content' => $this->comment->content,
            'post_id' => $this->comment->post_id,
            'parent_id' => $this->comment->parent_id,
            'created_at' => $this->comment->created_at->toDateTimeString(),
        ];
    }
}