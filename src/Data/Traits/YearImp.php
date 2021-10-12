<?php

namespace Eightfold\Events\Data\Traits;

trait YearImp
{
    public function year(): int
    {
        return $this->parts[0];
    }

    public function yearString(): string
    {
        return strval($this->year());
    }
}
