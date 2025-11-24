<?php

namespace Tests\Unit;

use App\Contracts\UniversRepositoryInterface;
use App\Models\Univers;
use App\Services\UniversService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UniversServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_process_univers_for_display_structure()
    {
        $repo = $this->createMock(UniversRepositoryInterface::class);
        $service = new UniversService($repo);

        $univers = new \stdClass;
        $univers->id = 1;
        $univers->name = 'Test Universe';
        $univers->description = 'Ceci est une description de test.';
        $univers->primary_color = '#ff0000';
        $univers->secondary_color = '#00ff00';
        $univers->image = null;
        $univers->logo = null;

        $collection = collect([$univers]);
        $result = $service->processUniversForDisplay($collection);

        $this->assertInstanceOf(Collection::class, $result);
        $first = $result->first();
        $this->assertEquals(1, $first['id']);
        $this->assertArrayHasKey('gradient_header', $first);
        $this->assertStringContainsString('#ff0000', $first['gradient_header']);
        $this->assertArrayHasKey('image_url', $first);
        $this->assertArrayHasKey('logo_url', $first);
    }

    public function test_create_univers_persists_data_and_files()
    {
        Storage::fake('public');

        $repo = $this->createMock(UniversRepositoryInterface::class);
        $service = new UniversService($repo);

        $validatedData = [
            'name' => 'Nouvel Univers',
            'description' => 'Description de test',
            'primary_color' => '#123456',
            'secondary_color' => '#654321',
        ];

        $request = Request::create('/univers', 'POST', $validatedData, [], [
            'image' => UploadedFile::fake()->image('image.jpg'),
            'logo' => UploadedFile::fake()->image('logo.jpg'),
        ]);

        $univers = $service->createUnivers($validatedData, $request);

        $this->assertInstanceOf(Univers::class, $univers);
        $this->assertDatabaseHas('univers', [
            'id' => $univers->id,
            'name' => 'Nouvel Univers',
        ]);

        $this->assertNotNull($univers->image);
        $this->assertNotNull($univers->logo);

        Storage::disk('public')->assertExists($univers->image);
        Storage::disk('public')->assertExists($univers->logo);
    }

    public function test_update_univers_updates_fields()
    {
        Storage::fake('public');

        $repo = $this->createMock(UniversRepositoryInterface::class);
        $service = new UniversService($repo);

        $univers = Univers::factory()->create([
            'name' => 'Ancien',
            'description' => 'Ancienne description',
            'primary_color' => '#000000',
            'secondary_color' => '#ffffff',
        ]);

        $validatedData = [
            'name' => 'Nouveau',
            'description' => 'Nouvelle description',
            'primary_color' => '#111111',
            'secondary_color' => '#222222',
        ];

        $request = Request::create("/univers/{$univers->id}", 'PUT', $validatedData);

        $result = $service->updateUnivers($univers, $validatedData, $request);

        $this->assertTrue($result);

        $univers->refresh();
        $this->assertSame('Nouveau', $univers->name);
        $this->assertSame('#111111', $univers->primary_color);
    }

    public function test_delete_univers_deletes_model_and_files()
    {
        Storage::fake('public');

        $repo = $this->createMock(UniversRepositoryInterface::class);
        $service = new UniversService($repo);

        $univers = Univers::factory()->create([
            'image' => 'univers/images/test.jpg',
            'logo' => 'univers/logos/test_logo.jpg',
        ]);

        Storage::disk('public')->put($univers->image, 'dummy');
        Storage::disk('public')->put($univers->logo, 'dummy');

        $result = $service->deleteUnivers($univers);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('univers', [
            'id' => $univers->id,
        ]);

        Storage::disk('public')->assertMissing($univers->image);
        Storage::disk('public')->assertMissing($univers->logo);
    }
}
