<?php

namespace Tests\Unit\Helpers;

use App\Helpers\TagImporter;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagImporterTest extends TestCase
{
    use RefreshDatabase;

    protected TagImporter $importer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->importer = new TagImporter();
    }

    /** @test */
    public function it_imports_new_tags_and_returns_the_map()
    {
        $posts = [
            ['tags' => ['php', 'laravel']],
            ['tags' => ['php', 'vue']],
        ];

        $map = $this->importer->importAndMap($posts);

        $this->assertCount(3, $map);

        $this->assertDatabaseHas('tags', ['name' => 'php']);
        $this->assertDatabaseHas('tags', ['name' => 'laravel']);
        $this->assertDatabaseHas('tags', ['name' => 'vue']);

        $this->assertArrayHasKey('php', $map);
        $this->assertArrayHasKey('laravel', $map);
        $this->assertArrayHasKey('vue', $map);

        $this->assertIsInt($map['php']);
    }

    /** @test */
    public function it_does_not_duplicate_existing_tags()
    {
        Tag::create(['name' => 'php']);

        $posts = [
            ['tags' => ['php', 'laravel']],
        ];

        $map = $this->importer->importAndMap($posts);

        $this->assertDatabaseCount('tags', 2);
        $this->assertArrayHasKey('php', $map);
        $this->assertArrayHasKey('laravel', $map);
    }

    /** @test */
    public function it_returns_only_existing_map_when_no_new_tags_are_found()
    {
        Tag::insert([
            ['name' => 'php'],
            ['name' => 'laravel'],
        ]);

        $posts = [
            ['tags' => ['php', 'laravel']],
        ];

        $map = $this->importer->importAndMap($posts);

        $this->assertDatabaseCount('tags', 2);
        $this->assertEquals([
            'php' => Tag::where('name', 'php')->value('id'),
            'laravel' => Tag::where('name', 'laravel')->value('id'),
        ], $map);
    }

    /** @test */
    public function it_handles_empty_posts_list()
    {
        $map = $this->importer->importAndMap([]);

        $this->assertIsArray($map);
        $this->assertCount(0, $map);
    }

    /** @test */
    public function it_handles_posts_with_empty_tag_lists()
    {
        Tag::create(['name' => 'php']);

        $posts = [
            ['tags' => []],
            ['tags' => []],
        ];

        $map = $this->importer->importAndMap($posts);

        $this->assertCount(1, $map);
        $this->assertArrayHasKey('php', $map);
    }
}
