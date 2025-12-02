<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Tag;
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

        $response->assertInertia(
            fn($page) =>
            $page
                ->component('users/Show')
                ->where('user.id', $user->id)
                ->where('user.first_name', $user->first_name)
                ->where('user.email', $user->email)
                ->where('user.posts_count', 5)
                ->where('user.comments_count', 8)
        );
    }

    /** @test */
    public function it_renders_the_user_posts_page()
    {
        $user = User::factory()->create();

        Post::factory()
            ->for($user, 'user')
            ->has(Comment::factory()->count(2), 'comments')
            ->count(3)
            ->create();

        $response = $this->get("/user/{$user->id}/posts");

        $response->assertInertia(function ($page) use ($user) {
            $page
                ->component('Welcome')
                ->where('user.id', $user->id)
                ->where('user.email', $user->email)
                ->has('posts.data', 3)
                ->where('posts.data.0.user_id', $user->id)
                ->where('posts.data.0.comments_count', 2)
                ->has('posts.links');
        });
    }

    /** @test */
    public function it_filters_user_posts_by_title_even_with_inertia_params()
    {
        $user = User::factory()->create();

        $match = Post::factory()->for($user)->create(['title' => 'Meu post legal']);
        $notMatch = Post::factory()->for($user)->create(['title' => 'Outro título']);

        $response = $this->get("/user/{$user->id}/posts?search=legal&isDirty=true&__rememberable=true");

        $response->assertInertia(
            fn($page) =>
            $page
                ->component('Welcome')
                ->has('posts.data', 1)
                ->where('posts.data.0.id', $match->id)
        );
    }

    /** @test */
    public function it_filters_user_posts_by_tag_even_with_inertia_params()
    {
        $user = User::factory()->create();

        $tagA = Tag::factory()->create(['name' => 'Laravel']);
        $tagB = Tag::factory()->create(['name' => 'React']);

        $postLaravel = Post::factory()->for($user)->hasAttached($tagA)->create();
        $postReact = Post::factory()->for($user)->hasAttached($tagB)->create();

        $response = $this->get("/user/{$user->id}/posts?tag=Laravel&processing=false");

        $response->assertInertia(
            fn($page) =>
            $page
                ->component('Welcome')
                ->has('posts.data', 1)
                ->where('posts.data.0.id', $postLaravel->id)
        );
    }

    /** @test */
    public function it_sorts_user_posts_by_likes_desc_even_with_inertia_params()
    {
        $user = User::factory()->create();

        $low = Post::factory()->for($user)->create(['likes' => 5]);
        $high = Post::factory()->for($user)->create(['likes' => 20]);

        $response = $this->get("/user/{$user->id}/posts?likes=desc&page=1&recentlySuccessful=false");

        $response->assertInertia(
            fn($page) =>
            $page
                ->component('Welcome')
                ->has('posts.data', 2)
                ->where('posts.data.0.id', $high->id)
                ->where('posts.data.1.id', $low->id)
        );
    }
}
