<?php

use Illuminate\Database\Seeder;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reviews')->insert(
        [
            'rating' => '5',
            'comment' => 'Great greek food. Must try it!',
            'title' => 'Fantastic food',
            'resto_id' => '1',
            'user_id' => '2',
        ]);
        DB::table('reviews')->insert(
        [
            'rating' => '4',
            'comment' => 'Enjoyable salads and pizzas',
            'title' => 'Very good',
            'resto_id' => '3',
            'user_id' => '2',
        ]);
        DB::table('reviews')->insert(
        [
            'rating' => '5',
            'comment' => 'Great pita wraps, and delicious greek fast food',
            'title' => 'My favourite restaurant',
            'resto_id' => '1',
            'user_id' => '3',
        ]);
        DB::table('reviews')->insert(
        [
            'rating' => '3',
            'comment' => 'It has a great atmosphere, and the food is okay.',
            'title' => 'Good',
            'resto_id' => '5',
            'user_id' => '3',
        ]);
    }
}
