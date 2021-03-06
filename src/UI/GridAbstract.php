<?php

namespace Eightfold\Events\UI;

use Eightfold\Foldable\Fold;
use Eightfold\Foldable\Foldable;

use Carbon\Carbon;

use Eightfold\Events\Data\Traits\RootImp;
use Eightfold\Events\Data\Traits\PartsImp;
use Eightfold\Events\Data\Traits\YearImp;
use Eightfold\Events\Data\Traits\MonthImp;

use Eightfold\Markup\UIKit;
use Eightfold\ShoopShelf\Shoop;

use Eightfold\Events\Events;

use Eightfold\Events\UI\Traits\RenderImp;
use Eightfold\Events\UI\Traits\FormatsImp;

use Eightfold\Events\UI\Traits\PropertiesImp;

// use Eightfold\Events\UI\Interfaces\NumbersForMonth;
// use Eightfold\Events\UI\Traits\NumbersForMonthImp;

abstract class GridAbstract extends Fold
{
    use RootImp, PartsImp, YearImp, FormatsImp, RenderImp, PropertiesImp;

    abstract public function carbon();

    abstract public function totalGridItems(): int;

    abstract public function header();

    abstract public function gridItem(int $itemNumber);

    public function events()
    {
        if ($this->events === null) {
            $this->events = Events::fold($this->root());
        }
        return $this->events;
    }

// -> rendering


    // public function header()
    // {
    //     $title = $this->carbon()->copy()->format($this->monthTitleFormat);
    //     return UIKit::h2($title);
    // }

    // public function previousLink()
    // {
    //     $month = $this->events()
    //         ->previousMonthWithEvents($this->year(false), $this->month(false));
    //     $title = "";

    //     if ($month) {
    //         $format = $this->monthTitleFormat;
    //         $title = $this->carbon()->copy()
    //             ->year($month->year())->month($month->month())->format($format);
    //     }
    //     return $this->navLink($month, $title, "ef-grid-previous-month");
    // }

    // public function nextLink()
    // {
    //     $month = $this->events()
    //         ->nextMonthWithEvents($this->year(), $this->month());
    //     $title = "";

    //     if ($month) {
    //         $format = $this->monthTitleFormat;
    //         $title = $this->carbon()->copy()
    //             ->year($month->year())->month($month->month())->format($format);
    //     }
    //     return $this->navLink($month, $title, "ef-grid-next-month");
    // }



    // public function gridItem(int $itemNumber)
    // {
    //     $month = $this->events()->month($this->year(), $this->month());
    //     if (! $month) {
    //         return $this->gridItemBlank($itemNumber);
    //     }

    //     $date = $this->events()->date($this->year(), $this->month(), $itemNumber);
    //     if (! $date->hasEvents()) {
    //         return $this->gridItemBlank($itemNumber);
    //     }

    //     $cc = $this->carbon()->copy()
    //         ->year($date->year())
    //         ->month($date->month())
    //         ->day($date->date());

    //     $id     = $cc->format("Y") . $cc->format("m") . $cc->format("d");
    //     $abbr   = $cc->format("j");
    //     $title  = $cc->format($this->dayTitleFormat);

    //     $events = Shoop::this($date->content())->each(function($event) {
    //         return UIKit::span($event->title());
    //     })->unfold();

    //     return UIKit::button(
    //             UIKit::abbr($abbr)->attr("title ". $title),
    //             ...$events
    //         )->attr(
    //             "id toggle-". $id,
    //             "aria-expanded false",
    //             "class calendar-date",
    //             "onclick EFEventsModals.init(this, ". $id .")"
    //         );
    // }

    // public function gridItemBlank(int $itemNumber)
    // {
    //     $cc = $this->carbon()->copy()->year(
    //         $this->year()
    //     )->month(
    //         $this->month()
    //     )->day(
    //         $itemNumber
    //     );

    //     $abbr = $cc->format($this->dayAbbrFormat);
    //     $title = $cc->format($this->dayTitleFormat);

    //     return UIKit::button(
    //         UIKit::abbr($abbr)->attr("title ". $title)
    //     )->attr(
    //         "disabled disabled",
    //         "aria-disabled true",
    //         "role presentation"
    //     );
    // }
























    // use RenderImp, FormatsImp, PropertiesImp, NumbersForMonthImp;

    // static public function forMonth(
    //     string $path,
    //     string $class = \Eightfold\Events\UI\GridForMonth::class
    // )
    // {
    //     return new $class($path);
    // }

    // public function __construct(string $path)
    // {
    //     $eventsPath = Shoop::string($path)->divide("/")->dropLast(2)->join("/");
    //     $this->events = Events::init($eventsPath);

