<?php

namespace Eightfold\Events\Data;

use Eightfold\Events\Data\DataAbstract;

use Eightfold\Events\Data\Traits\PartsImp;
use Eightfold\Events\Data\Traits\YearImp;
use Eightfold\Events\Data\Traits\MonthImp;
use Eightfold\Events\Data\Traits\DateImp;

// use Carbon\Carbon;

use Eightfold\ShoopShelf\Shoop;

// use Eightfold\Events\Data\Interfaces\Day as DayInterface;
// use Eightfold\Events\Data\Traits\DayImp;

// use Eightfold\Events\Data\Event;

class Date extends DataAbstract
{
    use PartsImp, YearImp, MonthImp, DateImp;

    public function __construct(string $root, int $year, int $month, int $date)
    {
        $this->root = $root;
        $this->parts = [$year, $month, $date];
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
                    $initial   = Shoop::this($v)->divide("/")->last();
                    $fileName  = $initial->divide(".")->first();
                    $fileParts = $fileName->divide("_");
                    if ($fileParts->first()->startsWith($this->date())->unfold()) {
                        $count = ($fileParts->length()->is(1)->unfold())
                            ? 1
                            : $fileParts->last()->unfold();

                        $k = "i". $fileParts->first()->unfold();
                        $this->content[$k][] = Event::fold(
                            $this->root(),
                            $this->year(),
                            $this->month(false),
                            $this->date(false),
                            $count
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
        return $this->hasEvents();
    }

    public function hasEvents(): bool
    {
        return Shoop::this($this->content())->length()->isGreaterThan(0)->unfold();
    }
}
