<?php

namespace Eightfold\Events\UI;

use Carbon\Carbon;

use Eightfold\Markup\UIKit;
use Eightfold\Shoop\Shoop;

use Eightfold\Events\Events;
use Eightfold\Events\Data\Day;

use Eightfold\Events\UI\Interfaces\Render;
use Eightfold\Events\UI\Traits\RenderImp;

use Eightfold\Events\UI\Interfaces\Formats;
use Eightfold\Events\UI\Traits\FormatsImp;

use Eightfold\Events\UI\Interfaces\Properties;
use Eightfold\Events\UI\Traits\PropertiesImp;

use Eightfold\Events\UI\Interfaces\NumbersForMonth;
use Eightfold\Events\UI\Traits\NumbersForMonthImp;

class GridForMonth implements Render, Formats, Properties, NumbersForMonth
{
    use RenderImp, FormatsImp, PropertiesImp, NumbersForMonthImp;

    static public function forMonth(
        string $path,
        string $class = \Eightfold\Events\UI\GridForMonth::class
    )
    {
        return new $class($path);
    }

    public function __construct(string $path)
    {
        $eventsPath = Shoop::string($path)->divide("/")->dropLast(2)->join("/");
        $this->events = Events::init($eventsPath);

        $this->path = $path;
        $this->year = Shoop::string($this->path)->divide("/")->toggle()
            ->first(2)->last()->int;
        $this->month = Shoop::string($this->path)->divide("/")->toggle()
            ->first()->int;

        $this->carbon = Carbon::now()
            ->year($this->year)->month($this->month)->day(10)
            ->startOfWeek(Carbon::MONDAY);

        $this->daysInMonth = $this->carbon->daysInMonth;

        // No blank weeks
        $endBlanks = $this->totalEndGridBlanks();
        if ($endBlanks >= 7) {
            $this->weeksToDisplay -= 1;
        }
    }

    public function unfold()
    {
        return $this->render();
    }

    public function render()
    {
        $startBlanks = [""];
        if ($this->totalStartGridBlanks() >= 1) {
            $startBlanks = Shoop::int($this->totalStartGridBlanks())->range(1)
                ->each(function($item) {
                    return UIKit::button()->attr(
                        "disabled disabled",
                        "aria-disabled true",
                        "role presentation"
                    );
                });
        }

        $eventItems = Shoop::array([]);
        $days = Shoop::int($this->totalDaysInMonth())->range(1)
            ->each(function($day) use (&$eventItems) {
                $day = $this->events()
                    ->year($this->year())->month($this->month())->day($day);
                if ($day->hasEvents()->unfold()) {
                    $eventItems = $eventItems
                        ->plus($this->eventsModalItem($day));
                }
                return $this->gridItem($day);
            });

        $endBlanks = [""];
        if ($this->totalEndGridBlanks() >= 1) {
            $endBlanks = Shoop::int($this->totalEndGridBlanks())->range(1)
                ->each(function($item) {
                    return UIKit::button()->attr(
                        "disabled disabled",
                        "aria-disabled true",
                        "role presentation"
                    );
                });
        }

        $render = Shoop::array($startBlanks)
            ->plus(...$days)->plus(...$endBlanks)->noEmpties();
        return UIKit::div(...Shoop::array([])
            ->plus(
                $this->header(),
                $this->previousLink(),
                $this->nextLink(),
                UIKit::div(...$eventItems->noEmpties())->attr(
                    "id ef-events-modals",
                    "onclick EFEventsModals.closeAll()",
                    "aria-hidden true"
                )
            )->plus(...$this->dayTitles()
            )->plus(...$render)
        )->attr("class ef-events-grid-month", "aria-live assertive");
    }

    public function header()
    {
        $title = $this->carbon()->copy()->format($this->monthTitleFormat);
        return UIKit::h2($title);
    }

    public function previousLink()
    {
        $month = $this->events()
            ->previousMonthWithEvents($this->year(), $this->month());
        $title = "";
        if ($month !== null) {
            $format = $this->monthTitleFormat;
            $title = $this->carbon()->copy()
                ->year($month->year())->month($month->month())->format($format);
        }
        return $this->navLink($month, $title, "ef-grid-previous-month");
    }

    public function nextLink()
    {
        $month = $this->events()
            ->nextMonthWithEvents($this->year(), $this->month());
        $title = "";
        if ($month !== null) {
            $format = $this->monthTitleFormat;
            $title = $this->carbon()->copy()
                ->year($month->year())->month($month->month())->format($format);
        }
        return $this->navLink($month, $title, "ef-grid-next-month");
    }

    public function dayTitles()
    {
        return Shoop::dictionary([
                "Mon" => "Monday",
                "Tue" => "Tuesday",
                "Wed" => "Wednesday",
                "Thu" => "Thursday",
                "Fri" => "Friday",
                "Sat" => "Saturday",
                "Sun" => "Sunday"
            ])->each(function($long, $short) {
                return UIKit::abbr($short)
                    ->attr("title {$long}", "class ef-weekday-heading");
            });
    }

    public function eventsModalItem(Day $day)
    {
        $events = $day->events();
        if ($events->isEmpty) {
            return [""];
        }

        $eventParts = $events->each(function($event) {
            $title = $event->title();
            $content = $event->body();
            if (strlen($content)) {
                return Shoop::array([
                    UIKit::h4($title),
                    UIKit::markdown($content)
                ]);
            }
            return "";
        })->noEmpties()->flatten()->plus(
            UIKit::button("close")->attr(
                "onclick EFEventsModals.closeAll()"
            )
        );

        $year  = $day->year();
        $month = $day->monthString();
        $day   = $day->dayString();
        $id = "id {$year}{$month}{$day}";
        $heading = Carbon::now()->year($year)->month($month)->day($day)
            ->format($this->dayTitleFormat);
        return UIKit::div(
            UIKit::h3($heading),
            ...$eventParts
        )->attr($id, "role dialog");
    }

    public function gridItem(Day $day)
    {
        if ($day->hasEvents()->unfold()) {
            $cc = $this->carbon()->copy()
                ->year($day->year())
                ->month($day->month())
                ->day($day->day());
            $id = $cc->format("Y") . $cc->format("m") . $cc->format("d");
            $abbr = $cc->format("j");
            $title = $cc->format($this->dayTitleFormat);
            return UIKit::button(
                    UIKit::abbr($abbr)->attr("title {$title}"),
                    ...$day->events()->each(function($event) {
                        return UIKit::span($event->title());
                    })->noEmpties()
                )->attr(
                    "id toggle-{$id}",
                    "for {$id}",
                    "aria-expanded false",
                    "class calendar-date",
                    "onclick EFEventsModals.init(this, {$id})"
                );
        }
        return $this->gridItemBlank($day);
    }

    public function gridItemBlank($uriObject)
    {
        $year = $uriObject->year();
        $month = $uriObject->month();
        $day = $uriObject->day();
        $cc = $this->carbon()->copy()->year($year)->month($month)->day($day);

        $abbr = $cc->format($this->dayAbbrFormat);
        $title = $cc->format($this->dayTitleFormat);

        return UIKit::button(
            UIKit::abbr($abbr)->attr("title {$title}")
        )->attr(
            "disabled disabled",
            "aria-disabled true",
            "role presentation"
        );
    }

    public function year(): int
    {
        return $this->year;
    }

    public function month(): int
    {
        return $this->month;
    }
}
