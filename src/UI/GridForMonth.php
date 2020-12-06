<?php

namespace Eightfold\Events\UI;

use Eightfold\Events\UI\GridAbstract;

use Carbon\Carbon;

use Eightfold\Markup\UIKit;
use Eightfold\ShoopShelf\Shoop;

use Eightfold\Events\Data\Traits\MonthImp;

use Eightfold\Events\Data\Date;

class GridForMonth extends GridAbstract
{
    use MonthImp;

    private $weeksToDisplay = 6;

    public function __construct(string $root, int $year, int $month)
    {
        $this->root = $root;
        $this->parts = [$year, $month];

        $endBlanks = $this->totalEndGridBlanks();
        if ($endBlanks >= 7) {
            $this->weeksToDisplay -= 1;
        }
    }

    public function carbon()
    {
        if ($this->carbon === null) {
            $this->carbon = Carbon::now()
                ->year($this->year(false))->month($this->month(false))->day(10)
                ->startOfWeek(Carbon::MONDAY);
        }
        return $this->carbon;
    }

    public function totalStartGridBlanks(): int
    {
        return $this->carbon()->copy()->startOfMonth()->dayOfWeek - 1;
    }

    public function totalEndGridBlanks(): int
    {
        $totalItems = $this->totalGridItems();
        $totalStart = $this->totalStartGridBlanks();
        $totalDays = $this->daysInMonth();
        return $totalItems - $totalStart - $totalDays;
    }

    public function totalGridItems(): int
    {
        return 7 * $this->weeksToDisplay;
    }

    public function totalDaysInMonth()
    {
        return $this->carbon()->daysInMonth;
    }

// -> rendering
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

        if ($month) {
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

        if ($month) {
            $format = $this->monthTitleFormat;
            $title = $this->carbon()->copy()
                ->year($month->year())->month($month->month())->format($format);
        }
        return $this->navLink($month, $title, "ef-grid-next-month");
    }

    public function gridItem(int $itemNumber)
    {
        $month = $this->events()->month($this->year(), $this->month());
        if (! $month) {
            return $this->gridItemBlank($itemNumber);
        }

        $date = $this->events()->date($this->year(), $this->month(), $itemNumber);
        if (! $date or ! $date->hasEvents()) {
            return $this->gridItemBlank($itemNumber);
        }

        $cc = $this->carbon()->copy()
            ->year($date->year())
            ->month($date->month())
            ->day($date->date());

        $id     = $cc->format("Y") . $cc->format("m") . $cc->format("d");
        $abbr   = $cc->format("j");
        $title  = $cc->format($this->dayTitleFormat);

        $events = Shoop::this($date->content())->each(function($event) {
            return UIKit::span($event->title());
        })->unfold();

        return UIKit::button(
                UIKit::abbr($abbr)->attr("title ". $title),
                ...$events
            )->attr(
                "id toggle-". $id,
                "aria-expanded false",
                "class calendar-date",
                "onclick EFEventsModals.init(this, ". $id .")"
            );
    }

    public function gridItemBlank(int $itemNumber)
    {
        $cc = $this->carbon()->copy()->year(
            $this->year()
        )->month(
            $this->month()
        )->day(
            $itemNumber
        );

        $abbr = $cc->format($this->dayAbbrFormat);
        $title = $cc->format($this->dayTitleFormat);

        return UIKit::button(
            UIKit::abbr($abbr)->attr("title ". $title)
        )->attr(
            "disabled disabled",
            "aria-disabled true",
            "role presentation"
        );
    }

    // public function startBlanks()
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
    //     return $startBlanks;
    // }

    // public function endBlanks()
    // {
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
    //     return $endBlanks;
    // }

    public function bookEndBlank()
    {
        return UIKit::button()->attr(
            "disabled disabled",
            "aria-disabled true",
            "role presentation"
        );
    }

