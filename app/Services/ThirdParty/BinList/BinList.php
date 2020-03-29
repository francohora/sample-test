<?php
declare(strict_types=1);

namespace App\Services\ThirdParty\BinList;

use App\Services\ThirdParty\AbstractThirdParty;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

final class BinList extends AbstractThirdParty implements BinListInterface
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getAlpha2(string $bin): string
    {
        $binResults = $this->request($bin);

        $content = json_decode($binResults->getBody()->getContents(), true);

        return $content['country']['alpha2'];
    }

    public function request(?string $endpoint = null): ResponseInterface
    {
        return $this->client->request('GET', $endpoint);
    }
}
