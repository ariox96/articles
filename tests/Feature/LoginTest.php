<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * @return void
     */
    public function test_a_user_can_login()
    {
        $user = [
            'name' => fake()->name,
            'email' => fake()->unique()->email,
            'password' => fake()->password,
        ];
        $this->post('/post-registration', $user);
        unset($user['name']);
        $response = $this->post('/post-login', $user);
        $response->assertRedirect(route('article.index'));
        $this->assertAuthenticated();
    }

    /**
     * @return void
     */
    public function test_a_user_cannot_login_with_incorrect_data()
    {
        $user = [
            'email' => 'invalid-email',
            'password' => 'invalid-password',
        ];
        $response = $this->post('/post-login', $user);
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
}
