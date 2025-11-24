<?php

namespace Tests\Feature;

use App\Models\Univers;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteToggleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_toggle_favorite()
    {
        $user = User::factory()->create();
        $univers = Univers::factory()->create();

        $this->actingAs($user);

        // Ajouter aux favoris
        $response = $this->post("/favorites/{$univers->id}/toggle");
        $response->assertJsonFragment(['success' => true]);
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'univers_id' => $univers->id,
        ]);

        // Retirer des favoris
        $response = $this->post("/favorites/{$univers->id}/toggle");
        $response->assertJsonFragment(['success' => true]);
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'univers_id' => $univers->id,
        ]);
    }
}
