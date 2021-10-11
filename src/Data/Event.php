<?php

namespace Eightfold\Events\Data;

use Eightfold\Events\Data\DataAbstract;

use Eightfold\FileSystem\Item;

// use Eightfold\ShoopShelf\Shoop;

// use Eightfold\Events\Data\Traits\PartsImp;
// use Eightfold\Events\Data\Traits\YearImp;
// use Eightfold\Events\Data\Traits\MonthImp;
// use Eightfold\Events\Data\Traits\DateImp;

class Event extends DataAbstract
{
    // use PartsImp, YearImp, MonthImp, DateImp;

    private int $count;

    private $item;

    private string $content = '';

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

    /**
     * possible traits
     */
    // year implementation
    public function year(bool $asString = true)
    {
        if ($asString) {
            return strval($this->parts[0]);
        }
        return $this->parts[0];
    }

    // month implementation
    public function month(bool $asString = true)
    {
        if ($asString) {
            $month = $this->month(false);
            if ($month >= 10) {
                return (string) $month;
            }
            return "0". $month;
        }
        return $this->parts[1];
    }

    public function daysInMonth(): int
    {
        $carbon = Carbon::now()->year($this->year())->month($this->month());
        return $carbon->daysInMonth;
    }

    public function date(bool $asString = true)
    {
        if ($asString) {
            $date = $this->date(false);
            if ($date >= 10) {
                return (string) $this->parts[2];
            }
            return "0". $this->parts[2];
        }
        return $this->parts[2];
    }
}
