<?php

namespace Eightfold\Events;

use Eightfold\Events\UI\Grid as UIGrid;

class Grid
{
    static public function forYear(
        string $path,
        string $class = \Eightfold\Events\UI\GridForYear::class
    )
    {
        return new $class($path);
    }

    static public function forMonth(
        string $path,
        string $class = \Eightfold\Events\UI\GridForMonth::class
    )
    {
        return new $class($path);
    }
}
