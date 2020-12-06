<?php

namespace Eightfold\Events\Data;

use Eightfold\Events\Data\DataAbstract;

use Eightfold\ShoopShelf\Shoop;

use Eightfold\Events\Data\Year;

class Years extends DataAbstract
{
    public function __construct(string $root)
    {
        $this->root = $root;
    }

    public function path(): string
    {
        return $this->root();
    }

    public function content()
    {
        if (Shoop::this($this->content)->length()->efIsEmpty()) {
            Shoop::store($this->path())->content()->each(function($v, $m, &$build) {
                $y = Shoop::this($v)->divide("/")->last();
                $k = $y->prepend("i")->unfold();

                $year = Year::fold($this->root(), $y->unfold());

                $this->content[$k] = $year;
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
        return Shoop::store($this->root())->isFolder()->unfold();
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

    public function year(int $year)
    {
        $year = "i". $year;
        if (Shoop::this($this->content())->hasAt($year)->unfold()) {
            return $this->content[$year];
        }
        return false;
    }
}
