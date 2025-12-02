<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::query()->withCount('comments')->with('tags');

        if ($request->filled('search')) {
            $query->where('title', 'LIKE', "%{$request->search}%");
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', fn($q) => $q->where('name', $request->tag));
        }

        if ($request->likes === 'asc') {
            $query->orderBy('likes', 'asc');
        }

        if ($request->likes === 'desc') {
            $query->orderBy('likes', 'desc');
        }

        return Inertia::render('Welcome', [
            'posts'   => $query->paginate(30),
            'filters' => $request->only('search', 'tag', 'likes'),
            'tags'    => Tag::pluck('name'),
        ]);
    }


    public function show(Post $post): Response
    {
        $post->load('tags', 'comments.user', 'user');

        return Inertia::render('posts/Show', ['post' => $post]);
    }
}
