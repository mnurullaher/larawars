<?php

namespace Tests;

use App\Models\User;

class TestUtils
{
    public static function createUser() {
        return User::factory()->create();
    }
}
