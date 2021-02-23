<?php

namespace Database\Factories;

use App\Models\Meeting;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeetingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Meeting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'startAt' => $this->faker->dateTimeThisYear,
            'chairman' => $this->faker->boolean
                ? $this->faker->firstName ." ". $this->faker->lastName
                : null,
            'type' => $this->faker->randomElement([
                'Öffentliche Zusammenkunft',
                'Gedächtnismahl',
                'Leben- und Dienstzusammenkunft'
            ]),
        ];
    }
}
