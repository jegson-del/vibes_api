<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PriceType as PriceTypeModel;

class PriceType extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['Standard', 'Regular', 'Vip', 'Vvip'];

        foreach ($types as $type) {
            PriceTypeModel::factory()->create(['type' => $type]);
        }
    }
}
