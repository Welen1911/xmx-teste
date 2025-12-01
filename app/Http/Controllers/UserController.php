<?php

namespace App\Http\Controllers;

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

    public function posts(User $user): Response
    {
        $posts = $user->posts()->withCount('comments')->latest()->paginate(10);

        return Inertia::render('Welcome', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }
}


