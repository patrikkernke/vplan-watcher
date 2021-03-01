<?php

namespace Database\Factories\Schedule\Item;

use App\Models\Schedule\Item\CircuitOverseerTalk;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CircuitOverseerTalkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CircuitOverseerTalk::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'start_at' => $this->faker->dateTimeThisYear,
            'circuitOverseer' => 'Uwe Ackermann',
            'topic' => $this->faker->sentence(6)
        ];
    }

    public function atTime(Carbon $time):CircuitOverseerTalkFactory
    {
        return $this->state(function (array $attributes) use ($time) {
            return [
                'start_at' => $time->toDateTimeString()
            ];
        });
    }
}
