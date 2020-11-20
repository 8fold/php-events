<?php

namespace Eightfold\Events\UI\Traits;

trait NumbersForMonthImp
{
    private $daysInWeek = 7;
    private $weeksToDisplay = 6;
    private $daysInMonth = 31;

    // public function totalGridItems(): int
    // {
    //     return $this->daysInWeek * $this->weeksToDisplay;
    // }

    // public function totalStartGridBlanks(): int
    // {
    //     return $this->carbon()->copy()->startOfMonth()->dayOfWeek - 1;
    // }

    // public function totalEndGridBlanks(): int
    // {
    //     $totalItems = $this->totalGridItems();
    //     $totalStart = $this->totalStartGridBlanks();
    //     $totalDays = $this->totalDaysInMonth();
    //     return $totalItems - $totalStart - $totalDays;
    // }

    // public function totalDaysInMonth(): int
    // {
    //     return $this->daysInMonth;
    // }
}
