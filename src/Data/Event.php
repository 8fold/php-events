<?php

declare(strict_types=1);

namespace Eightfold\Events\Data;

use Eightfold\FileSystem\Item;

use Eightfold\Events\Data\Interfaces\Event as EventInterface;

use Eightfold\Events\Implementations\Year as YearImp;
use Eightfold\Events\Implementations\Month as MonthImp;
use Eightfold\Events\Implementations\Date as DateImp;

class Event
{
    use YearImp;
    use MonthImp;
    use DateImp;

    private string $root;

    /**
     * @var Item|null
     */
    private $item;

    private int $count;

    private string $content = '';

    /**
     * @var array<int>
     */
    private array $parts = [];

    public static function fromItem(string $rootPath, Item $item): Event
    {
        $p = $item->thePath();
        $parts = explode('/', $p);

        $fileName = array_pop($parts);
        $fileName = str_replace('.event', '', $fileName);
        $fParts   = explode('_', $fileName);
        $date     = intval(array_shift($fParts));
        $count    = 1;
        if (count($fParts) > 0) {
            $count = intval($fParts[0]);
        }

        $month = intval(array_pop($parts));

        $year = intval(array_pop($parts));

        return new Event($rootPath, $year, $month, $date, $count, $item);
    }

    /**
     * @param mixed $args [description]
     */
    public static function fold(...$args): Event
    {
        return new Event(...$args);
    }

    public function __construct(
        string $root,
        int $year,
        int $month,
        int $date,
        int $count,
        Item $item = null
    ) {
        $this->root  = $root;
        $this->parts = [$year, $month, $date, $count];
        $this->item  = $item;
    }

    public function item(): Item
    {
        if ($this->item === null) {
            $this->item = Item::create($this->root)->append(
                $this->yearString(),
                $this->monthString(),
                $this->dateString() . '_' . $this->count() . '.event'
            );

            if ($this->count() === 1 and ! $this->item->isFile()) {
                $check = Item::create($this->root)->append(
                    $this->yearString(),
                    $this->monthString(),
                    $this->dateString() . '.event'
                );

                if ($check->isFile()) {
                    $this->item = $check;

                }
            }
        }
        return $this->item;
    }

    public function path(): string
    {
        return $this->item()->thePath();
    }

    public function content(): string
    {
        if (strlen($this->content) === 0 and $this->hasEvents()) {
            $c = $this->item()->content();
            if (is_string($c)) {
                $this->content = $c;

            }
        }
        return $this->content;
    }

    public function title(): string
    {
        $content = $this->content();
        if (strlen($content) === 0) {
            return '';

        }

        $parts = $this->contentParts();
        $title = array_shift($parts);
        if ($title === null) {
            return '';

        }
        return trim($title);
    }

    public function body(): string
    {
        $content = $this->content();
        if (strlen($content) === 0) {
            return '';

        }

        $parts = $this->contentParts();
        $body  = array_pop($parts);
        if ($body === null) {
            return '';

        }
        return trim($body);
    }

    /**
     * @return array<string> [description]
     */
    private function contentParts(): array
    {
        return explode("\n\n", $this->content(), 2);
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
        return $this->item()->isFile();
    }
}
