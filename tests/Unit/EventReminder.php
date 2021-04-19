<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class EventReminder extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Event::factory(5)
            ->hasAddress()
            ->hasFlyer()
            ->create(['starts' => now()]);

        Event::factory()
            ->hasAddress()
            ->hasFlyer()
            ->create(['starts' => now()->addDays(1)]);

        foreach (Event::all() as $event) {
            Payment::create([
                'event_id' => $event->id,
                'user_id' => User::factory()->create()->id,
                'reference' => Str::uuid(),
                'amount_paid' => rand(1, 100),
                'platform' => 'whiskey',
            ]);
        }
    }

    /** @test */
    public function can_send_daily_email()
    {
        $this->artisan('event:reminder --daily=1')
            ->assertExitCode(0);
    }

    /** @test */
    public function can_send_3days_or_weekly_email()
    {
        $this->artisan('event:reminder')
            ->assertExitCode(0);
    }
}
