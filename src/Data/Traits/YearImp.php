<?php

namespace Eightfold\Events\Data\Traits;

trait YearImp
{
    public function year(bool $asString = true)
    {
        if ($asString) {
            return strval($this->parts[0]);
        }
        return $this->parts[0];
    }
}
