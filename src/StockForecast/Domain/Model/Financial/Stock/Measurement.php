<?php

namespace Obokaman\StockForecast\Domain\Model\Financial\Stock;

use DateTimeImmutable;
use Obokaman\StockForecast\Domain\Model\Financial\Currency;

final class Measurement
{
    private $currency;
    private $stock;
    private $timestamp;
    private $close;
    private $high;
    private $low;
    private $open;
    private $volume_from;
    private $volume_to;

    public function __construct(
        Currency $a_currency,
        Stock $a_stock,
        DateTimeImmutable $a_timestamp,
        float $an_open,
        float $a_close,
        float $a_high,
        float $a_low,
        float $a_volume_from,
        float $a_volume_to
    ) {
        $this->currency = $a_currency;
        $this->stock = $a_stock;
        $this->timestamp = $a_timestamp;
        $this->close = $a_close;
        $this->high = $a_high;
        $this->low = $a_low;
        $this->open = $an_open;
        $this->volume_from = $a_volume_from;
        $this->volume_to = $a_volume_to;
    }

    public function currency(): Currency
    {
        return $this->currency;
    }

    public function stock(): Stock
    {
        return $this->stock;
    }

    public function timestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }

    public function close(): float
    {
        return $this->sensitiveRound($this->close);
    }

    public function high(): float
    {
        return $this->sensitiveRound($this->high);
    }

    public function low(): float
    {
        return $this->sensitiveRound($this->low);
    }

    public function volatility(): float
    {
        return $this->sensitiveRound($this->high - $this->low);
    }

    public function open(): float
    {
        return $this->sensitiveRound($this->open);
    }

    public function change(): float
    {
        return $this->sensitiveRound($this->close - $this->open);
    }

    public function changePercent(): float
    {
        return $this->sensitiveRound((($this->close / $this->open) - 1) * 100);
    }

    public function volumeFrom(): float
    {
        return $this->sensitiveRound($this->volume_from);
    }

    public function volumeTo(): float
    {
        return $this->sensitiveRound($this->volume_to);
    }

    public function volume(): float
    {
        return $this->sensitiveRound($this->volume_to - $this->volume_from);
    }

    private function sensitiveRound(float $amount): float
    {
        if (0.001 > abs($amount)) {
            return round($amount, 4);
        }

        if (0.01 > abs($amount)) {
            return round($amount, 3);
        }

        return round($amount, 2);
    }
}
