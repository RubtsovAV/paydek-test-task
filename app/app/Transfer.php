<?php

namespace App;

use App\Exceptions\NotEnoughMoney;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'rate' => 'decimal:6',
        'amount' => 'decimal:2',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sender_id', 'receiver_id', 'amount'];

    /**
     * Get the sender account.
     */
    public function sender()
    {
        return $this->belongsTo('App\Account');
    }

    /**
     * Get the receiver account.
     */
    public function receiver()
    {
        return $this->belongsTo('App\Account');
    }

    /**
     * @throws Exceptions\UndefinedRateException
     */
    public function refreshRate()
    {
        $fromCurrencyCode = $this->sender->currency->code;
        $toCurrencyCode = $this->receiver->currency->code;

        $this->rate = (new ExchangeRate())->fetch($fromCurrencyCode, $toCurrencyCode);
    }

    /**
     * @param array $options
     *
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     */
    public function save(array $options = [])
    {
        return $this->getConnection()->transaction(function () use ($options) {
            $sender = $this->sender()->lockForUpdate()->first();
            $receiver = $this->receiver()->lockForUpdate()->first();

            if ($sender->balance < $this->amount) {
                throw new NotEnoughMoney(
                    "Account#{$sender->id} have only {$sender->balance}, but {$this->amount} required"
                );
            }

            $sender->balance -= $this->amount;
            $receiver->balance += $this->amount * $this->rate;
            $sender->save();
            $receiver->save();
            return parent::save($options);
        });
    }
}
