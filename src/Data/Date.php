<?php

namespace Eightfold\Events\Data;

use Eightfold\Events\Data\DataAbstract;

use Eightfold\FileSystem\Item;

use Eightfold\Events\Data\Interfaces\Day as DateInterface;

use Eightfold\Events\Data\Traits\YearImp;
use Eightfold\Events\Data\Traits\MonthImp;
use Eightfold\Events\Data\Traits\DateImp;

class Date implements DateInterface
{
    use YearImp;
    use MonthImp;
    use DateImp;

    private string $root;

    /**
     * @var Item|null
     */
    private $item;

    /**
     * @var array<Event>
     */
    private array $content = [];

    /**
     * @var array<int>
     */
    private array $parts = [];

    public static function fromItem(string $rootPath, Item $item): Date
    {
        $p = $item->thePath();
        $parts = explode('/', $p);

        $fileName = array_pop($parts);
        $fileName = str_replace('.event', '', $fileName);
        $fParts   = explode('_', $fileName);
        $date     = intval(array_shift($fParts));

        $month = intval(array_pop($parts));

        $year = intval(array_pop($parts));

        // Item doesn't need date; go up one
        return new Date($rootPath, $year, $month, $date, $item->up());
    }

    /**
     * @param mixed $args [description]
     */
    public static function fold(...$args): Date
    {
        return new Date(...$args);
    }

    public function __construct(
        string $root,
        int $year,
        int $month,
        int $date,
        Item $item = null
    ) {
        $this->root = $root;
        $this->parts = [$year, $month, $date];
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
     * @return array<Event>
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
                    if (
                        substr($path, -6) === '.event' and
                        substr($fileName, 0, 2) === $this->dateString()
                    ) {
                        $this->content[$path] =
                            Event::fromItem($this->root, $item);

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
        return $this->hasEvents();
    }

    public function hasEvents(): bool
    {
        return $this->count() > 0;
    }
}
