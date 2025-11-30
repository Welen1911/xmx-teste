<?php

namespace Tests\Unit\Services;

use App\Exceptions\DummyJsonException;
use App\Services\DummyJsonService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DummyJsonServiceTest extends TestCase
{
    protected DummyJsonService $service;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.dummyjson.base_url', 'https://api.example.com');
        config()->set('services.dummyjson.users_endpoint', '/users');
        config()->set('services.dummyjson.posts_endpoint', '/posts');
        config()->set('services.dummyjson.comments_endpoint', '/comments');
        config()->set('services.dummyjson.limit', 10);

        $this->service = new DummyJsonService();
    }

    /** @test */
    public function it_fetches_users_successfully()
    {
        Http::fake([
            'https://api.example.com/users?limit=10' =>
                Http::response([
                    'users' => [
                        ['id' => 1, 'name' => 'John'],
                    ],
                ], 200),
        ]);

        $response = $this->service->getUsers();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('users', $response);
        $this->assertSame('John', $response['users'][0]['name']);
    }

    /** @test */
    public function it_fetches_posts_successfully()
    {
        Http::fake([
            'https://api.example.com/posts?limit=10' =>
                Http::response([
                    'posts' => [
                        ['id' => 1, 'title' => 'Post title'],
                    ],
                ], 200),
        ]);

        $response = $this->service->getPosts();

        $this->assertArrayHasKey('posts', $response);
        $this->assertSame('Post title', $response['posts'][0]['title']);
    }

    /** @test */
    public function it_fetches_comments_successfully()
    {
        Http::fake([
            'https://api.example.com/comments?limit=10' =>
                Http::response([
                    'comments' => [
                        ['id' => 1, 'body' => 'Nice'],
                    ],
                ], 200),
        ]);

        $response = $this->service->getComments();

        $this->assertArrayHasKey('comments', $response);
        $this->assertSame('Nice', $response['comments'][0]['body']);
    }

    /** @test */
    public function it_throws_dummy_json_exception_on_api_failure()
    {
        Http::fake([
            '*' => Http::response(null, 500),
        ]);

        $this->expectException(DummyJsonException::class);

        $this->service->getUsers();
    }
}
