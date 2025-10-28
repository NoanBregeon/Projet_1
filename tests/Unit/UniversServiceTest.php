<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\UniversService;
use Illuminate\Support\Collection;
use App\Contracts\UniversRepositoryInterface;

class UniversServiceTest extends TestCase
{
    public function test_process_univers_for_display_structure()
    {
        $repo = $this->createMock(UniversRepositoryInterface::class);
        $service = new UniversService($repo);

        $univers = new \stdClass();
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
}
