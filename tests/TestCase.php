<?php

namespace Tests;

use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Ramsey\Uuid\Lazy\LazyUuidFromString;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public const FAKE_EVENT_PRE_PATH = 'public/' . File::DIR_EVENT . '/';
    public const FAKE_USER_PRE_PATH = 'public/' . File::DIR_PROFILE . '/';

    public function fakeUuid($uuid)
    {
        Str::createUuidsUsing(function () use ($uuid) {
            return new LazyUuidFromString($uuid);
        });
    }

    public function authNormal()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            [User::ROLE_SUBSCRIBER]
        );
    }

    public function authAdmin()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            [User::ROLE_ADMIN]
        );
    }
}
