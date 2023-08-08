<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvasionControllerTest extends TestCase
{
    public function test_invalid_invasion_requests_throw_400(): void
    {
        $invaderOne = "Luke Skywalker";
        $invaderTwo = "Darth Vader";
        $invaderThree = "Gandalf";
        $planet = "Tatooine";
        $invalidPlanet = "Middle Earth";
        $responseOne = $this->post('/api/invade', [
            'invaders' => [
                $invaderOne
            ],
            'planet' => $planet
        ]);
        $responseTwo = $this->post('/api/invade', [
            'invaders' => [
                $invaderOne, $invaderOne
            ],
            'planet' => $planet
        ]);
        $responseThree = $this->post('/api/invade', [
            'invaders' => [
                $invaderOne, $invaderTwo
            ],
            'planet' => $invalidPlanet
        ]);
        $responseFour = $this->post('/api/invade', [
            'invaders' => [
                $invaderOne, $invaderTwo, $invaderThree
            ],
            'planet' => $planet
        ]);

        $responseOne->assertStatus(400);
        $responseTwo->assertStatus(400);
        $responseThree->assertStatus(400);
        $responseFour->assertStatus(400);
    }
}
