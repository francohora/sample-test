<?php
declare(strict_types=1);

namespace App\Tests\Unit\Services\Validator;

use App\Exceptions\InvalidCurrencyException;
use App\Services\Validator\Currency;
use App\Tests\TestCase;

/**
 * @covers \App\Services\Validator\Currency
 */
final class CurrencyTest extends TestCase
{
    public function testValidateShouldThrowException(): void
    {
        $this->expectException(InvalidCurrencyException::class);

        $validator = new Currency();

        self::assertFalse($validator->validate('JPN'));
    }

    public function testValidateShouldReturnTrue(): void
    {
        self::assertTrue((new Currency())->validate('AT'));
    }
}
