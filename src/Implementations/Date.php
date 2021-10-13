<?php

declare(strict_types=1);

namespace Eightfold\Events\Implementations;

trait Date
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
