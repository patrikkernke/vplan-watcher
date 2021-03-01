<?php

namespace Database\Factories\Schedule\Item;

use App\Models\Schedule\Item\WatchtowerStudy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

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
            'start_at' => $this->faker->dateTimeThisYear,
            'conductor' => $this->faker->firstName[0] .'. '. $this->faker->lastName,
            'reader' => $this->faker->boolean()
                ? $this->faker->firstName[0] .'. '. $this->faker->lastName
                : null,
        ];
    }

    public function atTime(Carbon $time):WatchtowerStudyFactory
    {
        return $this->state(function (array $attributes) use ($time) {
            return [
                'start_at' => $time->toDateTimeString()
            ];
        });
    }
}
