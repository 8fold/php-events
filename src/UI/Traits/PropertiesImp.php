<?php

declare(strict_types=1);

namespace Eightfold\Events\UI\Traits;

use Carbon\Carbon;

use Eightfold\Events\Events;
use Eightfold\Events\Grid;

trait PropertiesImp
{
    private $path  = "";
    private $year  = 0;
    private $month = 0;

    protected $events;

    protected $carbon;

    private $uriPrefix = "/events";

    public function path(): string
    {
        return $this->path;
    }

    public function events(): Events
    {
        return $this->events;
    }

    public function carbon(): Carbon
    {
        return $this->carbon;
    }

    public function prefix(): string
    {
        return $this->uriPrefix;
    }

    public function isYear(): bool
    {
        return $this->month() === 0;
    }

    public function isMonth(): bool
    {
        return ! $this->isYear();
    }
}
