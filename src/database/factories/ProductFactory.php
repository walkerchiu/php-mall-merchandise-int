<?php

/** @var \Illuminate\Database\Eloquent\Factory  $factory */

use Faker\Generator as Faker;
use WalkerChiu\MallMerchandise\Models\Entities\Product;
use WalkerChiu\MallMerchandise\Models\Entities\ProductLang;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'serial'     => $faker->isbn10,
        'identifier' => $faker->slug,
    ];
});

$factory->define(ProductLang::class, function (Faker $faker) {
    return [
        'code'  => $faker->locale,
        'key'   => $faker->randomElement(['name', 'description']),
        'value' => $faker->sentence,
    ];
});
