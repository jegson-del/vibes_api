<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

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
            'reference' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'amount_paid' => $this->faker->randomNumber(5) . '.00',
            'platform' => $this->faker->sentence,
        ];
    }
}
