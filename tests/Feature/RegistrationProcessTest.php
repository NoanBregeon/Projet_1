<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegistrationProcessTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_user_registration_splits_name_and_authenticates()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $user = auth()->user();
        $this->assertEquals('John', $user->first_name);
        $this->assertEquals('Doe', $user->last_name);

        $response->assertRedirect(route('dashboard'));
    }
}
