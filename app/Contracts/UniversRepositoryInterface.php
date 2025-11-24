<?php

namespace App\Contracts;

use App\Models\Univers;
use Illuminate\Support\Collection;

interface UniversRepositoryInterface
{
    /**
     * @return Collection<int, Univers>
     */
    public function all(): Collection;

    public function find(int $id): ?Univers;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Univers;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Univers $univers, array $data): bool;

    public function delete(Univers $univers): bool;

    public function findOrFail(int $id): Univers;

    /**
     * @return array<int, array{primary:string,secondary:string,name:string}>
     */
    public function getColorStats(): array;
}