    // public function gridItems()
    // {
    //     $range = range(1, $this->totalDaysInMonth());
    //     $eventItems = [];
    //     return Shoop::this($range)->each(function($date) {
    //         return $this->gridItem($date);
    //     });
    // }

//     public function eventItems()
//     {
//         $range  = range(1, $this->totalDaysInMonth());
//         $month = $this->events()->month($this->year(), $this->month());
//         if (! $month->hasEvents()) {
//             return [
//                 UIKit::p("No events found.")
//                     ->attr("class ef-events-empty")
//             ];
//         }
//         $dates = $month->content();
//         $eventItems = Shoop::this($dates)->each(function($date, $m, &$build) {
//             $date = $date[0];
//             if (! $date or ! $date->hasEvents()) {
//                 return "";
//             }
//             $build[] = $this->eventsModalItem($date);
//         });
// die(var_dump(
//     $eventItems->unfold()
// ));
//         return $eventItems->unfold();
//     }

    private function eventsModalItem(Date $date)
    {
        if (! $date->hasEvents()) {
            return [""];
        }
        $eventParts = Shoop::this($date->content())->each(function($event, $m, &$build) {
            $title = $event->title();
            $body  = $event->body();
            if (Shoop::this($body)->isEmpty()->reversed()->unfold()) {
                $build[] = UIKit::h4($title);
                $build[] = UIKit::markdown($body);
            }
            return "";
        })->drop(fn($v) => empty($v))->append([
            UIKit::button(
                UIKit::span("close")
            )->attr(
                "onclick EFEventsModals.closeAll()"
            )
        ])->unfold();

        $year  = $date->year();
        $month = $date->month();
        $day   = $date->date();
        $id = "id {$year}{$month}{$day}";
        $heading = Carbon::now()->year($year)->month($month)->day($day)
            ->format($this->dayTitleFormat);
        return UIKit::div(
            UIKit::h3($heading),
            ...$eventParts
        )->attr($id, "role dialog");
    }

    public function dayTitles()
    {
        return Shoop::this([
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
            })->efToArray();
    }

    public function unfold()
    {
        $totalGridItems = $this->totalGridItems();
        $startingBlanks = $this->totalStartGridBlanks(); // start blank grid items
        $endingBlanks = $this->totalEndGridBlanks(); // end blank grid items

        $eventItems = [];
        $gridItems = range(1, $totalGridItems);
        $gridItems = Shoop::this($gridItems)->each(
            function($itemNumber) use (
                $totalGridItems,
                $startingBlanks,
                $endingBlanks,
                &$eventItems
            ) {
                if ($itemNumber <= $startingBlanks) {
                    return $this->bookEndBlank();

                } elseif ($itemNumber > ($totalGridItems - $endingBlanks)) {
                    return $this->bookEndBlank();

                } else {
                    $i = $itemNumber - $startingBlanks;
                    $date = $this->events()->date(
                        $this->year(),
                        $this->month(),
                        $i
                    );

                    if ($date) {
                        $eventItems[] = $this->eventsModalItem($date);
                    }

                    // blank date grid items
                    // event date grid items
                    return $this->gridItem($i);

                }
        })->drop(fn($e) => empty($e))->unfold();

        if (Shoop::this($eventItems)->efIsEmpty()) {
            $eventItems = UIKit::p("No events found.")
                ->attr("class ef-events-empty");
        }

        return UIKit::div(...Shoop::this([
                $this->header(),
                $this->previousLink(),
                $this->nextLink(),
                UIKit::div(...$eventItems)->attr(
                    "id ef-events-modals",
                    "onclick EFEventsModals.closeAll()",
                    "aria-hidden true"
                )
            ])->append($this->dayTitles())
            ->append($gridItems)
        )->attr("class ef-events-grid ef-events-grid-month", "aria-live assertive");
    }















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






    //     $render = Shoop::array($startBlanks)
    //         ->plus(...$days)->plus(...$endBlanks)->noEmpties();
    //     $dayTitles = $this->dayTitles();
    //     if ($eventItems->isEmptyUnfolded()) {
    //         $dayTitles = Shoop::array([]);
    //         $render = Shoop::array([$emptyEvents]);
    //     }

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
