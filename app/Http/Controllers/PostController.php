<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
class PostController extends Controller
{

    public function index()
    {
        $posts = Post::with('user')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
    
        return Inertia::render('Posts/Index', compact('posts'));
    }
    
    public function create()
    {
        return Inertia::render('Posts/Create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'visibility' => 'required|in:public,private',
            'image' => 'nullable|image|max:2048',
        ]);
    
        $data = $request->only(['title', 'content', 'visibility']);
    
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }
    
        auth()->user()->posts()->create($data);
    
        return redirect()->route('posts.index');
    }
    
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
    
        return Inertia::render('Posts/Edit', compact('post'));
    }
    
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);
    
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'visibility' => 'required|in:public,private',
            'image' => 'nullable|image|max:2048',
        ]);
    
        $data = $request->only(['title', 'content', 'visibility']);
    
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }
    
        $post->update($data);
    
        return redirect()->route('posts.index');
    }
    
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
    
        $post->delete();
    
        return redirect()->route('posts.index');
    }
    
}
