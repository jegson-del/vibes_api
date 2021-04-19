<?php

namespace Tests\Support\Factories;

use Illuminate\Support\Arr;

class AddressFactory
{
    public static function make(array $data = []): array
    {
        return Arr::collapse([
            [
                'address' => 'No 10',
                'city' => 'city',
                'province' => 'province',
                'post_code' => '00112',
            ],
            $data
        ]);
    }
}
