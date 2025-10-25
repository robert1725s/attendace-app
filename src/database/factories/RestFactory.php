<?php

namespace Database\Factories;

use App\Models\Rest;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestFactory extends Factory
{
    protected $model = Rest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startTime = $this->faker->dateTimeBetween('12:00:00', '13:00:00');
        $endTime = (clone $startTime)->modify('+1 hour');

        return [
            'attendance_id' => 1,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];
    }
}
