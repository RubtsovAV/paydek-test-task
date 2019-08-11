<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the accounts for the currency.
     */
    public function accounts()
    {
        return $this->hasMany('App\Account');
    }
}
