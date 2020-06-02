<?php

namespace Eightfold\Events\Data;

use Carbon\Carbon;

use Eightfold\Shoop\{
    Shoop,
    ESArray,
    ESBool,
    ESDictionary,
    ESInt,
    ESString
 };

use Eightfold\Events\Data\Interfaces\Month as MonthInterface;
use Eightfold\Events\Data\Traits\MonthImp;

use Eightfold\Events\Data\Event;

class Month implements MonthInterface
{
    use MonthImp;

    private $days; // ESDictionary

    private $events; // ESArray

	static public function init(string $path): Month
	{
		return new Month($path);
	}

	public function __construct(string $path)
	{
        $this->path = $path;

        $this->days = Shoop::dictionary([]);
	}

    public function hasEvents(): ESBool
    {
        return $this->totalEvents()->isGreaterThan(0);
    }

    public function totalEvents(): ESInt
    {
        return $this->events()->count();
    }

    // TODO: deprecated??
    public function events(): ESArray
    {
        $events = Shoop::array([]);
        $this->days()->each(function($day, $timestamp) use (&$events) {
            $events = $events->plus(...$day->events());
        });
        return $events;
    }

    public function totalDays()
    {
        $carbon = Carbon::now()->year($this->year())->month($this->month());
        return $carbon->daysInMonth;
    }

    public function days(): ESDictionary
    {
        if ($this->days->isEmpty) {
            $this->path()->pathContent()->each(function($path) {

                $path = Shoop::string($path)->divide(".", false, 2)->first()
                    ->divide("_", false, 2)->first();
                $year = $this->year();
                $month = $this->monthString();
                $day = $path->divide("/")->last()->int;

                $member   = "i{$year}{$month}{$day}";
                $instance = Day::init($path);
                $doesNotHaveMember = $this->days->hasMember($member)->not;
                $hasEvents = $instance->hasEvents()->unfold();
                if ($doesNotHaveMember and $hasEvents) {
                    $this->days = $this->days->plus($instance, $member);
                }
            });
        }
        return $this->days;
    }

    public function day(int $day)
    {
        $year   = $this->year();
        $month  = $this->monthString();
        $day    = $this->dayString($day);
        $member = "i{$year}{$month}{$day}";
        $cached = $this->days()->{$member};
        if ($cached === null) {
            return Day::init($this->path()->plus("/{$day}"));
        }
        return $cached;
    }

    public function month()
    {
        return $this->path()->divide("/")->toggle()->first()->int;
    }

    public function year()
    {
        return $this->path()->divide("/")->toggle()->first(2)->last()->int;
    }

    public function dataPaths(): ESArray
    {
        return $this->path()->divide("/")->join("/")->pathContent()
            ->each(function($path) {
                $tail = $this->uri()->divide("/")->last();
                $startsWithTail = Shoop::string($tail)->divide("/")->last()
                    ->startsWithUnfolded($tail);
                if ($startsWithTail) {
                    return $path;
                }
                return "";
            })->noEmpties();
    }

    public function uri(): ESString
    {
        return Shoop::string($this->path())->divide("/")->toggle()
            ->first(2)->toggle()->join("/")->start("/");
    }
}
