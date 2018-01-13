<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'title' => $faker->realText(20),
        'intro' => $faker->realText(100),
        'content' => $faker->realText(500),
        'category_id' => App\PostCategory::all()->random()->id,
        'user_id' => App\User::all()->random()->id,
        'created_at'=> $faker->dateTimeBetween($startDate = '-5 months', $endDate = 'now'),
        'updated_at'=>  $faker->dateTimeBetween($startDate = '-4 months', $endDate = 'now'),
        'status' => rand(0,1)
    ];
});
