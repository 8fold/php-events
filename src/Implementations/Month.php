<?php

declare(strict_types=1);

namespace Eightfold\Events\Implementations;

use Carbon\Carbon;

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
        return Carbon::now()
            ->year($this->year())
            ->month($this->month())
            ->daysInMonth;
    }
}
