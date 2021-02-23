<?php

namespace Database\Factories\Schedule\Item;

use App\Models\Schedule\Item\CircuitOverseerTalk;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'startAt' => $this->faker->dateTimeThisYear,
            'circuitOverseer' => 'Uwe Ackermann',
            'topic' => $this->faker->sentence(6)
        ];
    }
}