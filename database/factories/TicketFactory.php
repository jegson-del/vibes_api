<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Payment;
use App\Models\PriceType;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'payment_id' => Payment::first()->id,
            'event_id' => Event::first()->id,
            'price_type_id' => PriceType::first()->id,
            'owner' => $this->faker->name,
        ];
    }
}
