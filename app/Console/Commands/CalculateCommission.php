<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\ThirdParty\BinList\BinListInterface;
use App\Services\ThirdParty\ExchangeRate\ExchangeRateInterface;
use App\Services\Validator\Interfaces\CurrencyInterface;
use Illuminate\Console\Command;

final class CalculateCommission extends Command
{
    protected $description = 'Compute your commission';

    protected $signature = 'commission:compute {file}';

    /**
     * @var \App\Services\ThirdParty\BinList\BinListInterface
     */
    private $binList;

    /**
     * @var \App\Services\Validator\Interfaces\CurrencyInterface
     */
    private $currency;

    /**
     * @var \App\Services\ThirdParty\ExchangeRate\ExchangeRateInterface
     */
    private $exchangeRate;

    public function __construct(
        CurrencyInterface $currency,
        ExchangeRateInterface $exchangeRate,
        BinListInterface $binList
    ) {
        $this->currency = $currency;
        $this->exchangeRate = $exchangeRate;
        $this->binList = $binList;

        parent::__construct();
    }

    public function handle(): int
    {
        $file = $this->argument('file');

        if ($file === null) {
            return 1;
        }

        foreach (\explode("\n", \trim(\file_get_contents($file))) as $row) {
            $rowDetail = \json_decode($row, true);

            $binResult = $this->binList->getAlpha2($rowDetail['bin']);

            $amountFixed = $this->exchangeRate->getRates($rowDetail['currency'], (float)$rowDetail['amount']);

            echo \number_format($amountFixed * ($this->currency->validate($binResult) === true ? 0.01 : 0.02 ), 2) . "\n";
        }


        return 0;
    }
}
