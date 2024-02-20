<?php

namespace Tests;

use App\Models\User;

class TestUtils
{
    public static function createUser()
    {
        return User::factory()->create();
    }

    public static function getResourceArray($resourceCollection): array
    {
        $resourceArr = [];
        foreach ($resourceCollection as $resourceInstance) {
            $resourceArr[] = $resourceInstance;
        }

        return $resourceArr;
    }
}
