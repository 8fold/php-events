<?php

namespace Eightfold\Events\Data;

use Eightfold\Events\Data\DataAbstract;

use Eightfold\FileSystem\Item;

use Eightfold\Events\Data\Traits\DateImp;

class Event extends DataAbstract
{
    use DateImp;

    private int $count;

    private string $content = '';

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

    public function __construct(
        string $root,
        int $year,
        int $month,
        int $date,
        int $count,
        Item $item = null
    )
    {
        $this->root  = $root;
        $this->parts = [$year, $month, $date, $count];
        $this->item  = $item;
    }

    public function item(): Item
    {
        if ($this->item === null) {
            $this->item = Item::create($this->root)->append(
                $this->year(),
                $this->month(),
                $this->date() . '_' . $this->count() . '.event'
            );

            if ($this->count() === 1 and ! $this->item->isFile()) {
                $check = Item::create($this->root)->append(
                    $this->year(),
                    $this->month(),
                    $this->date() . '.event'
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
            $this->content = $this->item()->content();

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
        return trim($body);
    }

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
