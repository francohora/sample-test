<?php
declare(strict_types=1);

namespace App\Services\ThirdParty\ExchangeRate;

use App\Services\ThirdParty\Interfaces\ThirdPartyApiInterface;

interface ExchangeRateInterface extends ThirdPartyApiInterface
{
    public function getRates(string $currency, float $defaultAmount): float;
}
