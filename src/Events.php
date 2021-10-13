<?php

declare(strict_types=1);

namespace Eightfold\Events;

// use Eightfold\Foldable\Fold;

// use Carbon\Carbon;

// use Eightfold\ShoopShelf\Shoop;
use Eightfold\FileSystem\Item;

use Eightfold\Events\Data\Years;
use Eightfold\Events\Data\Year;
use Eightfold\Events\Data\Month;
use Eightfold\Events\Data\Date;

use Eightfold\Events\Implementations\Root as RootImp;

class Events // extends Fold
{
    use RootImp;

    /**
     * @var Years
     */
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

    /**
     * @return Year|bool|boolean        [description]
     */
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

    /**
     * @return Month|bool|boolean        [description]
     */
    public function month(int $year, int $month)
    {
        $y = $this->year($year);
        if (! is_object($y)) {
            return false;
        }

        if ($month < 10) {
            $month = '0' . $month;
        }
        $monthKey = 'i' . $month;

        $months = $y->content();
        if (! array_key_exists($monthKey, $months)) {
            return false;
        }
        return $months[$monthKey];
    }

    /**
     * @return Date|bool|boolean        [description]
     */
    public function date(int $year, int $month, int $date)
    {
        $m = $this->month($year, $month);
        if (! is_object($m)) {
            return false;
        }

        if ($date < 10) {
            $date = '0' . $date;
        }
        $dateKey = 'i' . $date;

        $dates = $m->content();
        if (! array_key_exists($dateKey, $dates)) {
            return false;
        }
        return $dates[$dateKey];
    }

    /**
     * @return Year|bool|boolean        [description]
     */
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

    /**
     * @return Year|bool|boolean        [description]
     */
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

    /**
     * @return Month|bool|boolean        [description]
     */
    public function nextMonthWithEvents(int $year, int $month)
    {
        $years = $this->years();

        if ($years->count() === 0) {
            return false;
        }

        if ($y = $years->year($year) and is_object($y)) {
            $months = [];
            foreach ($y->content() as $m) {
                if ($m->isAfter($month) and $m->hasEvents()) {
                    $months[] = $m;
                }
            }

            // Check if a following year has events.
            if (count($months) === 0) {
                if (
                    $nextYear = $this->nextYearWithEvents($y->year()) and
                    is_object($nextYear)
                ) {
                    // Return a month from a follow-on year.
                    return $this->nextMonthWithEvents($nextYear->year(), 0);
                }
                return false;
            }
            return $months[0];
        }

        $y = $this->nextYearWithEvents($year);
        if (is_object($y)) {
            return $this->nextMonthWithEvents($y->year(), 0);
        }
        return false;
    }

    /**
     * @return Month|bool|boolean        [description]
     */
    public function previousMonthWithEvents(int $year, int $month)
    {
        $years = $this->years();

        if ($years->count() === 0) {
            return false;
        }

        if ($y = $years->year($year) and is_object($y)) {
            $months = [];
            foreach ($y->content() as $m) {
                if ($m->isBefore($month) and $m->hasEvents()) {
                    $months[] = $m;
                }
            }

            // Check if there is a previous year with events.
            if (count($months) === 0) {
                if (
                    $previousYear = $this->previousYearWithEvents($y->year()) and
                    is_object($previousYear)
                ) {
                    // Return a month from a previous year.
                    return $this->previousMonthWithEvents($previousYear->year(), 13);
                }
                return false;
            }

            $months = array_reverse($months);
            return $months[0];
        }

        $years = $years->content();
        $years = array_reverse($years);
        $year  = array_shift($years);
        if (is_object($year)) {
            return $this->previousMonthWithEvents($year->year(), 13);
        }
        return false;
    }

    /**
     * @deprecated No replacement
     */
    // public function nearestMonthWithEvents(int $year, int $month): ?Month
    // {
    //     $m = $this->month($year, $month);
    //     if ($m and $m->hasEvents()) {
    //         return $m;
    //     }

    //     $m = $this->nextMonthWithEvents($year, $month);
    //     if ($m) {
    //         return $m;
    //     }

    //     $m = $this->previousMonthWithEvents($year, $month);
    //     if ($m) {
    //         return $m;
    //     }

    //     return null;
    // }

    /**
     * @deprecated No replacement
     */
    // public function nearestYearWithEvents(int $year): ?Year
    // {
    //     $y = $this->year($year);
    //     if ($y and $y->hasEvents()) {
    //         return $y;
    //     }

    //     $y = $this->nextYearWithEvents($year);
    //     if ($y) {
    //         return $y;
    //     }

    //     $y = $this->previousYearWithEvents($year);
    //     if ($y) {
    //         return $y;
    //     }

    //     return null;
    // }
}
