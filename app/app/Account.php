<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'balance' => 'decimal:2',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'balance' => 0,
    ];

    /**
     * Get the user that owns the account.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the currency of this account.
     */
    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }
}
