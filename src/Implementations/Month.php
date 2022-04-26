<?php

declare(strict_types=1);

namespace Eightfold\Events\Implementations;

trait Month
{
    public function month(): int
    {
        return $this->parts[1];
    }

    public function monthString(): string
    {
        $m = $this->month();
        if ($m < 10) {
            return '0' . $m;
        }
        return strval($m);
    }

    public function daysInMonth(): int
    {
        return cal_days_in_month(CAL_GREGORIAN, $this->month(), $this->year());
    }
}
