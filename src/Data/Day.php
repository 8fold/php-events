<?php

namespace Eightfold\Events\Data;

use Carbon\Carbon;

use Eightfold\Shoop\{
    Shoop,
    ESArray,
    ESBool,
    ESInt,
    ESString
};

use Eightfold\Events\Data\Interfaces\Day as DayInterface;
use Eightfold\Events\Data\Traits\DayImp;

use Eightfold\Events\Data\Event;

class Day implements DayInterface
{
    use DayImp;

    private $eventSeparator = "^^^^^^";

    private $events;

    static public function init(string $path): Day
    {
        return new Day($path);
    }

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function month()
    {
        return $this->path()->divide("/")->toggle()->first(2)->last()->int;
    }

    public function year()
    {
        return $this->path()->divide("/")->toggle()->first(3)->last()->int;
    }

    public function hasEvents(): ESBool
    {
        return $this->totalEvents()->isGreaterThan(0);
    }

    public function totalEvents(): ESInt
    {
        return $this->events()->count();
    }

    public function events(): ESArray
    {
        return $this->dataPaths()->each(function($path) {
                $event = Event::init($path);
                if ($event->content()->count()->isUnfolded(0)) {
                    return "";
                }
                return $event;

            })->noEmpties();
    }

    public function dataPaths(): ESArray
    {
        return $this->path()->divide("/", false, -1)->asString("/")->start("/")
            ->pathContent()
            ->each(function($path) {
                $dayString = $this->uri()->divide("/")->last();
                $startsWithDay = Shoop::string($path)->divide("/")->last()
                    ->startsWithUnfolded($dayString);
                if ($startsWithDay) {
                    return $path;
                }
                return "";
            })->noEmpties();
    }

    public function uri(): ESString
    {
        return $this->path()->divide("/")->toggle()->first(3)->toggle()
            ->asString("/")->start("/");
    }
}
