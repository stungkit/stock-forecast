<?php

namespace Obokaman\StockForecast\Domain\Model\Financial\Stock;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StockTest extends WebTestCase
{
    /** @var Stock */
    private $stock;

    /**
     * @test
     */
    public function shouldConvertStockCodeToUppercase()
    {
        $this->whenITryToCreateAnValidStock('appl');
        $this->thenIObtainAValidStock('APPL');
    }

    private function whenITryToCreateAnValidStock(string $stock_code)
    {
        $this->stock = Stock::fromCode($stock_code);
    }

    private function thenIObtainAValidStock(string $stock_code)
    {
        $this->assertEquals($stock_code, (string)$this->stock);
    }
}
