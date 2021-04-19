<?php

namespace Tests\Feature\Admin;

use App\Models\Address;
use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tests\Support\Factories\AddressFactory;
use Tests\Support\Factories\EventFactory;
use Tests\TestCase;
use App\Models\Event;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

//    private const FAKE_EVENT_NAME = 'fake_event_image_name';
//    private const FAKE_UPDATE_EVENT_NAME = 'fake_updated_event_image_name';
//
//    private const FAKE_EVENT_FULL_NAME = self::FAKE_EVENT_NAME . '.jpg';
//    private const FAKE_UPDATE_EVENT_FULL_NAME = self::FAKE_UPDATE_EVENT_NAME . '.jpg';
//
//    public function setUp(): void
//    {
//        parent::setUp();
//
//        $this->authAdmin();
//    }

    /** @test */
    public function admin_can_save_event()
    {
        $this->markTestIncomplete('Add CRUD test for livewire');

        $this->fakeUuid(self::FAKE_EVENT_NAME);
        Storage::fake('avatars');

        $response = $this->post(
            route('api.admin.events.store'),
            Arr::collapse([
                EventFactory::make(),
                AddressFactory::make()
            ])
        )->decodeResponseJson();

        $event = $response->json('data');
        $address = $event['location'];
        $flyer = $event['flyer'];

        Storage::disk('public')->assertExists(File::DIR_EVENT . '/' . self::FAKE_EVENT_FULL_NAME);

        $this->assertEquals(1, File::count());
        $this->assertEquals(self::FAKE_EVENT_PRE_PATH . self::FAKE_EVENT_FULL_NAME, $flyer['link']);

        $this->assertEquals('Test', $event['name']);
        $this->assertEquals('This is a description', $event['description']);
        $this->assertEquals('10000', $event['price']);
        $this->assertEquals('2020-01-02 11:00', $event['starts']);
        $this->assertEquals('2020-01-02 17:00', $event['ends']);
        $this->assertEquals('Pop', $event['genre']);
        $this->assertEquals('No 10', $address['address']);
        $this->assertEquals('city', $address['city']);
        $this->assertEquals('province', $address['province']);
        $this->assertEquals('00112', $address['post_code']);
    }

    /** @test */
    public function admin_can_update_event_image()
    {
        $this->markTestIncomplete('Add CRUD test for livewire');

        $this->fakeUuid(self::FAKE_UPDATE_EVENT_NAME);
        Storage::fake('avatars');

        $this->createEvent();

        $data = Arr::collapse([
            EventFactory::make(['name' => 'Test Updated']),
            AddressFactory::make(['post_code' => '00112U'])
        ]);

        $response = $this->put(
            route('api.admin.events.update', Event::first()->id),
            $data
        )->decodeResponseJson();

        $event = $response->json('data');
        $address = $event['location'];
        $flyer = $event['flyer'];

        Storage::disk('public')->assertMissing(File::DIR_EVENT . '/' . self::FAKE_EVENT_FULL_NAME);
        Storage::disk('public')->assertExists(File::DIR_EVENT . '/' . self::FAKE_UPDATE_EVENT_FULL_NAME);

        $this->assertEquals('Test Updated', $event['name']);
        $this->assertEquals('00112U', $address['post_code']);

        $this->assertEquals(1, File::count());
        $this->assertEquals(self::FAKE_EVENT_PRE_PATH . self::FAKE_UPDATE_EVENT_FULL_NAME, $flyer['link']);
    }

    /** @test */
    public function admin_can_delete_event()
    {
        $this->markTestIncomplete('Add CRUD  test for livewire');

        $this->createEvent();

        $response = $this->delete(
            route('api.admin.events.delete', Event::first()->id)
        )->decodeResponseJson();

        Storage::disk('public')->assertMissing(File::DIR_EVENT . '/fake_event_name.jpg');
        $this->assertEquals(0, Event::count());
        $this->assertEquals(0, Address::count());

        $this->assertEquals('success', $response->json('status'));
    }
}
