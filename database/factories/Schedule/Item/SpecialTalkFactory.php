<?php

namespace Database\Factories\Schedule\Item;

use App\Models\Schedule\Item\SpecialTalk;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpecialTalkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SpecialTalk::class;

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
