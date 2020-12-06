<?php

namespace Eightfold\Events\UI;

use Eightfold\Events\UI\GridAbstract;
// use Eightfold\Foldable\Fold;
// use Eightfold\Foldable\Foldable;

use Carbon\Carbon;

use Eightfold\Markup\UIKit;
use Eightfold\Shoop\Shoop;

use Eightfold\Events\Data\Year;

class GridForYear extends GridAbstract
{
    public function __construct(string $root, int $year)
    {
        $this->root = $root;
        $this->parts = [$year];
    }

    public function totalGridItems(): int
    {
        return Year::totalMonthsInYear();
    }

    public function header()
    {
        $title = $this->carbon()->copy()->format($this->yearTitleFormat);
        return UIKit::h2($title);
    }

    public function carbon()
    {
        if ($this->carbon === null) {
            $this->carbon = Carbon::now()
                ->year($this->year(false));
        }
        return $this->carbon;
    }

    public function gridItem(int $itemNumber)
    {
        $year = $this->events()->year($this->year());
        if (! $year) {
            return $this->gridItemBlank($itemNumber);
        }

        $month = $this->events()->month($this->year(), $itemNumber);

        // $date = $this->events()->date($this->year(), $this->month(), $itemNumber);
        if (! $month->hasEvents()) {
            return $this->gridItemBlank($itemNumber);
        }

        $cc = $this->carbon()->copy()
            ->year($month->year())
            ->month($month->month());

        $abbr   = $cc->format($this->monthAbbrFormat);
        $title  = $cc->format($this->monthTitleFormat);
        $total = strval($month->count());

        return UIKit::a(
            UIKit::abbr($abbr)
                ->attr("title ". $title),
            UIKit::span($total)
        )->attr(
            "href ". $this->prefix() . $month->uri()
        );
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

    public function previousLink()
    {
        $year = $this->events()->previousYearWithEvents($this->year());
        $title = "";

        if ($year) {
            $format = $this->yearTitleFormat;
            $title = $this->carbon()->copy()->year($year->year())
                ->format($format);
        }

        return $this->navLink($year, $title, "ef-grid-previous-year");
    }

    public function nextLink()
    {
        $year = $this->events()->nextYearWithEvents($this->year());
        $title = "";

        if ($year) {
            $format = $this->yearTitleFormat;
            $title = $this->carbon()->copy()->year($year->year())
                ->format($format);
        }

        return $this->navLink($year, $title, "ef-grid-next-year");
    }

    // use RenderImp, FormatsImp, PropertiesImp, NumbersImp;

    // public function __construct(string $path)
    // {
    //     $eventsPath = Shoop::string($path)->divide("/")->dropLast()->join("/");
    //     $this->events = Events::init($eventsPath);

    //     $this->path = $path;
    //     $this->year = Shoop::string($this->path)->divide("/")->last()->int;
    // }

    // public function unfold()
    // {
    //     return $this->render();
    // }

    // public function render()
    // {
    //     $itemHasEvents = Shoop::array([]);
    //     $months = Shoop::int($this->totalGridItems())->asArray(1)
    //         ->each(function($month) use (&$itemHasEvents) {
    //             $month = $this->events()
    //                 ->year($this->year())->month($month);
    //             $itemHasEvents = $itemHasEvents
    //                 ->plus($month->hasEvents()->unfold());
    //             return $this->gridItem($month);
    //         });

    //     $render = $months;
    //     if ($itemHasEvents->doesNotHaveUnfolded(true)) {
    //         $render = Shoop::array([
    //                 UIKit::p("No events found.")->attr("class ef-events-empty")
    //             ]);
    //     }
    //     return UIKit::div(
    //         $this->header(),
    //         $this->previousLink(),
    //         $this->nextLink(),
    //         ...$render
    //     )->attr("class ef-events-grid ef-events-grid-year");
    // }









    // public function gridItemBlank($uriObject)
    // {
    //     $cc = $this->carbon()->copy()
    //         ->year($uriObject->year())->month($uriObject->month());

    //     $abbr = $cc->format($this->monthAbbrFormat);
    //     $title = $cc->format($this->monthTitleFormat);

    //     return UIKit::button(
    //         UIKit::abbr($abbr)->attr("title {$title}")
    //     )->attr(
    //         "disabled disabled",
    //         "aria-disabled true",
    //         "role presentation"
    //     );
    // }

    // public function year(): int
    // {
    //     return $this->year;
    // }
}
