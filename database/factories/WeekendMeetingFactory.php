<?php

namespace Database\Factories;

use App\Models\WeekendMeeting;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeekendMeetingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WeekendMeeting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'startAt' => $this->faker->dateTimeThisYear,
            'chairman' => $this->faker->firstName . ' ' . $this->faker->lastName
        ];
    }
}
