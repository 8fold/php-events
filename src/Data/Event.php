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
        if (Shoop::this($path)->divide("/")->reversed()->first()->divide(".")->first()->endsWith("_1")->unfold() and
            Shoop::store($path)->isFile()->reversed()->unfold()
        ) {
            $path = Shoop::this($path)->divide("/")->reversed()->dropFirst()
                ->prepend([$this->date() .".event"])->reversed()->efToString("/");

        }
        return $path;
    }

    public function content(): string
    {
        return ($this->hasEvents())
            ? Shoop::store($this->path())->content()
            : "";
    }

    public function title(): string
    {
        $content = Shoop::this($this->content());
        if ($content->length()->is(0)->unfold()) {
            return "";
        }
        $title = $content->divide("\n\n")->first()->unfold();
        return trim($title);
    }

    public function body(): string
    {
        $content = Shoop::this($this->content());
        if ($content->length()->is(0)->unfold()) {
            return "";
        }
        $title = $content->divide("\n\n", false, 2)->last()->unfold();
        return trim($title);
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
