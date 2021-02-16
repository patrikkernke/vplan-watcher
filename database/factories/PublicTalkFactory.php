<?php

namespace Database\Factories;

use App\Models\PublicTalk;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublicTalkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PublicTalk::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'startAt' => $this->faker->dateTimeThisYear,
            'speaker' => $this->faker->firstName .' '. $this->faker->lastName,
            'congregation' => $this->faker->city,
            'disposition' => $this->faker->unique()->randomNumber(3),
            'topic' => $this->faker->sentence(6)
        ];
    }
}
