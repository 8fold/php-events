<?php

namespace Eightfold\Events\Data\Traits;

use Carbon\Carbon;

use Eightfold\ShoopShelf\Shoop;

trait MonthImp
{
    public function month(bool $asString = true)
    {
        if ($asString) {
            $month = $this->month(false);
            if (Shoop::this($month)->isGreaterThanOrEqualTo(10)->unfold()) {
                return strval($month);
            }
            return "0". $month;
        }
        return $this->parts[1];
    }

    public function daysInMonth(): int
    {
        $carbon = Carbon::now()->year($this->year())->month($this->month());
        return $carbon->daysInMonth;
    }
}
