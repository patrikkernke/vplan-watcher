<?php

namespace Database\Factories;

use App\Models\FieldServiceGroup;
use App\Models\ServiceMeeting;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceMeetingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceMeeting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'startAt' => $this->faker->dateTimeThisYear,
            'type' => 'congregation',
            'leader' => $this->faker->boolean()
                ? $this->faker->firstName[0] . '. ' . $this->faker->lastName
                : null,
        ];
    }

    /**
     * Indicate that meeting is for congregation.
     *
     * @return ServiceMeetingFactory
     */
    public function forCongregation(): ServiceMeetingFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'congregation',
            ];
        });
    }

    /**
     * Indicate that meeting is for a field service group.
     *
     * @return ServiceMeetingFactory
     */
    public function forFieldServiceGroup(): ServiceMeetingFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'field_service_group',
                'field_service_group_id' => FieldServiceGroup::factory()
            ];
        });
    }

    /**
     * Indicate that meeting is for a field service group.
     *
     * @return ServiceMeetingFactory
     */
    public function forServiceWeek(): ServiceMeetingFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'service_week',
            ];
        });
    }

}
