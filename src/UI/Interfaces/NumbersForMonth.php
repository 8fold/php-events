<?php

namespace Eightfold\Events\UI\Interfaces;

use Carbon\Carbon;

use Eightfold\Events\UI\Interfaces\Numbers;

interface NumbersForMonth extends Numbers
{
    public function totalStartGridBlanks(): int;

    public function totalEndGridBlanks(): int;

    public function totalDaysInMonth(): int;
}
