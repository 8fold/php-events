<?php

namespace Eightfold\Events\Data\Traits;

use Carbon\Carbon;

use Eightfold\ShoopShelf\Shoop;

trait MonthImp
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
        return Carbon::now()
            ->year($this->year())
            ->month($this->month())
            ->daysInMonth;
    }
}
