<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class FillCurrenciesTable extends Migration
{
    const CURRENCY_CODES = ['USD', 'EUR', 'GBP', 'RON'];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (static::CURRENCY_CODES as $i => $code) {
            DB::table('currencies')->insert([
                'id' => $i + 1,
                'code' => $code,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('currencies')->whereIn('code', static::CURRENCY_CODES)->delete();
    }
}
