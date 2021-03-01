<?php

namespace Database\Factories;

use App\Models\Meeting;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

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
            'start_at' => $this->faker->dateTimeThisYear,
            'chairman' => $this->faker->boolean
                ? $this->faker->firstName[0] .". ". $this->faker->lastName
                : null,
            'type' => $this->faker->randomElement([
                'Öffentliche Zusammenkunft',
                'Gedächtnismahl',
                'Leben- und Dienstzusammenkunft'
            ]),
        ];
    }

    /**
     * Indicate that the meeting starts at a sunday given weeks before.
     *
     * @param int $weeks
     *
     * @return \Database\Factories\Schedule\Item\MeetingFactory
     */
    public function atWeekFromNow(int $weeks = 0):MeetingFactory
    {
        $weekMethod = $weeks < 0 ? 'subRealDays' : 'addRealDays';
        $weeks = abs($weeks);

        return $this->state(function (array $attributes) use ($weekMethod, $weeks) {
            return [
                'start_at' => now()
                    ->endOfWeek(Carbon::SUNDAY)
                    ->setTime(10, 0, 0, 0)
                    ->$weekMethod($weeks * 7)
                    ->toDateTimestring()
            ];
        });
    }

    /**
     * Indicate that it is a weekend meeting.
     *
     * @return \Database\Factories\Schedule\Item\MeetingFactory
     */
    public function weekendMeeting():MeetingFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'Öffentliche Zusammenkunft'
            ];
        });
    }
}
