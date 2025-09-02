<?php

namespace Database\Factories;

use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Test>
 */
class TestFactory extends Factory
{
    protected $model = Test::class;

    public function definition()
    {
        return [
            'champ' => $this->faker->text(5),
            'champ2' => $this->faker->name,
        ];
    }
}
