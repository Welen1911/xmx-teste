<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Tag;
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
            ->hasComments(3)
            ->create([
                'likes' => 10,
                'dislikes' => 1,
            ]);

        $response = $this->get('/'); 

        $response->assertInertia(function ($page) use ($post) {
            $page
                ->component('Welcome')
                ->has('posts.data', 1)
                ->where('posts.data.0.id', fn($value) => $value === $post->id)
                ->where('posts.data.0.title', $post->title)
                ->where('posts.data.0.likes', 10)
                ->where('posts.data.0.dislikes', 1)
                ->where('posts.data.0.comments_count', 3)
                ->has('posts.data.0.tags', 2)
                ->has('posts.links');
        });
    }
}
