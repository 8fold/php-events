<?php

namespace Eightfold\Events\Data;

use Eightfold\Events\Data\DataAbstract;

use Eightfold\FileSystem\Item;

use Eightfold\Events\Data\Interfaces\Month as MonthInterface;

use Eightfold\Events\Data\Traits\YearImp;
use Eightfold\Events\Data\Traits\MonthImp;

class Month implements MonthInterface
{
    use YearImp;
    use MonthImp;

    private $item;

    private array $content = [];

    public static function fromItem(string $rootPath, Item $item): Month
    {
        $p = $item->thePath();
        $parts = explode('/', $p);

        $month = intval(array_pop($parts));

        $year = intval(array_pop($parts));

        return new Month($rootPath, $year, $month, $item);
    }

    static public function fold(...$args): Month
    {
        return new Month(...$args);
    }

    public function __construct(
        string $root,
        int $year,
        int $month,
        Item $item = null
    ) {
        $this->root = $root;
        $this->parts = [$year, $month];
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
                if (substr($path, -6) === '.event') {
                    $date = substr($fileName, 0, 2);
                    $key  = 'i' . $date;
                    if (! isset($this->content[$key])) {
                        $item = Item::create($this->path() .'/'. $date);
                        $this->content[$key] = Date::fromItem($this->root, $item);

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

// TODO: Test??
    public function is(int $compare): bool
    {
        return (Shoop::this($this->month(false))->is($compare)->unfold())
            ? true
            : false;
    }

    public function isAfter(int $compare): bool
    {
        if ($this->is($compare)) {
            return false;
        }
        return Shoop::this($this->month(false))->isGreaterThan($compare)->unfold();
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
        return Shoop::this($this->path())->divide("/")->last(2)->asString("/")
            ->prepend("/");
    }
}
