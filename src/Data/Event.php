<?php

namespace Eightfold\Events\Data;

use Eightfold\Events\Data\DataAbstract;

use Eightfold\ShoopShelf\Shoop;

use Eightfold\Events\Data\Traits\PartsImp;
use Eightfold\Events\Data\Traits\YearImp;
use Eightfold\Events\Data\Traits\MonthImp;
use Eightfold\Events\Data\Traits\DateImp;

class Event extends DataAbstract
{
    use PartsImp, YearImp, MonthImp, DateImp;

    private $count;

    public function __construct(
        string $root,
        int $year,
        int $month,
        int $date,
        int $count
    )
    {
        $this->root = $root;
        $this->parts = [$year, $month, $date, $count];
    }

    public function path(): string
    {
        $path = Shoop::this($this->root)->divide("/")->append([
            $this->year(),
            $this->month(),
            $this->date() ."_". $this->count() .".event"
        ])->efToString("/");
        if (Shoop::this($path)->divide(".")->first()->endsWith("_1")->unfold() and
            Shoop::store($path)->isFile()->reversed()->unfold()
        ) {
            $path = Shoop::this($path)->divide(".")->first()->dropLast(2)
                ->append(".event")->unfold();

        }
        return $path;
    }

    public function content(): string
    {
        return ($this->hasEvents())
            ? Shoop::store($this->path())->content()
            : "";
    }

    public function count(): int
    {
        return $this->parts[3];
    }

    public function couldHaveEvents(): bool
    {
        return $this->hasEvents();
    }

    public function hasEvents(): bool
    {
        return Shoop::store($this->path())->isFile()->unfold();
    }
}
