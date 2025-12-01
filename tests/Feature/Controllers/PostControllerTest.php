<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_renders_the_posts_index_page()
    {
        $post = Post::factory()
            ->has(Tag::factory()->count(2))
            ->has(Comment::factory()->count(3), 'comments')
            ->create([
                'likes' => 10,
                'dislikes' => 1,
            ]);

        $response = $this->get('/');

        $response->assertInertia(function ($page) use ($post) {
            $page
                ->component('Welcome')
                ->has('posts.data', 1)
                ->where('posts.data.0.id', fn($v) => $v === $post->id)
                ->where('posts.data.0.title', $post->title)
                ->where('posts.data.0.likes', 10)
                ->where('posts.data.0.dislikes', 1)
                ->where('posts.data.0.comments_count', 3)
                ->has('posts.data.0.tags', 2)
                ->has('posts.links');
        });
    }

    /** @test */
    public function it_renders_the_posts_show_page()
    {
        $post = Post::factory()
            ->for(User::factory(), 'user')
            ->has(Tag::factory()->count(2))
            ->has(
                Comment::factory()
                    ->count(3)
                    ->for(User::factory(), 'user'),
                'comments'
            )
            ->create();

        $response = $this->get("/posts/{$post->id}");

        $response->assertInertia(function ($page) use ($post) {
            $page
                ->component('posts/Show')

                ->where('post.id', $post->id)
                ->where('post.title', $post->title)

                ->has('post.tags', 2)
                ->has('post.comments', 3)

                ->has('post.comments.0.user')
                ->has('post.user');
        });
    }
}
