<?php

namespace Tests\Feature;

use App\Models\Univers;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UniversControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_univers_for_guest()
    {
        $univers = Univers::factory()->create([
            'description' => 'Une description assez longue pour le test de l’index.',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('univers.index');

        $response->assertViewHas('processedUnivers', function ($collection) use ($univers) {
            $item = $collection->firstWhere('id', $univers->id);

            return $item !== null
                && $item['description'] === $univers->description
                && $item['truncated_description'] === $univers->description;
        });
    }

    public function test_index_truncates_description_for_authenticated_user()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $univers = Univers::factory()->create([
            'description' => str_repeat('A', 150),
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);

        $response->assertViewHas('processedUnivers', function ($collection) use ($univers) {
            $item = $collection->firstWhere('id', $univers->id);

            return $item !== null
                && strlen($item['truncated_description']) < strlen($univers->description);
        });
    }

    public function test_store_creates_univers()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $data = [
            'name' => 'Carte Test',
            'description' => 'Description test',
            'primary_color' => '#123456',
            'secondary_color' => '#654321',
        ];

        $files = [
            'image' => UploadedFile::fake()->image('image.jpg'),
            'logo' => UploadedFile::fake()->image('logo.jpg'),
        ];

        $response = $this->actingAs($user)
            ->post(route('univers.store'), array_merge($data, $files));

        $response->assertRedirect('/');
        $this->assertDatabaseHas('univers', [
            'name' => 'Carte Test',
        ]);
    }

    public function test_update_modifies_univers()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $univers = Univers::factory()->create([
            'name' => 'Ancien nom',
        ]);

        $data = [
            'name' => 'Nouveau nom',
            'description' => 'Nouvelle description',
            'primary_color' => '#000000',
            'secondary_color' => '#ffffff',
        ];

        $response = $this->actingAs($user)
            ->put(route('univers.update', $univers), $data);

        $response->assertRedirect(route('univers.edit', $univers));

        $this->assertDatabaseHas('univers', [
            'id' => $univers->id,
            'name' => 'Nouveau nom',
        ]);
    }

    public function test_destroy_deletes_univers()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $univers = Univers::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('univers.destroy', $univers));

        $response->assertRedirect(route('univers.index'));

        $this->assertDatabaseMissing('univers', [
            'id' => $univers->id,
        ]);
    }

    public function test_remove_image_deletes_file_and_clears_field()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $univers = Univers::factory()->create([
            'image' => 'univers/images/test.jpg',
        ]);

        Storage::disk('public')->put($univers->image, 'dummy');

        $response = $this->actingAs($user)
            ->delete(route('univers.remove-image', $univers));

        $response->assertRedirect(route('univers.edit', $univers));

        Storage::disk('public')->assertMissing($univers->image);

        $this->assertNull($univers->fresh()->image);
    }

    public function test_remove_logo_deletes_file_and_clears_field()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $univers = Univers::factory()->create([
            'logo' => 'univers/logos/test_logo.jpg',
        ]);

        Storage::disk('public')->put($univers->logo, 'dummy');

        $response = $this->actingAs($user)
            ->delete(route('univers.remove-logo', $univers));

        $response->assertRedirect(route('univers.edit', $univers));

        Storage::disk('public')->assertMissing($univers->logo);

        $this->assertNull($univers->fresh()->logo);
    }
}
