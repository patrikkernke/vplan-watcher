<?php

namespace Database\Factories;

use App\Models\WatchtowerStudy;
use Illuminate\Database\Eloquent\Factories\Factory;

class WatchtowerStudyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WatchtowerStudy::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'startAt' => $this->faker->dateTimeThisYear,
            'conductor' => $this->faker->firstName .' '. $this->faker->lastName,
            'reader' => $this->faker->firstName .' '. $this->faker->lastName,
        ];
    }
}
