<?php

namespace App\Http\Controllers;
use App\Models\Like;

use Illuminate\Http\Request;

class LikeController extends Controller
{
    //

public function toggle(Post $post) {
    $like = $post->likes()->where('user_id', auth()->id())->first();

    if ($like) {
        $like->delete();
    } else {
        Like::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
        ]);

        // Send notification to post owner if not self-like
        if ($post->user_id !== auth()->id()) {
            Notification::create([
                'user_id' => $post->user_id,
                'type' => 'like',
                'message' => auth()->user()->username . ' liked your post.',
            ]);
        }
    }

    return response()->json(['success' => true]);
}

}
