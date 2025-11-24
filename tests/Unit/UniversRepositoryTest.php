<?php

namespace Tests\Unit;

use App\Contracts\UniversRepositoryInterface;
use App\Models\Univers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UniversRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_returns_all_univers()
    {
        /** @var UniversRepositoryInterface $repo */
        $repo = app(UniversRepositoryInterface::class);

        Univers::factory()->count(2)->create();

        $all = $repo->all();

        $this->assertCount(2, $all);
    }

    public function test_find_returns_univers_or_null()
    {
        /** @var UniversRepositoryInterface $repo */
        $repo = app(UniversRepositoryInterface::class);

        $univers = Univers::factory()->create();

        $found = $repo->find($univers->id);
        $this->assertNotNull($found);
        $this->assertEquals($univers->id, $found->id);

        $this->assertNull($repo->find(999999));
    }

    public function test_create_persists_univers()
    {
        /** @var UniversRepositoryInterface $repo */
        $repo = app(UniversRepositoryInterface::class);

        $data = Univers::factory()->make()->toArray();

        $created = $repo->create($data);

        $this->assertInstanceOf(Univers::class, $created);
        $this->assertDatabaseHas('univers', [
            'id' => $created->id,
            'name' => $data['name'],
        ]);
    }

    public function test_update_modifies_univers()
    {
        /** @var UniversRepositoryInterface $repo */
        $repo = app(UniversRepositoryInterface::class);

        $univers = Univers::factory()->create([
            'name' => 'Ancien nom',
        ]);

        $repo->update($univers, [
            'name' => 'Nouveau nom',
        ]);

        $this->assertEquals('Nouveau nom', $univers->fresh()->name);
    }

    public function test_delete_removes_univers()
    {
        /** @var UniversRepositoryInterface $repo */
        $repo = app(UniversRepositoryInterface::class);

        $univers = Univers::factory()->create();

        $this->assertTrue($repo->delete($univers));

        $this->assertDatabaseMissing('univers', [
            'id' => $univers->id,
        ]);
    }

    public function test_get_color_stats_returns_expected_structure(): void
    {
        $univers = Univers::factory()->create([
            'primary_color' => '#111111',
            'secondary_color' => '#222222',
            'name' => 'Color Card',
        ]);

        /** @var UniversRepositoryInterface $repo */
        $repo = app(UniversRepositoryInterface::class);

        $stats = $repo->getColorStats();

        $this->assertIsArray($stats);
        $this->assertNotEmpty($stats);

        // Rechercher l'élément correspondant à l'univers créé (l'ordre peut varier)
        $matching = null;
        foreach ($stats as $item) {
            if (! is_array($item)) {
                continue;
            }
            if (isset($item['name']) && $item['name'] === $univers->name) {
                $matching = $item;
                break;
            }
        }

        $this->assertNotNull($matching, "Aucun élément de getColorStats ne correspond au nom attendu '{$univers->name}'.");

        $this->assertArrayHasKey('primary', $matching);
        $this->assertArrayHasKey('secondary', $matching);
        $this->assertArrayHasKey('name', $matching);

        // Vérifier que les couleurs ont bien la forme hex #RRGGBB
        $this->assertMatchesRegularExpression('/^#[A-Fa-f0-9]{6}$/', $matching['primary']);
        $this->assertMatchesRegularExpression('/^#[A-Fa-f0-9]{6}$/', $matching['secondary']);

        // Vérifier que le nom correspond à l'univers créé
        $this->assertSame($univers->name, $matching['name']);
    }
}
