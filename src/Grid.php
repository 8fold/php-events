<?php

namespace Eightfold\Events;

use Eightfold\Events\UI\Grid as UIGrid;

use Eightfold\Events\UI\GridForYear;
use Eightfold\Events\UI\GridForMonth;

class Grid
{
    public static function forYear(
        string $root,
        int $year
    ) {
        return GridForYear::fold($root, $year);
    }

    public static function forMonth(
        string $root,
        int $year,
        int $month
    ) {
        return GridForMonth::fold($root, $year, $month);
    }
}
