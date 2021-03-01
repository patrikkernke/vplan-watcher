<?php

namespace Database\Factories;

use App\Models\FieldServiceGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class FieldServiceGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FieldServiceGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->city
        ];
    }
}
