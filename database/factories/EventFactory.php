<?php

namespace Database\Factories;

use App\Models\Event;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = Carbon::create(
            rand(2020, 2024),
            rand(1, 12),
            rand(1, 28),
            rand(1, 24),
            rand(1, 60),
            0
        );

        return [
            'name' => $this->faker->name,
            'description' => $this->faker->paragraph,
            'starts' => $date->format('Y-m-d H:i:s'),
            'ends' => $date->addWeeks(rand(1, 3))->format('Y-m-d H:i:s'),
            'genre' => 'Pop',
            'status' => Event::ACTIVE,
        ];
    }
}
