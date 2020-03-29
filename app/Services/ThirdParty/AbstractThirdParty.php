<?php
declare(strict_types=1);

namespace App\Services\ThirdParty;

use App\Services\ThirdParty\Interfaces\ThirdPartyApiInterface;

abstract class AbstractThirdParty implements ThirdPartyApiInterface
{
    abstract public function request(?string $endpoint = null);
}
