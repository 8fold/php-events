<?php

declare(strict_types=1);

namespace Eightfold\Events;

// use Eightfold\Foldable\Fold;

// use Carbon\Carbon;

// use Eightfold\ShoopShelf\Shoop;
use Eightfold\FileSystem\Item;

use Eightfold\Events\Data\Years;
// use Eightfold\Events\Data\Year;
// use Eightfold\Events\Data\Month;

use Eightfold\Events\Implementations\Root as RootImp;

class Events // extends Fold
{
    use RootImp;

    private $years;

    public static function fold(string $root): Events
    {
        return new Events($root);
    }

    public function __construct(string $root)
    {
        $this->root = $root;
    }

    public function years(): Years
    {
        if ($this->years === null) {
            $this->years = Years::fold($this->root());
        }
        return $this->years;
    }

    public function year(int $year)
    {
        if ($this->years()->count() === 0) {
            return false;
        }

        $years = $this->years()->content();
        $yearKey = 'i' . $year;

        if (! array_key_exists($yearKey, $years)) {
            return false;
        }

        return  $years[$yearKey];
    }

    public function month(int $year, int $month)
    {
        if (! $this->year($year)) {
            return false;
        }

        if ($month < 10) {
            $month = '0' . $month;
        }
        $monthKey = 'i' . $month;

        $months = $this->year($year)->content();
        if (! array_key_exists($monthKey, $months)) {
            return false;
        }
        return $months[$monthKey];
    }

    /**
     * @deprecated No replacement
     */
    public function date(int $year, int $month, int $date)
    {
        if (! $this->month($year, $month)) {
            return false;
        }

        if ($date < 10) {
            $date = '0' . $date;
        }
        $date = 'i' . $date;

        $dates = $this->month($year, $month)->content();
        if (Shoop::this($dates)->hasAt($date)->reversed()->unfold()) {
            return false;
        }
        return $dates[$date][0];
    }

    public function nextYearWithEvents(int $baseYear)
    {
        $years = [];
        foreach ($this->years()->content() as $year) {
            if ($year->isAfter($baseYear) and $year->hasEvents()) {
                $years[] = $year;
            }
        }

        if (count($years) === 0) {
            return false;
        }

        return $years[0];
    }

    public function previousYearWithEvents(int $baseYear = 0)
    {
        $years = $this->years()->content();
        $years = array_reverse($years);

        $y = [];
        foreach ($years as $year) {
            if ($year->isBefore($baseYear) and $year->hasEvents()) {
                $y[] = $year;
            }
        }

        if (count($y) === 0) {
            return false;
        }
        return $y[0];
    }

    public function nextMonthWithEvents(int $year, int $month)
    {
        $years = $this->years();

        if ($years->count() === 0) {
            return false;
        }

        if ($years->year($year)) {
            $y = $years->year($year);

            $months = [];
            foreach ($y->content() as $m) {
                if ($m->isAfter($month) and $m->hasEvents()) {
                    $months[] = $m;
                }
            }

            if (count($months) === 0) {
                $nextYear = $this->nextYearWithEvents($y->year());
                if ($nextYear) {
                    return $this->nextMonthWithEvents($nextYear->year(), 0);
                }
                return false;
            }
            return $months[0];
        }

        $y = $this->nextYearWithEvents($year);
        if ($y) {
            return $this->nextMonthWithEvents($y->year(), 0);
        }
        return false;
    }

    public function previousMonthWithEvents(int $year, int $month)
    {
        $years = $this->years();

        if ($years->count() === 0) {
            return false;
        }

        if ($years->year($year)) {
            $y = $years->year($year);

            $months = [];
            foreach ($y->content() as $m) {
                if ($m->isBefore($month) and $m->hasEvents()) {
                    $months[] = $m;
                }
            }

            if (count($months) === 0) {
                $previousYear = $this->previousYearWithEvents($year->year());
                if ($previousYear) {
                    return $this->previousYearWithEvents($previousYear->year(), 13);
                }
                return false;
            }

            $months = array_reverse($months);
            return $months[0];
        }

        $years = $years->content();
        $years = array_reverse($years);
        $year  = array_shift($years);
        return $this->previousMonthWithEvents($year->year(), 13);
    }

    /**
     * @deprecated No replacement
     */
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

    /**
     * @deprecated No replacement
     */
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
