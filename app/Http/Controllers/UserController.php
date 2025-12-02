<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function show(User $user): Response
    {
        $user->loadCount('posts', 'comments');

        return Inertia::render('users/Show', [
            'user' => $user,
        ]);
    }

    public function posts(User $user, Request $request)
    {
        $query = $user->posts()
            ->withCount('comments')
            ->with('tags');

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
            'posts'   => $query->paginate(9)->withQueryString(),
            'filters' => $request->only('search', 'tag', 'likes'),
            'tags'    => Tag::pluck('name'),
            'user'    => $user,
        ]);
    }
}
