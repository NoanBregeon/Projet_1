<?php

namespace Database\Seeders;

use App\Models\Univers;
use Illuminate\Database\Seeder;

class UniversSeeder extends Seeder
{
    public function run(): void
    {
        Univers::factory()->count(10)->create();
    }
}
