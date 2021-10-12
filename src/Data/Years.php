<?php

namespace Eightfold\Events\Data;

use Eightfold\Events\Data\DataAbstract;

// use Eightfold\ShoopShelf\Shoop;

use Eightfold\Events\Data\Year;

use Eightfold\FileSystem\Item;

class Years //extends DataAbstract
{
    private $item;

    private array $content = [];

    static public function fold(...$args): Years
    {
        return new static(...$args);
    }

    public function __construct(string $root)
    {
        $this->root = $root;
    }

    public function path(): string
    {
        return $this->root();
    }

    public function item(): Item
    {
        if ($this->item === null) {
            $this->item = Item::create($this->root);
        }
        return $this->item;
    }

    public function content()
    {
        if (count($this->content) === 0) {
            $c = $this->item()->content();
            foreach ($c as $year) {
                $path  = $year->thePath();
                $parts = explode('/', $path);
                $year  = array_pop($parts);
                $key   = 'i' . $year;

                $this->content[$key] = Year::fold($this->root, $year);

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
        foreach ($this->content() as $year) {
            if ($year->hasEvents()) {
                return true;

            }
        }
        return false;
    }

    public function year(int $year)
    {
        $year = "i". $year;
        if ($c = $this->content() and array_key_exists($year, $c)) {
            return $c[$year];
        }
        return false;
    }
}
