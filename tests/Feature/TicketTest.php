<?php

namespace Tests\Feature;

use App\Models\PriceType;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Event;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    private Ticket $ticket;
    private User $user;

    private const FAKE_TICKET_ID = 'ticket_id_1';

    public function setUp(): void
    {
        parent::setUp();

        $this->fakeUuid(self::FAKE_TICKET_ID);

        $this->user = User::factory()->create();

        Event::factory()
            ->hasPayments()
            ->create();

        PriceType::factory()->create();

        $this->ticket = Ticket::factory()->create();
    }

    /** @test */
    public function user_can_retrieve_ticket_with_reference()
    {
        $response = $this->get(
            route('api.ticket.show', ['ticket' => $this->ticket->id]),
        )->decodeResponseJson();

        $data = $response->json('data');

        $this->assertEquals($this->ticket->id, $data['id']);
    }

    /** @test */
    public function user_can_update_ticket_owner()
    {
        $this->authNormal();

        $response = $this->patch(
            route('api.ticket.owner'),
            [
                'id' => $this->ticket->id,
                'owner' => 'Test Owner'
            ]
        )->decodeResponseJson();

        $data = $response->json('data');

        $this->assertEquals('Test Owner', $data['owner']);
    }
}
