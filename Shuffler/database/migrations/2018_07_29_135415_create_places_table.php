<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->increments('id');
            $table->string('place_id');
            $table->string('name');
            $table->string('type');
            $table->string('coordinates');
            $table->string('address');
            $table->string('phone');
            $table->string('opening_days');
            $table->string('opens_at');
            $table->string('closes_at');
            $table->integer('rating');
            $table->integer('price_level');
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
        Schema::dropIfExists('places');
    }
}
