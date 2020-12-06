<?php

namespace Eightfold\Events;

use Eightfold\Foldable\Fold;

use Carbon\Carbon;

use Eightfold\ShoopShelf\Shoop;

use Eightfold\Events\Data\Years;
use Eightfold\Events\Data\Year;
use Eightfold\Events\Data\Month;

class Events extends Fold
{
    private $years = [];

    public function years(): Years
    {
        if (Shoop::this($this->years)->efIsEmpty()) {
            $this->years = Years::fold($this->main);
        }
        return $this->years;
    }

    public function year(int $year)
    {
        $year  = "i". $year;
        $years = Shoop::this($this->years()->content());

        if ($years->efIsEmpty() or $years->hasAt($year)->reversed()->unfold()) {
            return false;
        }

        $years = $years->unfold();
        return  $years[$year];
    }

    public function month(int $year, int $month)
    {
        if (! $this->year($year)) {
            return false;
        }

        if ($month < 10) {
            $month = "0". $month;
        }
        $month = "i". $month;

        $months = $this->year($year)->content();
        if (Shoop::this($months)->hasAt($month)->reversed()->unfold()) {
            return false;
        }

        return $months[$month];
    }

    public function date(int $year, int $month, int $date)
    {
        if (! $this->month($year, $month)) {
            return false;
        }

        if ($date < 10) {
            $date = "0". $date;
        }
        $date = "i". $date;

        $dates = $this->month($year, $month)->content();
        if (Shoop::this($dates)->hasAt($date)->reversed()->unfold()) {
            return false;
        }

        return $dates[$date][0];
    }

    public function nextYearWithEvents(int $baseYear = 0)
    {
        // TODO: Unfoldable needs a way to discern whether to unfold recursively
        $years = Shoop::this($this->years()->content())->retain(function($year) use ($baseYear) {
            return ($year->isAfter($baseYear) and $year->hasEvents());
        });

        if ($years->length()->efIsEmpty()) {
            return false;
        }

        $years = $years->unfold();
        return $years[0];
    }

    public function previousYearWithEvents(int $year = 0)
    {
        $years = Shoop::this($this->years()->content())->reversed()->retain(function($y) use ($year) {
            return ($y->isBefore($year) and $y->hasEvents());
        });

        if ($years->efIsEmpty()) {
            return false;
        }

        $years = $years->unfold();
        return $years[0];
    }

    public function nextMonthWithEvents(int $year, int $month)
    {
        $year  = "i". $year;
        $years = Shoop::this($this->years()->content());

        if ($years->efIsEmpty()) {
            return false;
        }

        if ($years->hasAt($year)->unfold()) {
            $years = $years->unfold();
            $year  = $years[$year];

            $months = Shoop::this($year->content())->retain(function($m) use ($month) {
                return ($m->isAfter($month) and $m->hasEvents());
            });

            if ($months->efIsEmpty()) {
                $nextYear = $this->nextYearWithEvents($year->year());
                if ($nextYear) {
                    return $this->nextMonthWithEvents($nextYear->year(), 0);
                }
                return false;
            }
            $months = $months->unfold();
            return $months[0];
        }

        $year = Shoop::this($year)->dropFirst()->unfold();
        $y    = $this->nextYearWithEvents($year);
        if (! $y or $y->year() <= $year) {
            return false;
        }
        return $this->nextMonthWithEvents($y->year(), 0);
    }

    public function previousMonthWithEvents(int $year, int $month)
    {
        $year  = "i". $year;
        $years = Shoop::this($this->years()->content());

        if ($years->efIsEmpty()) {
            return false;
        }

        if ($years->hasAt($year)->unfold()) {
            $years = $years->unfold();
            $year  = $years[$year];

            $months = Shoop::this($year->content())->reversed()->retain(function($m) use ($month) {
                return ($m->isBefore($month) and $m->hasEvents());
            });

            if ($months->efIsEmpty()) {
                $previousYear = $this->previousYearWithEvents($year->year());
                if ($previousYear) {
                    return $this->previousMonthWithEvents($previousYear->year(), 13);
                }
                return false;
            }

            $months = $months->unfold();
            return $months[0];
        }

        $years = $years->reversed()->unfold();
        $year  = array_shift($years);
        return $this->previousMonthWithEvents($year->year(), 13);
    }

    public function nearestMonthWithEvents(int $year, int $month): ?Month
    {
        $m = $this->month($year, $month);
        if ($m and $m->hasEvents()) {
            return $m;
        }

        $m = $this->nextMonthWithEvents($year, $month);
        if ($m) {
            return $m;
        }

        $m = $this->previousMonthWithEvents($year, $month);
        if ($m) {
            return $m;
        }

        return null;
    }

    public function nearestYearWithEvents(int $year): ?Year
    {
        $y = $this->year($year);
        if ($y and $y->hasEvents()) {
            return $y;
        }

        $y = $this->nextYearWithEvents($year);
        if ($y) {
            return $y;
        }

        $y = $this->previousYearWithEvents($year);
        if ($y) {
            return $y;
        }

        return null;
    }
}
