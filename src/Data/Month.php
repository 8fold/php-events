<?php

declare(strict_types=1);

namespace Eightfold\Events\Data;

use Eightfold\Events\Data\DataAbstract;

use Eightfold\FileSystem\Item;

use Eightfold\Events\Implementations\Root as RootImp;
use Eightfold\Events\Implementations\Parts as PartsImp;
use Eightfold\Events\Implementations\Year as YearImp;
use Eightfold\Events\Implementations\Month as MonthImp;

class Month
{
    use RootImp;
    use PartsImp;
    use YearImp;
    use MonthImp;

    /**
     * @var Item|null
     */
    private $item;

    /**
     * @var array<Date>
     */
    private array $content = [];

    public static function fromItem(string $rootPath, Item $item): Month
    {
        $p = $item->thePath();
        $parts = explode('/', $p);

        $month = intval(array_pop($parts));

        $year = intval(array_pop($parts));

        return new Month($rootPath, $year, $month, $item);
    }

    /**
     * @param mixed $args [description]
     */
    public static function fold(...$args): Month
    {
        return new Month(...$args);
    }

    public function __construct(
        string $root,
        int $year,
        int $month,
        Item $item = null
    ) {
        $this->root  = $root;
        $this->parts = [$year, $month];
        $this->item  = $item;
    }

    public function item(): Item
    {
        if ($this->item === null) {
            $this->item = Item::create($this->root)->append(
                $this->yearString(),
                $this->monthString(),
            );
        }
        return $this->item;
    }

    public function path(): string
    {
        return $this->item()->thePath();
    }

    /**
     * @return array<Date>
     */
    public function content()
    {
        if (count($this->content) === 0) {
            $c = $this->item()->content();
            if (is_array($c)) {
                foreach ($c as $item) {
                    $path     = $item->thePath();
                    $p        = explode('/', $path);
                    $fileName = array_pop($p);
                    if (substr($path, -6) === '.event') {
                        $date = substr($fileName, 0, 2);
                        $key  = 'i' . $date;
                        if (! isset($this->content[$key])) {
                            $item = Item::create($this->path() . '/' . $date);
                            $this->content[$key] = Date::fromItem($this->root, $item);

                        }
                    }
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
        foreach ($this->content() as $date) {
            if ($date->hasEvents()) {
                return true;
            }
        }
        return false;
    }

    public function isSameAs(int $compare): bool
    {
        return $this->month() === $compare;
    }

    public function isAfter(int $compare): bool
    {
        if ($this->isSameAs($compare)) {
            return false;
        }
        return $this->month() > $compare;
    }

    public function isBefore(int $compare): bool
    {
        if ($this->isSameAs($compare)) {
            return false;
        }
        return $this->month() < $compare;
    }

    public function uri(): string
    {
        $parts = explode('/', $this->path());
        $parts = array_slice($parts, -2);
        return '/' . implode('/', $parts);
    }
}
