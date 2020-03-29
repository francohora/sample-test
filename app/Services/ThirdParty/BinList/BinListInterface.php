<?php
declare(strict_types=1);

namespace App\Services\ThirdParty\BinList;

use App\Services\ThirdParty\Interfaces\ThirdPartyApiInterface;

interface BinListInterface extends ThirdPartyApiInterface
{
    public function getAlpha2(string $bin): string;
}
