<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private const FAKE_PROFILE_NAME = 'fake_profile_pics_name';
    private const FAKE_PROFILE_FULL_NAME = 'fake_profile_pics_name.jpg';

    /** @test */
    public function get_normal_logged_in_user()
    {
        $this->authNormal();

        $response = $this->get(route('api.user.detail'));//->decodeResponseJson();

        $data = $response->json('data');

        $this->assertNotEquals([], $data);
        $this->assertFalse($data['is_admin']);
    }

    /** @test */
    public function get_admin_logged_in_user()
    {
        $this->authAdmin();

        $response = $this->get(route('api.user.detail'));//->decodeResponseJson();

        $data = $response->json('data');

        $this->assertNotEquals([], $data);
        $this->assertTrue($data['is_admin']);
    }

    /** @test */
    public function user_can_upload_profile_pics()
    {
        $this->authNormal();

        $this->fakeUuid(self::FAKE_PROFILE_NAME);
        Storage::fake('avatars');

        $response = $this->post(
            route('api.user.profile_pics'),
            ['image' => UploadedFile::fake()->image('avatar.jpg')]
        )->decodeResponseJson();

        $data = $response->json('data');

        $this->assertEquals(self::FAKE_USER_PRE_PATH . self::FAKE_PROFILE_FULL_NAME, $data['profile_pics']['link']);
    }

    /** @test */
    public function user_profile_pics_will_be_deleted_if_exist()
    {
        Sanctum::actingAs(
            User::factory()
                ->hasProfilePics([
                    'link' => self::FAKE_PROFILE_FULL_NAME
                ])
                ->create(),
            [User::ROLE_SUBSCRIBER]
        );

        $this->fakeUuid(self::FAKE_PROFILE_NAME);
        Storage::fake('avatars');

        $response = $this->post(
            route('api.user.profile_pics'),
            ['image' => UploadedFile::fake()->image('avatar.jpg')]
        )->decodeResponseJson();

        $data = $response->json('data');

        $this->assertEquals(self::FAKE_USER_PRE_PATH . self::FAKE_PROFILE_FULL_NAME, $data['profile_pics']['link']);
    }
}
