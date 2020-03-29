<?php
declare(strict_types=1);

namespace App\Tests\Unit\Services\ThirdParty\ExchangeRate;

use App\Services\ThirdParty\ExchangeRate\ExchangeRate;
use App\Tests\TestCase;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @covers \App\Services\ThirdParty\ExchangeRate\ExchangeRate
 */
final class ExchangeRateTest extends TestCase
{
    public function testGetRatesShouldThrowExceptionOnError(): void
    {
        $this->expectException(\Throwable::class);

        $client = \Mockery::mock(ClientInterface::class);
        $client->shouldReceive('request')->once()->andThrow(\Exception::class);

        $exchangeRate = new ExchangeRate($client, \env('EXCHANGE_RATE_TYPE'));

        $rate = $exchangeRate->getRates('EUR', 10.00);

        self::assertSame(10.00, $rate);
    }

    public function testGetRatesShouldReturnDefaultRate(): void
    {
        $sample = $this->getSampleRates();

        $stream = \Mockery::mock(StreamInterface::class);
        $stream->shouldReceive('getContents')
            ->once()
            ->andReturn(\json_encode($sample));

        $response = \Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->once()->andReturn($stream);

        $client = \Mockery::mock(ClientInterface::class);
        $client->shouldReceive('request')->once()->andReturn($response);

        $exchangeRate = new ExchangeRate($client, \env('EXCHANGE_RATE_TYPE'));

        $rate = $exchangeRate->getRates('EUR', 10.00);

        self::assertSame(10.00, $rate);
    }

    public function testGeRatesShouldCalculateRate(): void
    {
        $sample = $this->getSampleRates();

        $stream = \Mockery::mock(StreamInterface::class);
        $stream->shouldReceive('getContents')
            ->once()
            ->andReturn(\json_encode($sample));

        $response = \Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->once()->andReturn($stream);

        $client = \Mockery::mock(ClientInterface::class);
        $client->shouldReceive('request')->once()->andReturn($response);

        $exchangeRate = new ExchangeRate($client, \env('EXCHANGE_RATE_TYPE'));

        $rate = $exchangeRate->getRates('CAD', 10.00);

        self::assertSame((10.00/$sample['rates']['CAD']), $rate);
    }

    private function getSampleRates(): array
    {
        return [
            'rates' => [
                'CAD' => 1.5521,
                'HKD' => 8.50995,
                'ISK' => 154.0
            ],
            'base' => 'EUR',
            'date' => '2020-03-27'
        ];
    }
}
