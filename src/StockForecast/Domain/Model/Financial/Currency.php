<?php

namespace Obokaman\StockForecast\Domain\Model\Financial;

use InvalidArgumentException;

use function in_array;

final class Currency
{
    private const VALID_CURRENCIES = ['EUR', 'USD', 'GBP'];
    private $code;

    private function __construct(string $a_code)
    {
        $this->code = $a_code;
    }

    public static function fromCode(string $a_code): Currency
    {
        $a_code = strtoupper($a_code);

        self::assertValidCurrency($a_code);

        return new self($a_code);
    }

    private static function assertValidCurrency(string $a_code): void
    {
        if (!in_array($a_code, self::VALID_CURRENCIES, true)) {
            throw new InvalidArgumentException($a_code . ' is not a valid currency.');
        }
    }

    public function __toString()
    {
        return $this->code;
    }

    public function equals(Currency $a_currency): bool
    {
        return $this->code === $a_currency->code;
    }
}
