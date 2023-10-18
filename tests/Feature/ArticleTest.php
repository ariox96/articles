<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * @return void
     */
    public function test_it_can_show_the_article_in_index_page()
    {
        $user = User::factory()->create();
        Article::factory(3)->create();
        $user->givePermissionTo($this->permissions['index']);

        $this->assertTrue($user->can($this->permissions['index']));

        $this->actingAs($user)
            ->get(route('admin.blog.index'))
            ->assertSuccessful();

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
