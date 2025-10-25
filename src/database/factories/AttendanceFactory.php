<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'work_date' => $this->faker->date(),
            'start_time' => $this->faker->dateTimeBetween('08:00:00', '10:00:00'),
            'end_time' => $this->faker->dateTimeBetween('17:00:00', '19:00:00'),
        ];
    }
}
