<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //


public function store(Request $request, Post $post) {
    $request->validate([
        'content' => 'required|string',
        'parent_id' => 'nullable|exists:comments,id',
    ]);

    $comment = Comment::create([
        'user_id' => auth()->id(),
        'post_id' => $post->id,
        'parent_id' => $request->parent_id,
        'content' => $request->content,
    ]);

    // Notify post owner or parent comment user
    if ($request->parent_id) {
        $parent = Comment::find($request->parent_id);
        if ($parent->user_id !== auth()->id()) {
            Notification::create([
                'user_id' => $parent->user_id,
                'type' => 'reply',
                'message' => auth()->user()->username . ' replied to your comment.',
            ]);
        }
    } elseif ($post->user_id !== auth()->id()) {
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'comment',
            'message' => auth()->user()->username . ' commented on your post.',
        ]);
    }

    return response()->json($comment);
}

}
