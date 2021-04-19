<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use App\Models\UserBlockEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserBlockEventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserBlockEvent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::first()->id,
            'event_id' => Event::first()->id,
        ];
    }
}
