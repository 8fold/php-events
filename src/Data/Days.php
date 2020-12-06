<?php

namespace Eightfold\Events\Data;

use Eightfold\Foldable\Fold;
use Eightfold\Foldable\Foldable;

use Eightfold\ShoopShelf\Shoop;

use Eightfold\Events\Data\Traits\RootImp;
use Eightfold\Events\Data\Traits\PartsImp;
use Eightfold\Events\Data\Traits\YearImp;
use Eightfold\Events\Data\Traits\MonthImp;

use Eightfold\Events\Data\Day;

// TODO: Use caching mechanism for days??
class Days extends Fold
{
    use RootImp, PartsImp, YearImp, MonthImp;

    static public function fold(...$args): Foldable
    {
        return new static(...$args);
    }

    public function __construct(
        string $root,
        string $year,
        string $month,
        string $date
    )
    {
        $this->root = $root;
        $this->parts = [$year, $month, $date];
    }

    private function path(): string
    {
        return Shoop::this($this->root)->append([
            $this->year(),
            $this->month(),
            $this->date()
        ])->efToString("/");
    }

    public function content(): array
    {
        return Shoop::store($this->path())->content()->each(function($v) {
            $month = Shoop::this($v)->divide("/")->last()->unfold();
            return $this->month($month);
        })->unfold();
    }

    public function month(int $month): Month
    {
        return Month::fold($this->root(), $this->year(), $month);
    }
}
