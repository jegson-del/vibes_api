<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserBlockEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserBlockEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserBlockEvent::factory(3)->create();
    }
}
