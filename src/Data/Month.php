<?php

namespace Eightfold\Events\Data;

use Eightfold\Events\Data\DataAbstract;

use Eightfold\ShoopShelf\Shoop;

// use Eightfold\Events\Data\Traits\RootImp;
use Eightfold\Events\Data\Traits\PartsImp;
use Eightfold\Events\Data\Traits\YearImp;
use Eightfold\Events\Data\Traits\MonthImp;

class Month extends DataAbstract
{
    use PartsImp, YearImp, MonthImp;

    public function __construct(string $root, int $year, int $month)
    {
        $this->root = $root;
        $this->parts = [$year, $month];
    }

    public function path(): string
    {
        return Shoop::this($this->root)->divide("/")->append([
            $this->year(),
            $this->month()
        ])->efToString("/");
    }

    public function content()
    {
        if (Shoop::this($this->content)->length()->efIsEmpty()) {
            Shoop::store($this->path())->content()->each(function($v, $member, &$build) {
                if (Shoop::this($v)->endsWith(".event")->unfold()) {
                    $date = Shoop::this($v)->divide("/")->last()
                        ->divide(".")->first()
                        ->divide("_")->first();
                    $d = $date->prepend("i")->unfold();
                    if (Shoop::this($this->content)->hasAt($d)->reversed()->unfold()) {
                        $this->content[$d][] = Date::fold(
                            $this->root(),
                            $this->year(),
                            $this->month(),
                            $date->unfold()
                        );
                    }
                }
            });
        }
        return $this->content;
    }

    public function count(): int
    {
        return Shoop::this($this->content())->count();
    }

    public function couldHaveEvents(): bool
    {
        return Shoop::this($this->count())->isGreaterThan(0)->unfold();
    }

    public function hasEvents(): bool
    {
        $hasEvents = false;
        Shoop::this($this->content())->each(
            function($x, $y, $z, &$break) use (&$hasEvents) {
                if ($hasEvents) {
                    $break = true;
                    $hasEvents = true;

                } else {
                    $results = Shoop::this($x)->each(
                        function($date, $m, $n, &$break) use (&$hasEvents) {
                            if ($date->hasEvents()) {
                                $break = true;
                                $hasEvents = true;

                            }
                    });
                }
        });
        return $hasEvents;
    }

    public function is(int $compare): bool
    {
        return (Shoop::this($this->month(false))->is($compare)->unfold())
            ? true
            : false;
    }

    public function isAfter(int $compare): bool
    {
        if ($this->is($compare)) {
            return false;
        }
        return Shoop::this($this->month(false))->isGreaterThan($compare)->unfold();
    }

    public function isBefore(int $compare)
    {
        if ($this->is($compare)) {
            return false;
        }
        return ! $this->isAfter($compare);
    }

    public function uri()
    {
        return Shoop::this($this->path())->divide("/")->last(2)->asString("/")
            ->prepend("/");
    }
}
