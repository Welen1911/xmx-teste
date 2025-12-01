<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(): Response
    {
        $posts = Post::select('id', 'title', 'likes', 'dislikes')
        ->withCount('comments')
        ->with('tags')
        ->paginate(30);

        return Inertia::render('Welcome', ['posts' => $posts]);
    }

    public function show(Post $post): Response
    {
        $post->load('tags', 'comments.user', 'user');

        return Inertia::render('posts/Show', ['post' => $post]);
    }
}
