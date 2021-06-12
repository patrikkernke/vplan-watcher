<?php

namespace Database\Factories;

use App\Models\AwaySpeaker;
use Illuminate\Database\Eloquent\Factories\Factory;

class AwaySpeakerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AwaySpeaker::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'dispositions' => collect(['1', '2', '3', '4', '5'])->toJson(),
            'email' => $this->faker->optional()->email,
            'phone' => $this->faker->optional()->phoneNumber,
            'may_give_speak_away' => $this->faker->boolean(75),
            'is_dag' => $this->faker->boolean(10),
        ];
    }
}
