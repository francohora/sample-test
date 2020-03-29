<?php
declare(strict_types=1);

namespace App\Services\Validator;

use App\Services\Validator\Interfaces\CurrencyInterface;

final class Currency implements CurrencyInterface
{
    /**
     * @param string $currency
     *
     * @return bool
     */
    public function validate(string $currency): bool
    {
        return !(\in_array($currency, self::VALID_CURRENCY, true) === false);
    }
}
