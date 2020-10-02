<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use App\Models\Product;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->text(60),
        'description' => $faker->paragraph(),
        'price' => $faker->numberBetween(10, 9000),
        'manage_stock' => false,
        'in_stock' => $faker->boolean(),
        'slug' => $faker->slug(),
        'sku' => $faker->word(),
        'is_active' => $faker->boolean(),


    ];
});
