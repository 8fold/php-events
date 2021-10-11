<?php

namespace Eightfold\Events\Data;

use Eightfold\Events\Data\DataAbstract;

use Eightfold\FileSystem\Item;

class Date extends DataAbstract
{
    private array $content = [];

    public function __construct(string $root, int $year, int $month, int $date)
    {
        $this->root = $root;
        $this->parts = [$year, $month, $date];
    }

    public function item(): Item
    {
        if ($this->item === null) {
            $this->item = Item::create($this->root)->append(
                $this->year(),
                $this->month(),
            );
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
                $path     = $item->thePath();
                $p        = explode('/', $path);
                $fileName = array_pop($p);
                if (substr($path, -6) === '.event' and
                    substr($fileName, 0, 2) === $this->date()
                ) {
                    $this->content[$path] =
                        Event::fromItem($this->root(), $item);

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
        return $this->hasEvents();
    }

    public function hasEvents(): bool
    {
        return $this->count() > 0;
    }
}
