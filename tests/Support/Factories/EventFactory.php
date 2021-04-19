<?php

namespace Tests\Support\Factories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class EventFactory
{
    public static function make(array $data = []): array
    {
        return Arr::collapse([
            [
                'image' => UploadedFile::fake()->image('avatar.jpg'),
                'name' => 'Test',
                'description' => 'This is a description',
                'price' => '10000',
                'starts' => '2020-01-02 11:00',
                'ends' => '2020-01-02 17:00',
                'genre' => 'Pop',
                'address' => 'No 10',
                'city' => 'city',
                'province' => 'province',
                'post_code' => '00112',
            ],
            $data
        ]);
    }
}
