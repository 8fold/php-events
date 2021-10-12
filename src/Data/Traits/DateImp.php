<?php

namespace Eightfold\Events\Data\Traits;

use Eightfold\Events\Data\Traits\YearImp;

trait DateImp
{
    public function date(): int
    {
        return $this->parts[2];
    }

    public function dateString(): string
    {
        $d = $this->date();
        if ($d < 10) {
            return '0' . $d;
        }
        return strval($d);
    }
}
