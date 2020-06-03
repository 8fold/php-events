<?php

namespace Eightfold\Events;

use Eightfold\Shoop\{
    Shoop,
    ESBool
};

use Eightfold\Events\Data\Interfaces\Path;
use Eightfold\Events\Data\Traits\PathImp;

use Eightfold\Events\Data\Year;
use Eightfold\Events\Data\Month;

class Events implements Path
{
    use PathImp;

    private $years;

    static public function init(string $path): Events
    {
        return new Events($path);
    }

    public function __construct(string $path)
    {
        $this->path = $path;

        $this->years = Shoop::dictionary([]);
    }

    public function events()
    {
        // $directory = new DirectoryIterator($this->path());
        // foreach ($directory as $info) {
        //     if (! $info->isDot() and $info->isDir()) {

        //     }
        // }
    }

    public function year(int $year)
    {
        $member = "i". $year;
        return $this->years()->hasMember(
            $member,
            function($result, $years) use ($year, $member) {
                if ($result) {
                    return $years->get($member);
                }
                return Year::init($this->path()->plus("/". $year));
            });
    }

    public function years()
    {
        if ($this->years->isEmpty) {
            Shoop::string($this->path())->divide("/")->join("/")
                ->pathContent()->each(function($path) {
                    $year = Shoop::string($path)->divide("/")->last()->int;

                    $member = "i". $year;
                    $instance = Year::init($this->path()->plus("/". $year));

                    $this->years = $this->years->plus($instance, $member);
                });
        }
        return $this->years;
    }

    public function dataPaths()
    {
        return Shoop::array([$this->path()]);
    }

    public function uri()
    {
        return $this->path();
    }

    public function yearHasEvents(int $year)
    {
        return $this->year($year)->hasEvents();
    }

    public function monthHasEvents(int $year, int $month): ESBool
    {
        return $this->year($year)->month($month)->hasEvents();
    }

    public function dateHasEvents(int $year, int $month, int $day): ESBool
    {
        return $this->year($year)->month($month)->day($day)->hasEvents();
    }

    public function nextYearWithEvents(int $year): ?Year
    {
        return $this->years()->sortMembers()->each(function($y) use ($year) {
            $isNotGivenYear = $y->year() !== $year;
            $isInFuture = $y->year() > $year;
            $hasEvents = $y->hasEvents()->unfold();
            return ($isNotGivenYear and $isInFuture and $hasEvents) ? $y : "";

        })->noEmpties()->isEmpty(function($result, $futureYears) use ($year) {
            if ($result) {
                return null;
            }
            return $futureYears->first;
        });
    }

    public function previousYearWithEvents(int $year): ?Year
    {
        return $this->years()->sortMembers(false)
            ->each(function($y) use ($year) {
                $isNotGivenYear = $y->year() !== $year;
                $isInPast       = $y->year() < $year;
                $hasEvents      = $y->hasEvents()->unfold();
                return ($isNotGivenYear and $isInPast and $hasEvents) ? $y : "";

            })->noEmpties()->isEmpty(function($result, $pastYears) use ($year) {
                if ($result) {
                    return null;
                }
                return $pastYears->first;
            });
    }

    public function nearestYearWithEvents(int $year): ?Year
    {
        $y = $this->nextYearWithEvents($year);
        if ($y === null) {
            $y = $this->previousYearWithEvents($year);

        } elseif ($y !== null) {
            return $y;

        }
        return $y;
    }

    public function nextMonthWithEvents(int $year, int $month): ?Month
    {
        return $this->year($year)->months()->sortMembers()
            ->each(function($m) use ($month) {
                $isNotGivenYear = $m->month() !== $month;
                $isInFuture = $m->month() > $month;
                $hasEvents = $m->hasEvents();
                return ($isNotGivenYear and $isInFuture and $hasEvents) ? $m : "";

            })->noEmpties()->isEmpty(function($result, $futureMonths) use ($month) {
                if ($result) {
                    return null;
                }
                return $futureMonths->first;
            });
    }

    public function previousMonthWithEvents(int $year, int $month): ?Month
    {
        return $this->year($year)->months()->sortMembers(false)
            ->each(function($m) use ($month) {
                $isNotGivenYear = $m->month() !== $month;
                $isInPast = $m->month() < $month;
                $hasEvents = $m->hasEvents();
                return ($isNotGivenYear and $isInPast and $hasEvents) ? $m : "";

            })->noEmpties()->isEmpty(function($result, $pastMonths) use ($month) {
                if ($result) {
                    return null;

                }
                return $pastMonths->first;
            });
    }

    public function nearestMonthWithEvents(int $year, int $month): ?Month
    {
        if ($this->year($year)->month($month)->hasEvents()->unfold()) {
            return $this->year($year)->month($month);
        }

        $m = $this->nextMonthWithEvents($year, $month);
        if ($m === null) {
            $m = $this->previousMonthWithEvents($year, $month);
        }

        if ($m !== null) {
            return $m;
        }

        $y = $this->nearestYearWithEvents($year);
        if ($y === null) {
            return null;
        }

        if ($y->year() > $year) {
            return $this->year($y->year())->firstMonthWithEvents();
        }
        return $this->year($y->year())->lastMonthWithEvents();
    }
}
