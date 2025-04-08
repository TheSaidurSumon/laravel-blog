<?php

namespace App\Http\Controllers;
use App\Models\Like;

use Illuminate\Http\Request;

class LikeController extends Controller
{
    //

public function toggle(Post $post) {

        $like = Like::where('user_id', auth()->id())
                    ->where('post_id', $post->id)
                    ->first();

        if ($like) {
            $like->delete();
            
        } else {
            $like = Like::create([
                'user_id' => auth()->id(),
                'post_id' => $post->id,
            ]);
            broadcast(new PostLiked($like))->toOthers();
        }

        return response()->json([
            'liked' => !$like->wasRecentlyCreated,
            'like_count' => $post->likes()->count()
        ]);
    
        // Send notification to post owner if not self-like
        if ($post->user_id !== auth()->id()) {
            Notification::create([
                'user_id' => $post->user_id,
                'type' => 'like',
                'message' => auth()->user()->username . ' liked your post.',
            ]);
        }
        return response()->json(['success' => true]);
    }

   


}
