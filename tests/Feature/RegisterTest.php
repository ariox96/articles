<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * @return void
     */
    public function test_a_user_can_register()
    {
        $user = [
            'name' => fake()->name,
            'email' => fake()->unique()->email,
            'password' => fake()->password,
        ];

        $response = $this->post('/post-registration', $user);
        $response->assertRedirect('/dashboard');
        unset($user['password']);
        $this->assertDatabaseHas('users', $user);
    }
}
