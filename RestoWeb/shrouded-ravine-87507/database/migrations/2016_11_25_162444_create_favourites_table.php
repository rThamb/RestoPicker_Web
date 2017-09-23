<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavouritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favourites', function (Blueprint $table) {
            $table->increments('fav_id');
            $table->timestamps();
            
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('user_id')->on('users');
            
            $table->integer('resto_id')->unsigned()->index();
            $table->foreign('resto_id')->references('resto_id')->on('restaurants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favourites');
    }
}
