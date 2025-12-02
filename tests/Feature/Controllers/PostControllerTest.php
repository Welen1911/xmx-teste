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
    public function it_filters_posts_by_title_search_even_with_inertia_params()
    {
        $matching = Post::factory()->create(['title' => 'Aprendendo Laravel']);
        $notMatching = Post::factory()->create(['title' => 'Outro título']);

        $response = $this->get('/?search=Laravel&__rememberable=true&hasErrors=false&isDirty=true');

        $response->assertInertia(
            fn($page) => $page
                ->component('Welcome')
                ->has('posts.data', 1)
                ->where('posts.data.0.id', $matching->id)
        );
    }

    /** @test */
    public function it_filters_posts_by_tag_even_with_inertia_params()
    {
        $tagLaravel = Tag::factory()->create(['name' => 'Laravel']);
        $tagVue = Tag::factory()->create(['name' => 'Vue']);

        $postLaravel = Post::factory()->hasAttached($tagLaravel)->create();
        $postVue = Post::factory()->hasAttached($tagVue)->create();

        $response = $this->get('/?tag=Laravel&processing=false&wasSuccessful=false');

        $response->assertInertia(
            fn($page) => $page
                ->component('Welcome')
                ->has('posts.data', 1)
                ->where('posts.data.0.id', $postLaravel->id)
        );
    }

    /** @test */
    public function it_sorts_posts_by_likes_descending_even_with_inertia_params()
    {
        $low = Post::factory()->create(['likes' => 10]);
        $high = Post::factory()->create(['likes' => 50]);

        $response = $this->get('/?likes=desc&recentlySuccessful=false');

        $response->assertInertia(
            fn($page) => $page
                ->component('Welcome')
                ->has('posts.data', 2)
                ->where('posts.data.0.id', $high->id)
                ->where('posts.data.1.id', $low->id)
        );
    }

    /** @test */
    public function it_sorts_posts_by_likes_descending()
    {
        $low = Post::factory()->create(['likes' => 10]);
        $high = Post::factory()->create(['likes' => 50]);

        $response = $this->get('/?likes=desc');

        $response->assertInertia(
            fn($page) =>
            $page
                ->component('Welcome')
                ->where('posts.data.0.id', $high->id)
                ->where('posts.data.1.id', $low->id)
        );
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
