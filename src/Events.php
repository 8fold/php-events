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

//     public function nearestMonthWithEvents(int $year, int $month): ?Month
//     {
//         $m = $this->month($year, $month);
//         if ($m) {
//             return $m;
//         }

//         $m = $this->nextMonthWithEvents($year, $month);
//         if ($m) {
//             return $m;
//         }

// var_dump("events");
// die(var_dump(
//     $m
// ));
//             $m = $this->previousMonthWithEvents($year, $month);
//         if ($m !== null) {
//             return $m;
//         }

//         $y = $this->nearestYearWithEvents($year);
//         if ($y === null) {
//             return null;
//         }

//         if ($y->year() > $year) {
//             return $this->year($y->year())->firstMonthWithEvents();
//         }
//         return $this->year($y->year())->lastMonthWithEvents();
//     }
    // public function events()
    // {
    //     // $directory = new DirectoryIterator($this->path());
    //     // foreach ($directory as $info) {
    //     //     if (! $info->isDot() and $info->isDir()) {

    //     //     }
    //     // }
    // }

    // public function year(int $year)
    // {
    //     $member = "i". $year;
    //     return $this->years()->hasMember(
    //         $member,
    //         function($result, $years) use ($year, $member) {
    //             if ($result->unfold()) {
    //                 return $years->get($member);
    //             }
    //             return Year::init($this->path()->plus("/". $year));
    //         });
    // }

    // public function years()
    // {
    //     if ($this->years->isEmpty) {
    //         Shoop::string($this->path())->divide("/")->join("/")
    //             ->pathContent()->each(function($path) {
    //                 $year = Shoop::string($path)->divide("/")->last()->int;
    //                 if ($year > 0) {
    //                     $member = "i". $year;
    //                     $instance = Year::init($this->path()->plus("/". $year));

    //                     $this->years = $this->years->plus($instance, $member);
    //                 }
    //             });
    //     }
    //     return $this->years;
    // }

    // public function dataPaths()
    // {
    //     return Shoop::array([$this->path()]);
    // }

    // public function uri()
    // {
    //     return $this->path();
    // }

    // public function yearHasEvents(int $year)
    // {
    //     return $this->year($year)->hasEvents();
    // }

    // public function monthHasEvents(int $year, int $month): ESBool
    // {
    //     return $this->year($year)->month($month)->hasEvents();
    // }

    // public function dateHasEvents(int $year, int $month, int $day): ESBool
    // {
    //     return $this->year($year)->month($month)->day($day)->hasEvents();
    // }


    // public function nearestYearWithEvents(int $year): ?Year
    // {
    //     $y = $this->nextYearWithEvents($year);
    //     if ($y === null) {
    //         $y = $this->previousYearWithEvents($year);
    //     }

    //     if ($y !== null) {
    //         return $y;
    //     }
    //     return $y;
    // }


}
