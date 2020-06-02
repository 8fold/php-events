<?php

namespace Eightfold\Events\Data\Traits;

use Eightfold\Events\Data\Traits\PathImp;

trait YearImp
{
    use PathImp;

    private $year  = 0;

    public function monthString(int $month = 0)
    {
        $exists = method_exists($this, "month");
        if ($month === 0 and $exists and $this->month() < 10) {
            return "0". $this->month();

        } elseif ($month === 0 and $exists) {
            return $this->month();

        } elseif ($month < 10) {
            return "0". $month;

        }
        return $month;
    }
}
