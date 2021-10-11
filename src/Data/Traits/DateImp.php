<?php

namespace Eightfold\Events\Data\Traits;

use Eightfold\Events\Data\Traits\YearImp;

trait DateImp
{
    public function date(bool $asString = true)
    {
        if ($asString) {
            $date = $this->date(false);
            if ($date >= 10) {
                return (string) $this->parts[2];
            }
            return "0". $this->parts[2];
        }
        return $this->parts[2];
    }
}
