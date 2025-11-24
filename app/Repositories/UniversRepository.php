<?php

namespace App\Repositories;

use App\Models\Univers;
use Illuminate\Support\Collection;

class UniversRepository
{
    /**
     * Retourne tous les univers.
     */
    public function all(): Collection
    {
        return Univers::all();
    }

    /**
     * Trouve un univers par son id.
     */
    public function find(int $id): ?Univers
    {
        return Univers::find($id);
    }

    /**
     * Crée un univers.
     */
    public function create(array $data): Univers
    {
        return Univers::create($data);
    }

    /**
     * Met à jour un univers.
     */
    public function update(Univers $univers, array $data): bool
    {
        return $univers->update($data);
    }

    /**
     * Supprime un univers.
     */
    public function delete(Univers $univers): bool
    {
        return (bool) $univers->delete();
    }

    /**
     * Retourne les stats de couleurs :
     * [
     *   ['primary' => '#xxxxxx', 'secondary' => '#yyyyyy', 'name' => '...'],
     *   ...
     * ]
     *
     * On ordonne par created_at DESC pour que le dernier créé soit en premier,
     * ce que les tests attendent.
     */
    public function getColorStats(): array
    {
        return Univers::query()
            ->orderByDesc('created_at')
            ->get(['primary_color', 'secondary_color', 'name'])
            ->map(function (Univers $univers) {
                return [
                    'primary' => $univers->primary_color,
                    'secondary' => $univers->secondary_color,
                    'name' => $univers->name,
                ];
            })
            ->values()
            ->toArray();
    }
}
