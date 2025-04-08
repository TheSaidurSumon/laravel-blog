<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //


public function store(Request $request, Post $post) {
    $data = $request->validate([
        'post_id' => 'required|exists:posts,id',
        'content' => 'required',
        'parent_id' => 'nullable|exists:comments,id',
    ]);
    $comment = Comment::create([
        'user_id' => auth()->id(),
        'post_id' => $data['post_id'],
        'parent_id' => $data['parent_id'] ?? null,
        'content' => $data['content'],
    ]);

    $comment->load('user');

    broadcast(new CommentPosted($comment))->toOthers();

    return response()->json($comment);

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
public function destroy(Comment $comment)
{
    $this->authorize('delete', $comment); // Add policy

    $comment->delete();

    return response()->json(['message' => 'Comment deleted']);
}
}
