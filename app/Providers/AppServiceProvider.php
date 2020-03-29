<?php

namespace App\Providers;

use App\Services\ThirdParty\BinList\BinList;
use App\Services\ThirdParty\BinList\BinListInterface;
use App\Services\ThirdParty\ExchangeRate\ExchangeRate;
use App\Services\ThirdParty\ExchangeRate\ExchangeRateInterface;
use App\Services\Validator\Currency;
use App\Services\Validator\Interfaces\CurrencyInterface;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CurrencyInterface::class, Currency::class);
        $this->app->bind(BinListInterface::class, static function (): BinListInterface {
            $config = [
                'base_uri' => \env('BIN_LIST_URL'),
                'headers' => [
                    'Accept-Version' => 3
                ]
            ];

            return new BinList(new Client($config));
        });

        $this->app->bind(ExchangeRateInterface::class, static function(): ExchangeRateInterface {
            $config = [
                'base_uri' => \env('EXCHANGE_RATE')
            ];

            return new ExchangeRate(new Client($config), \env('EXCHANGE_RATE_TYPE'));
        });
    }
}
