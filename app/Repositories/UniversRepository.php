<?php

namespace App\Repositories;

use App\Contracts\UniversRepositoryInterface;
use App\Models\Univers;
use Illuminate\Support\Collection;

class UniversRepository implements UniversRepositoryInterface
{
    public function all(): Collection
    {
        return Univers::all();
    }

    public function find(int $id): ?Univers
    {
        return Univers::find($id);
    }

    public function findOrFail(int $id): Univers
    {
        return Univers::findOrFail($id);
    }

    public function create(array $data): Univers
    {
        return Univers::create($data);
    }

    public function update(Univers $univers, array $data): bool
    {
        return $univers->update($data);
    }

    public function delete(Univers $univers): bool
    {
        return $univers->delete();
    }

    public function getColorStats(): array
    {
        return Univers::all()->map(function ($univers) {
            return [
                'primary' => $univers->primary_color,
                'secondary' => $univers->secondary_color,
                'name' => $univers->name,
            ];
        })->toArray();
    }
}
