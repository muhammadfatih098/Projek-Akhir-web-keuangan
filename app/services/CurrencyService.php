<?php

class CurrencyService
{
    private string $apiKey = 'fca_live_EUOiz6xOvGwhyXg82XF06EC5BQQQxSJ1nLYQzYJu';
    private string $apiUrl = 'https://api.freecurrencyapi.com/v1/latest';

    private array $symbols = [
        'USD' => '$',
        'MYR' => 'RM',
        'JPY' => '¥',
        'SGD' => 'S$',
        'EUR' => '€',
    ];
    
    public function symbol(string $currency): string
    {
        return $this->symbols[$currency] ?? '';
    }
    
    public function rateToIdr(string $currency): float
    {
        $rates = $this->fetchRates();

        if (!isset($rates['IDR'], $rates[$currency])) {
            return 15000;
        }

        return $rates['IDR'] / $rates[$currency];
    }
    
    public function fromIdr(float $idr, string $currency): float
    {
        $rate = $this->rateToIdr($currency);

        if ($rate <= 0) {
            return 0;
        }

        return $idr / $rate;
    }
    
    private function fetchRates(): array
    {
        static $cache = null;
        if ($cache) return $cache;

        $url = $this->apiUrl
            . '?apikey=' . $this->apiKey
            . '&currencies=USD,MYR,JPY,SGD,EUR,IDR'
            . '&base_currency=USD';

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 10
        ]);

        $res = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($res, true);

        return $cache = $json['data']
            ?? ['USD'=>1,'IDR'=>15000,'MYR'=>3.4,'JPY'=>110,'SGD'=>1.35,'EUR'=>0.9];
    }
}