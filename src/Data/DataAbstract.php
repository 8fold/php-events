<?php

namespace Eightfold\Events\Data;

use Carbon\Carbon;

abstract class DataAbstract
{
    protected $root;

    protected $item;

    static public function fold(...$args): DataAbstract
    {
        return new static(...$args);
    }

    public function unfold()
    {
        return $this->path();
    }

    abstract public function path(): string;

    abstract public function content();

    abstract public function count(): int;

    abstract public function couldHaveEvents(): bool;

    abstract public function hasEvents(): bool;

    public function root(): string
    {
        return $this->root;
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
}
