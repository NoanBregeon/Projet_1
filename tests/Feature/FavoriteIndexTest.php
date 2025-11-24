<?php

namespace Tests\Feature;

use App\Models\Univers;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_favorites_for_authenticated_user()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $univers = Univers::factory()->create();

        $user->favorites()->attach($univers->id);

        $response = $this->actingAs($user)
            ->get(route('favorites.index'));

        $response->assertStatus(200);
        $response->assertViewIs('favorites.index');

        $response->assertViewHas('processedUnivers', function ($collection) use ($univers) {
            return $collection->firstWhere('id', $univers->id) !== null;
        });
    }
}
