<?php

namespace Eightfold\Events\Data\Traits;

use Eightfold\Events\Data\Traits\YearImp;

trait MonthImp
{
    use YearImp;

    private $month = 0;

    public function dayString(int $day = 0)
    {
        $day = ($day === 0) ? $this->day : $day;
        if ($day < 10) {
            $day = "0{$day}";
        }
        return "{$day}";
    }
}
