<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //admin
        $user = User::updateOrCreate(
            [
                'email' => 'info@vibes.com'
            ],
            [
                'name' => 'vibes',
                'email' => 'info@vibessa.com',
                'email_verified_at' => now(),
                'password' => Hash::make(config('app.admin_password')),
                'remember_token' => Str::random(10),
            ],
        );

        $user->createToken($user->name, [User::ROLE_ADMIN]);

//        User::factory(10)->create();
    }
}
