<?php

namespace Tests\Unit\Helpers;

use App\Helpers\BulkImporter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BulkImporterTest extends TestCase
{
    use RefreshDatabase;

    protected BulkImporter $bulkImporter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bulkImporter = new BulkImporter();
    }

    /** @test */
    public function it_maps_records_by_external_id()
    {
        $user1 = User::factory()->create(['external_id' => 10]);
        $user2 = User::factory()->create(['external_id' => 20]);

        $result = $this->bulkImporter->mapByExternalId(new User(), [10, 20]);

        $this->assertEquals([
            10 => $user1->id,
            20 => $user2->id,
        ], $result);
    }

    /** @test */
    public function it_returns_empty_array_when_no_external_ids_match()
    {
        User::factory()->count(2)->create();

        $result = $this->bulkImporter->mapByExternalId(new User(), [999, 888]);

        $this->assertEquals([], $result);
    }

    /** @test */
    public function it_upserts_data_correctly()
    {
        $this->bulkImporter->upsert(
            new User(),
            [
                [
                    'external_id' => 1,
                    'first_name' => 'Alice',
                    'last_name' => 'Smith',
                    'email' => 'alice@test.com',
                    'phone' => '1234567890',
                    'image' => 'alice.jpg',
                    'birth_date' => '1990-01-01',
                    'address' => json_encode([
                        'street' => '123 Main St',
                        'city' => 'Anytown',
                        'state' => 'CA',
                        'zip' => '12345',
                    ]),
                    'password' => 'password',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ],
            ['external_id'],
            ['first_name', 'last_name', 'email', 'phone', 'image', 'birth_date', 'address']

        );

        $this->assertDatabaseHas('users', [
            'external_id' => 1,
            'first_name' => 'Alice',
        ]);

        $this->bulkImporter->upsert(
            new User(),
            [
                [
                    'external_id' => 1,
                    'first_name' => 'Alice Updated',
                    'last_name' => 'Smith',
                    'email' => 'alice2@test.com',
                    'phone' => '1234567890',
                    'image' => 'alice.jpg',
                    'birth_date' => '1990-01-01',
                    'address' => json_encode([
                        'street' => '123 Main St',
                        'city' => 'Anytown',
                        'state' => 'CA',
                        'zip' => '12345',
                    ]),
                    'password' => 'newpassword',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ],
            ['external_id'],
            ['first_name', 'last_name', 'email', 'phone', 'image', 'birth_date', 'address']
        );

        $this->assertDatabaseHas('users', [
            'external_id' => 1,
            'first_name' => 'Alice Updated',
            'email' => 'alice2@test.com'
        ]);
    }

    /** @test */
    public function it_wraps_upsert_in_a_database_transaction()
    {
        DB::spy();

        $this->bulkImporter->upsert(
            new User(),
            [
                [
                    'external_id' => 5,
                    'name' => 'Trans Test',
                    'email' => 'trans@test.com',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ],
            ['external_id'],
            ['name']
        );

        DB::shouldHaveReceived('transaction')->once();
    }

    /** @test */
    public function it_applies_timestamps_to_each_row()
    {
        $data = [
            ['external_id' => 1, 'name' => 'A'],
            ['external_id' => 2, 'name' => 'B'],
        ];

        $result = $this->bulkImporter->withTimestamps($data);

        $this->assertCount(2, $result);
        $this->assertArrayHasKey('created_at', $result[0]);
        $this->assertArrayHasKey('updated_at', $result[0]);
        $this->assertNotNull($result[0]['created_at']);
        $this->assertNotNull($result[0]['updated_at']);
    }
}
