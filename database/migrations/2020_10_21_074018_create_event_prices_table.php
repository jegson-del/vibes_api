<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Event;
use App\Models\PriceType;

class CreateEventPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_prices', function (Blueprint $table) {
            $table->foreignIdFor(Event::class)->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignIdFor(PriceType::class)->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->float('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_prices');
    }
}
