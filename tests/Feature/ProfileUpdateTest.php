<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_information_can_be_updated_and_email_verification_behavior()
    {
        $user = User::factory()->create([
            'first_name' => 'Old',
            'last_name' => 'Name',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user);

        // Mise à jour du nom (même email) -> email_verified_at reste non null
        $response = $this->patch('/profile', [
            'name' => 'New Name',
            'email' => $user->email,
        ]);

        $response->assertSessionHasNoErrors()->assertRedirect('/profile');
        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
        $this->assertEquals('New', $user->first_name);
        $this->assertEquals('Name', $user->last_name);

        // Changement d'email -> email_verified_at doit devenir null
        $response = $this->patch('/profile', [
            'name' => 'New Name',
            'email' => 'newemail@example.com',
        ]);

        $response->assertSessionHasNoErrors()->assertRedirect('/profile');
        $user->refresh();
        $this->assertNull($user->email_verified_at);
        $this->assertEquals('newemail@example.com', $user->email);
    }
}
