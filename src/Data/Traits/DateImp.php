<?php

namespace Eightfold\Events\Data\Traits;

use Eightfold\Events\Data\Traits\YearImp;

trait DateImp
{
    public function date(bool $asString = true)
    {
        if ($asString) {
            if ($this->parts[2] < 10) {
                return "0". $this->parts[2];
            }
            return (string) $this->parts[2];
        }
        return $this->parts[2];
    }
}
