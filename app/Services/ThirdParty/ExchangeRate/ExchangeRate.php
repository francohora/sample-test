<?php
declare(strict_types=1);

namespace App\Services\ThirdParty\ExchangeRate;

use App\Services\ThirdParty\AbstractThirdParty;
use GuzzleHttp\ClientInterface;

final class ExchangeRate extends AbstractThirdParty implements ExchangeRateInterface
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $currencyType;

    public function __construct(ClientInterface $client, string $currencyType)
    {
        $this->client = $client;
        $this->currencyType = $currencyType;
    }

    public function getRates(string $currency, float $defaultAmount): float
    {
        $rates = \json_decode($this->request($this->currencyType)->getBody()->getContents(), true);

        if ($currency === $rates['base']) {
            return $defaultAmount;
        }

        return $defaultAmount / $rates['rates'][$currency];
    }

    public function request(?string $endpoint = null)
    {
        return $this->client->request('GET', $endpoint);
    }
}
