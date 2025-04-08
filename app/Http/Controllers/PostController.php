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
            'tags' => 'nullable|array',
        ]);
    
        $data = $request->only(['title', 'content', 'visibility']);
    
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }
     
        auth()->user()->posts()->create($data);
        if ($request->tags) {
            $tagIds = collect($request->tags)->map(function ($name) {
                return \App\Models\Tag::firstOrCreate(['name' => $name])->id;
            });
            $post->tags()->sync($tagIds);
        }
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

        if ($request->tags) {
            $tagIds = collect($request->tags)->map(function ($name) {
                return \App\Models\Tag::firstOrCreate(['name' => $name])->id;
            });
            $post->tags()->sync($tagIds);
        }
        return redirect()->route('posts.index');
    }
    
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
    
        $post->delete();
    
        return redirect()->route('posts.index');
    }
    public function publicPosts(Request $request)
{
    $query = Post::with('user', 'tags')->where('visibility', 'public');

    if ($request->search) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%$search%")
              ->orWhere('content', 'like', "%$search%")
              ->orWhereHas('user', fn($q) => $q->where('username', 'like', "%$search%"))
              ->orWhereHas('tags', fn($q) => $q->where('name', 'like', "%$search%"));
        });
    }

    $posts = $query->latest()->get();

    return Inertia::render('PublicFeed', compact('posts'));
}

    
}
