<?php

use App\Models\Like;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PostLiked implements ShouldBroadcast
{
    public $like;

    public function __construct(Like $like)
    {
        $this->like = $like;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('posts');
    }

    public function broadcastWith(): array
    {
        return [
            'post_id' => $this->like->post_id,
            'like_count' => $this->like->post->likes()->count()
        ];
    }
}