    //     $this->path = $path;
    //     $this->year = Shoop::string($this->path)->divide("/")->toggle()
    //         ->first(2)->last()->int;
    //     $this->month = Shoop::string($this->path)->divide("/")->toggle()
    //         ->first()->int;

    //     $this->daysInMonth = $this->carbon->daysInMonth;

    //     // No blank weeks
    // }

    // public function unfold()
    // {
    //     return $this->render();
    // }

    // public function render()
    // {
    //     $startBlanks = [""];
    //     if ($this->totalStartGridBlanks() >= 1) {
    //         $startBlanks = Shoop::int($this->totalStartGridBlanks())->asArray(1)
    //             ->each(function($item) {
    //                 return UIKit::button()->attr(
    //                     "disabled disabled",
    //                     "aria-disabled true",
    //                     "role presentation"
    //                 );
    //             });
    //     }

    //     $eventItems = Shoop::array([]);
    //     $days = Shoop::int($this->totalDaysInMonth())->asArray(1)
    //         ->each(function($day) use (&$eventItems) {
    //             $day = $this->events()
    //                 ->year($this->year())->month($this->month())->day($day);
    //             if ($day->hasEvents()->unfold()) {
    //                 $eventItems = $eventItems
    //                     ->plus($this->eventsModalItem($day));
    //             }
    //             return $this->gridItem($day);
    //         });

    //     $emptyEvents = $eventItems->isEmpty(function($result, $array) {
    //         if ($result->unfold()) {
    //             return UIKit::p("No events found.")
    //                 ->attr("class ef-events-empty");
    //         }
    //         return "";
    //     });

    //     $endBlanks = [""];
    //     if ($this->totalEndGridBlanks() >= 1) {
    //         $endBlanks = Shoop::int($this->totalEndGridBlanks())->asArray(1)
    //             ->each(function($item) {
    //                 return UIKit::button()->attr(
    //                     "disabled disabled",
    //                     "aria-disabled true",
    //                     "role presentation"
    //                 );
    //             });
    //     }

    //     $render = Shoop::array($startBlanks)
    //         ->plus(...$days)->plus(...$endBlanks)->noEmpties();
    //     $dayTitles = $this->dayTitles();
    //     if ($eventItems->isEmptyUnfolded()) {
    //         $dayTitles = Shoop::array([]);
    //         $render = Shoop::array([$emptyEvents]);
    //     }
    //     return UIKit::div(...Shoop::array([])
    //         ->plus(
    //             $this->header(),
    //             $this->previousLink(),
    //             $this->nextLink(),
    //             UIKit::div(...$eventItems->noEmpties())->attr(
    //                 "id ef-events-modals",
    //                 "onclick EFEventsModals.closeAll()",
    //                 "aria-hidden true"
    //             )
    //         )->plus(...$dayTitles
    //         )->plus(...$render)
    //     )->attr("class ef-events-grid ef-events-grid-month", "aria-live assertive");
    // }






    // public function dayTitles()
    // {
    //     return Shoop::dictionary([
    //             "Mon" => "Monday",
    //             "Tue" => "Tuesday",
    //             "Wed" => "Wednesday",
    //             "Thu" => "Thursday",
    //             "Fri" => "Friday",
    //             "Sat" => "Saturday",
    //             "Sun" => "Sunday"
    //         ])->each(function($long, $short) {
    //             return UIKit::abbr($short)
    //                 ->attr("title {$long}", "class ef-weekday-heading");
    //         });
    // }

    // public function eventsModalItem(Day $day)
    // {
    //     $events = $day->events();
    //     if ($events->isEmpty) {
    //         return [""];
    //     }

    //     $eventParts = $events->each(function($event) {
    //         $title = $event->title();
    //         $content = $event->body();
    //         if (strlen($content)) {
    //             return Shoop::array([
    //                 UIKit::h4($title),
    //                 UIKit::markdown($content)
    //             ]);
    //         }
    //         return "";
    //     })->noEmpties()->flatten()->plus(
    //         UIKit::button(
    //             UIKit::span("close")
    //         )->attr(
    //             "onclick EFEventsModals.closeAll()"
    //         )
    //     );

    //     $year  = $day->year();
    //     $month = $day->monthString();
    //     $day   = $day->dayString();
    //     $id = "id {$year}{$month}{$day}";
    //     $heading = Carbon::now()->year($year)->month($month)->day($day)
    //         ->format($this->dayTitleFormat);
    //     return UIKit::div(
    //         UIKit::h3($heading),
    //         ...$eventParts
    //     )->attr($id, "role dialog");
    // }





    // public function year(): int
    // {
    //     return $this->year;
    // }

    // public function month(): int
    // {
    //     return $this->month;
    // }
}
