<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->increments('resto_id');
            $table->string('name');
            $table->string('notes');
            $table->integer('priceRange');
            $table->string('genre');
            $table->string('address');
            $table->string('city');
            $table->string('postalCode');
            $table->string('image')->default('/images/noImage.png');
            $table->decimal('latitude');
            $table->decimal('longitude');
            $table->timestamps();
            
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurants');
    }
}