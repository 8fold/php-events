<?php

namespace Eightfold\Events\Data;

use Eightfold\Events\Data\DataAbstract;

use Eightfold\FileSystem\Item;

class Year extends DataAbstract
{
    private array $content = [];

    public static function totalMonthsInYear(): int
    {
        return 12;
    }

    public function __construct(string $root, int $year)
    {
        $this->root  = $root;
        $this->parts = [$year];
    }

    public function item(): Item
    {
        if ($this->item === null) {
            $this->item = Item::create($this->root)->append($this->year());
        }
        return $this->item;
    }

    public function path(): string
    {
        return $this->item()->thePath();
    }

    public function content()
    {
        if (count($this->content) === 0) {
            $c = $this->item()->content();

            foreach ($c as $item) {
                $parts = explode('/', $item->thePath());
                $month = array_pop($parts);
                $key   = 'i' . $month;
                if (! isset($this->content[$key])) {
                    $item = Item::create($this->path() .'/'. $month);
                    $this->content[$key] = Month::fromItem($this->root(), $item);

                }
            }
        }
        return $this->content;
    }

    public function count(): int
    {
        return count($this->content());
    }

    public function couldHaveEvents(): bool
    {
        return $this->count() > 0;
    }

    public function hasEvents(): bool
    {
        foreach ($this->content() as $month) {
            if ($month->hasEvents()) {
                return true;

            }
        }
        return false;
    }

// TODO: Test??
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


    public function uri()
    {
        return Shoop::this($this->path())->divide("/")->last()->prepend("/");
    }
}
