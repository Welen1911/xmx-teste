<?php

namespace Tests\Unit\Helpers;

use App\Helpers\PostPivotSyncHelper;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PostPivotSyncHelperTest extends TestCase
{
    use RefreshDatabase;

    protected PostPivotSyncHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->helper = new PostPivotSyncHelper();
    }

    /** @test */
    public function it_syncs_post_tag_pivot_correctly()
    {
        $post1 = Post::factory()->create();
        $post2 = Post::factory()->create();

        $tagTech = Tag::factory()->create();
        $tagLaravel = Tag::factory()->create();
        $tagPhp = Tag::factory()->create();
        $tagOld = Tag::factory()->create();

        $postMap = [
            10 => $post1->id,
            20 => $post2->id,
        ];

        $tagMap = [
            'tech'    => $tagTech->id,
            'laravel' => $tagLaravel->id,
            'php'     => $tagPhp->id,
        ];

        DB::table('post_tag')->insert([
            ['post_id' => $post1->id, 'tag_id' => $tagOld->id],
        ]);

        $posts = [
            ['id' => 10, 'tags' => ['tech', 'laravel']],
            ['id' => 20, 'tags' => ['php']],
        ];

        $this->helper->sync($posts, $postMap, $tagMap);

        $this->assertDatabaseMissing('post_tag', [
            'post_id' => $post1->id,
            'tag_id'  => $tagOld->id,
        ]);

        $this->assertDatabaseHas('post_tag', [
            'post_id' => $post1->id,
            'tag_id'  => $tagTech->id,
        ]);

        $this->assertDatabaseHas('post_tag', [
            'post_id' => $post1->id,
            'tag_id'  => $tagLaravel->id,
        ]);

        $this->assertDatabaseHas('post_tag', [
            'post_id' => $post2->id,
            'tag_id'  => $tagPhp->id,
        ]);

        $this->assertEquals(3, DB::table('post_tag')->count());
    }

    /** @test */
    public function it_ignores_posts_not_present_in_the_post_map()
    {
        $post = Post::factory()->create();
        $tag = Tag::factory()->create();

        $postMap = [10 => $post->id];
        $tagMap = ['tech' => $tag->id, 'php' => $tag->id];

        $posts = [
            ['id' => 10, 'tags' => ['tech']],
            ['id' => 99, 'tags' => ['php']],
        ];

        $this->helper->sync($posts, $postMap, $tagMap);

        $this->assertDatabaseHas('post_tag', [
            'post_id' => $post->id,
            'tag_id'  => $tag->id,
        ]);

        $this->assertDatabaseCount('post_tag', 1);
    }

    /** @test */
    public function it_deletes_all_pivots_for_a_post_when_payload_tags_are_empty()
    {
        $post = Post::factory()->create();
        $tagOld = Tag::factory()->create();

        DB::table('post_tag')->insert([
            ['post_id' => $post->id, 'tag_id' => $tagOld->id],
        ]);

        $posts = [
            ['id' => 10, 'tags' => []],
        ];

        $postMap = [10 => $post->id];
        $tagMap = [];

        $this->helper->sync($posts, $postMap, $tagMap);

        $this->assertDatabaseMissing('post_tag', [
            'post_id' => $post->id,
            'tag_id'  => $tagOld->id,
        ]);

        $this->assertDatabaseCount('post_tag', 0);
    }

    /** @test */
    public function it_does_nothing_when_payload_posts_is_empty()
    {
        $post = Post::factory()->create();
        $tag = Tag::factory()->create();

        DB::table('post_tag')->insert([
            ['post_id' => $post->id, 'tag_id' => $tag->id],
        ]);

        
        $this->helper->sync([], [], []);

        $this->assertDatabaseHas('post_tag', [
            'post_id' => $post->id,
            'tag_id'  => $tag->id,
        ]);

        $this->assertDatabaseCount('post_tag', 1);
    }
}
