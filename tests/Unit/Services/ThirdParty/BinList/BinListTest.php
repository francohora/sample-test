<?php
declare(strict_types=1);

namespace App\Tests\Unit\Services\ThirdParty\BinList;

use App\Services\ThirdParty\BinList\BinList;
use App\Tests\TestCase;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class BinListTest extends TestCase
{
    public function testGetAlpha2ShouldRetrieveAlpha2(): void
    {
        $sample = $this->sampleReturn();

        $stream = \Mockery::mock(StreamInterface::class);
        $stream->shouldReceive('getContents')
            ->once()
            ->andReturn(\json_encode($sample));

        $response = \Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->once()->andReturn($stream);

        $client = \Mockery::mock(ClientInterface::class);
        $client->shouldReceive('request')
            ->withArgs(static function ($method, $endpoint): bool {
                return $method === 'GET' && $endpoint === '123456';
            })
            ->once()
            ->andReturn($response);

        $binList = new BinList($client);

        $return = $binList->getAlpha2('123456');

        self::assertSame($sample['country']['alpha2'], $return);
    }

    public function testGetAlpha2ShouldThrowExceptionInError(): void
    {
        $this->expectException(\Throwable::class);

        $client = \Mockery::mock(ClientInterface::class);
        $client->shouldReceive('request')
            ->withArgs(static function ($method, $endpoint): bool {
                return $method === 'GET' && $endpoint === '123456';
            })
            ->once()
            ->andThrow(\Exception::class);

        $binList = new BinList($client);

        $binList->getAlpha2('123456');
    }

    private function sampleReturn(): array
    {
        return [
            'number' =>  [
                'length' => 16,
                'luhn' => true
            ],
            'scheme' => 'visa',
            'type' => 'debit',
            'brand' => 'Visa/Dankort',
            'prepaid' => false,
            'country' => [
                'numeric' => 208,
                'alpha2' => 'DK',
                'name' => 'Denmark',
                'emoji' => '🇩🇰',
                'currency' => 'DKK',
                'latitude' => 56,
                'longitude' => 10
            ],
            'bank' => [
                'name' => 'Jyske Bank',
                'url' => 'www.jyskebank.dk',
                'phone' => '+4589893300',
                'city' => 'Hjørring'
            ]
         ];
    }
}
