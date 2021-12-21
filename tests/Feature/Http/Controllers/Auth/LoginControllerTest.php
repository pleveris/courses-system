<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * testLoginAuthenticateAndRedirect
     *
     * @return void
     */
    public function testLoginAuthenticateAndRedirect()
    {
        $user = factory(User::class)->create();

        $response = $this->post(route('login'), [
            "email" => $user->email,
            "password" => "password",
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * register_and_authenticates
     *
     * @return void
     */
    public function testRegisterAndAuthenticates()
    {
        $name = $this->faker->name;
        $email = $this->faker->safeEmail;
        $password = $this->faker->password(8);

        $response = $this->post('register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email
        ]);
    }
}
