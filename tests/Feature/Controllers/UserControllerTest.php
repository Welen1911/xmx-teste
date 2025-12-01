<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_renders_the_user_show_page()
    {
        $user = User::factory()
            ->has(Post::factory()->count(5), 'posts')
            ->has(Comment::factory()->count(8), 'comments')
            ->create();

        $response = $this->get("/user/{$user->id}");

        $response->assertInertia(fn ($page) => 
            $page
                ->component('users/Show')
                ->where('user.id', $user->id)
                ->where('user.first_name', $user->first_name)
                ->where('user.email', $user->email)

                ->where('user.posts_count', 5)
                ->where('user.comments_count', 8)
        );
    }
}
