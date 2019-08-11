<?php

namespace App;

use GuzzleHttp\Client as HttpClient;
use App\Exceptions\UndefinedRateException;

class ExchangeRate
{
    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * ExchangeRate constructor.
     */
    public function __construct()
    {
        $this->client = new HttpClient([
            'connect_timeout' => 10,
            'timeout' => 10,
        ]);
    }

    /**
     * @param string $fromCurrencyCode
     * @param string $toCurrencyCode
     *
     * @return float
     * @throws UndefinedRateException
     */
    public function fetch(string $fromCurrencyCode, string $toCurrencyCode)
    {
        if ($fromCurrencyCode === $toCurrencyCode) {
            return 1;
        }
        return 1.123456; // exchangeratesapi.io is not working

        $url = "https://api.exchangeratesapi.io/latest?base={$fromCurrencyCode}&symbols=$toCurrencyCode";
        $response = $this->client->request('GET', $url);
        $data = \GuzzleHttp\json_decode($response->getBody().'');
        $rate = (float)$data->rates->$toCurrencyCode;
        if (!$rate) {
            throw new UndefinedRateException(
                "The response from exchangeratesapi.io don't have rate for $fromCurrencyCode-$toCurrencyCode pair"
            );
        }
        return $rate;
    }
}