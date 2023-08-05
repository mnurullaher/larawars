<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MigrateResourcesCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_migrate_all_resources_data(): void
    {

        $resources = [
            'people' => 82,
            'planets' => 60,
            'vehicles' => 39,
            'starships' => 36
        ];
        Artisan::call('resources:migrate');

        foreach ($resources as $resource => $count) {
            $this->assertDatabaseCount($resource, $count);
        }
    }
}
