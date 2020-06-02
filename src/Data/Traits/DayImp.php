<?php

namespace Eightfold\Events\Data\Traits;

use Eightfold\Events\Data\Traits\YearImp;

trait DayImp
{
    use MonthImp;

    /**
     * @deprecated
     */
    private $day = 0;

    public function day(): int
    {
        return $this->path()->divide("/")->last()
            ->divide(".", false, 2)->first()
            ->divide("_", false, 2)->first()
            ->int;
    }

    public function dayString()
    {
        if ($this->day() >= 10) {
            return $this->day();
        }
        return "0". $this->day();
    }
}
