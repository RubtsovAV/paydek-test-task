<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 5)->create()->each(function ($user) {
            $user->accounts()->saveMany(
                factory(App\Account::class, rand(1, 3))->make([
                    'user_id' => $user->id
                ])
            );
        });
    }
}
