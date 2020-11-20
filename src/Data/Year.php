<?php

namespace Eightfold\Events\Data;

use Eightfold\Events\Data\DataAbstract;

use Eightfold\ShoopShelf\Shoop;

use Eightfold\Events\Data\Traits\PartsImp;
use Eightfold\Events\Data\Traits\YearImp;

class Year extends DataAbstract
{
    use PartsImp, YearImp;

    static public function totalMonthsInYear(): int
    {
        return 12;
    }

    public function __construct(string $root, int $year)
    {
        $this->root  = $root;
        $this->parts = [$year];
    }

    public function path(): string
    {
        return Shoop::this($this->root())->divide("/")->append($this->parts())
            ->efToString("/");
    }

    public function content()
    {
        if (Shoop::this($this->content)->length()->efIsEmpty()) {
            Shoop::store($this->path())->content()->each(function($v, $member, &$build) {
                $m = Shoop::this($v)->divide("/")->last();
                $k = $m->prepend("i")->unfold();

                $month = Month::fold($this->root(), $this->year(), $m->efToInteger());

                $this->content[$k] = $month;
            });
        }
        return $this->content;
    }

    public function count(): int
    {}

    public function couldHaveEvents(): bool
    {
        return $this->hasEvents();
    }

    public function hasEvents(): bool
    {
        $hasEvents = false;
        Shoop::this($this->content())->each(
            function($x, $y, $z, &$break) use (&$hasEvents) {
                if ($hasEvents or $x->hasEvents()) {
                    $break = true;
                    $hasEvents = true;

                }
        });
        return $hasEvents;
    }

    public function monthsInYear(): int
    {
        return static::totalMonthsInYear();
    }

    public function is(int $compare): bool
    {
        return (Shoop::this($this->year(false))->is($compare)->unfold())
            ? true
            : false;
    }

    public function isAfter(int $compare): bool
    {
        if ($this->is($compare)) {
            return false;
        }
        return Shoop::this($this->year(false))->isGreaterThan($compare)->unfold();
    }

    public function isBefore(int $compare)
    {
        if ($this->is($compare)) {
            return false;
        }
        return ! $this->isAfter($compare);
    }
}
