<?php

namespace Obokaman\StockForecast\Domain\Model\Financial;

use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CurrencyTest extends WebTestCase
{
    /** @var Currency */
    private $currency;

    /**
     * @test
     * @dataProvider validCurrenciesProvider
     */
    public function shouldAllowCreatingAValidCurrency($currency_code)
    {
        $this->whenITryToCreateAnValidCurrency($currency_code);
        $this->thenIObtainAValidCurrency($currency_code);
    }

    /**
     * @test
     */
    public function shouldConvertCurrencyCodeToUppercase()
    {
        $this->whenITryToCreateAnValidCurrency('eur');
        $this->thenIObtainAValidCurrency('EUR');
    }

    /** @test */
    public function shouldNotAllowCreatingAnInvalidCurrency()
    {
        $this->thenItThrowsAnException();
        $this->whenITryToCreateAnInvalidCurrency();
    }

    private function thenItThrowsAnException()
    {
        $this->expectException(InvalidArgumentException::class);
    }

    private function whenITryToCreateAnInvalidCurrency()
    {
        $this->currency = Currency::fromCode('OBOKAMAN');
    }

    private function whenITryToCreateAnValidCurrency(string $currency_code)
    {
        $this->currency = Currency::fromCode($currency_code);
    }

    private function thenIObtainAValidCurrency(string $currency_code)
    {
        $this->assertEquals($currency_code, (string)$this->currency);
    }

    public function validCurrenciesProvider()
    {
        return [
            ['EUR'],
            ['USD'],
            ['GBP']
        ];
    }
}
