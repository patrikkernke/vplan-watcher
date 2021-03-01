<?php

namespace Database\Factories\Schedule\Item;

use App\Models\Schedule\Item\Congress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CongressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Congress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'start_at' => $this->faker->dateTimeThisYear,
            'motto_id' => $this->faker->unique()->randomNumber(3),
            'motto' => $this->faker->sentence(3),
            'part' => $this->faker->randomElement([
                'Freitag',
                'Samstag',
                'Sonntag',
                null
            ])
        ];
    }

    public function atTime(Carbon $time):CongressFactory
    {
        return $this->state(function (array $attributes) use ($time) {
            return [
                'start_at' => $time->toDateTimeString()
            ];
        });
    }
}
