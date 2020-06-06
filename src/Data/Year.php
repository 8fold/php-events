<?php

namespace Eightfold\Events\Data;

use Carbon\Carbon;

use Eightfold\Markup\UIKit;
use Eightfold\Shoop\{
    Shoop,
    ESArray,
    ESBool,
    ESDictionary,
    ESInt,
    ESString
};

use Eightfold\Events\Data\Interfaces\Path;
use Eightfold\Events\Data\Interfaces\Year as YearInterface;
use Eightfold\Events\Data\Traits\YearImp;

use Eightfold\Events\Data\Month;

class Year implements Path, YearInterface
{
    use YearImp;

    private $months; // ESDictionary

    static public function init(string $path): Year
    {
        return new Year($path);
    }

    public function __construct(string $path)
    {
        $this->path = $path;

        $this->months = Shoop::dictionary([]);
    }

    public function firstMonthWithEvents(): ?Month
    {
        $month = $this->months()->each(function($month) {
            if ($month->hasEvents()) {
                return $month;
            }
        })->noEmpties();

        if ($month->count === 0) {
            return null;
        }
        return $month->first;
    }

    public function lastMonthWithEvents(): ?Month
    {
        $month = $this->months()->toggle()->each(function($month) {
            if ($month->hasEvents()) {
                return $month;
            }
        })->noEmpties();

        if ($month->count === 0) {
            return null;
        }
        return $month->first;
    }

    public function year()
    {
        return $this->path()->divide("/")->toggle()->first()->int;
    }

    public function month(int $month): Month
    {
        $year   = $this->year();
        $month  = $this->monthString($month);
        $member = "i{$year}{$month}";
        $cached = $this->months()->{$member};
        if ($cached === null) {
            return Month::init($this->path()->plus("/{$month}"));
        }
        return $cached;
    }

    public function months(): ESDictionary
    {
        if ($this->months->isEmpty) {
            $this->path()->pathContent()->each(function($path) {
                $path = Shoop::string($path);
                $year = $this->year();
                $month = $path->divide("/")->last;

                $member   = "i{$year}{$month}";
                $instance = Month::init($path);

                $doesNotHaveMember = $this->months->hasMember($member)->not;
                $hasEvents = $instance->hasEvents()->unfold();
                if ($doesNotHaveMember and $hasEvents) {
                    $this->months = $this->months->plus($instance, $member);
                }
            });
        }
        return $this->months;
    }

    public function hasEvents(): ESBool
    {
        return $this->totalEvents()->isGreaterThan(0);
    }

    public function totalEvents(): ESInt
    {
        return $this->events()->count();
    }

    public function events(): ESArray
    {
        $events = Shoop::array([]);
        $this->months()->each(function($month, $timestamp) use (&$events) {
            $events = $events->plus(...$month->events());
        });
        return $events;
    }

    public function dataPaths()
    {
        return $this->path()->divide("/")->join("/")->pathContent()
            ->each(function($path) {
                $tail = $this->uri()->divide("/")->last();
                $startsWithTail = Shoop::string($tail)->divide("/")->last()
                    ->startsWithUnfolded($tail);
                if ($startsWithTail) {
                    return $path;
                }
                return "";
            })->noEmpties();
    }

    public function uri(): ESString
    {
        return $this->path()->divide("/")->last()->start("/");
    }
}
