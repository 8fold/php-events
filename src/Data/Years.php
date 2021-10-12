<?php

namespace Eightfold\Events\Data;

use Eightfold\Events\Data\Year;

use Eightfold\FileSystem\Item;

class Years
{
    private string $root;

    /**
     * @var Item
     */
    private $item;

    /**
     * @var array<Year>
     */
    private array $content = [];

    /**
     * @param string $args [description]
     */
    public static function fold(...$args): Years
    {
        return new Years(...$args);
    }

    public function __construct(string $root)
    {
        $this->root = $root;
    }

    public function path(): string
    {
        return $this->root();
    }

    public function root(): string
    {
        return $this->root;
    }

    public function item(): Item
    {
        if ($this->item === null) {
            $this->item = Item::create($this->root);
        }
        return $this->item;
    }

    /**
     * @return array<Year> [description]
     */
    public function content(): array
    {
        if (count($this->content) === 0) {
            $c = $this->item()->content();
            if (is_array($c)) {
                foreach ($c as $year) {
                    $path  = $year->thePath();
                    $parts = explode('/', $path);
                    $year  = array_pop($parts);
                    $key   = 'i' . $year;

                    $this->content[$key] = Year::fold(
                        $this->root,
                        intval($year)
                    );

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
        foreach ($this->content() as $year) {
            if ($year->hasEvents()) {
                return true;

            }
        }
        return false;
    }

    /**
     * @return Year|bool       [description]
     */
    public function year(int $year)
    {
        $year = 'i' . $year;
        if ($c = $this->content() and array_key_exists($year, $c)) {
            return $c[$year];
        }
        return false;
    }
}
