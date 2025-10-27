<?php

namespace App\Contracts;

use App\Models\Univers;
use Illuminate\Support\Collection;

interface UniversRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Univers;
    public function create(array $data): Univers;
    public function update(Univers $univers, array $data): bool;
    public function delete(Univers $univers): bool;
    public function findOrFail(int $id): Univers;
    public function getColorStats(): array;
}
