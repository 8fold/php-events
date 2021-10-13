<?php

declare(strict_types=1);

namespace Eightfold\Events\Implementations;

trait Year
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
