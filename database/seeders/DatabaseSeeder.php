<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(PriceType::class);
//        $this->call(EventSeeder::class);
//        $this->call(PaymentSeeder::class);
//        $this->call(TicketSeeder::class);
//        $this->call(UserBlockEventSeeder::class);
    }
}