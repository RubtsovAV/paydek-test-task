<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Account;
use App\Currency;
use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'currency_id' => function () {
            return Currency::inRandomOrder()->first()->id;
        },
        'balance' => $faker->randomFloat(2, 0, 99999999),
    ];
});
