<?php

namespace App\Services;

use App\Exceptions\DummyJsonException;
use Illuminate\Support\Facades\Http;

class DummyJsonService
{
    protected $baseUrl;
    protected $usersEndpoint;
    protected $postsEndpoint;
    protected $commentsEndpoint;
    protected $limit;

    public function __construct()
    {
        $this->baseUrl = config('services.dummyjson.base_url');
        $this->usersEndpoint = config('services.dummyjson.users_endpoint');
        $this->postsEndpoint = config('services.dummyjson.posts_endpoint');
        $this->commentsEndpoint = config('services.dummyjson.comments_endpoint');
        $this->limit = config('services.dummyjson.limit');
    }

    public function getUsers(): array
    {
        return $this->fetchData($this->usersEndpoint);
    }

    public function getPosts(): array
    {
        return $this->fetchData($this->postsEndpoint);
    }

    public function getComments(): array
    {
        return $this->fetchData($this->commentsEndpoint);
    }

    protected function fetchData($endpoint): array
    {
        try {
            $response = Http::baseUrl($this->baseUrl)
                ->acceptJson()
                ->get($endpoint, [
                    'limit' => $this->limit,
                ])
                ->throw()
                ->json();

            return $response;
        } catch (\Exception $e) {
            throw new DummyJsonException(
                "Falha ao buscar dados da DummyJSON: " . $e->getMessage(),
                previous: $e
            );
        }
    }
}
