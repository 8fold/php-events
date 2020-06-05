<?php

namespace Eightfold\Events\UI;

use Carbon\Carbon;

use Eightfold\Markup\UIKit;
use Eightfold\Shoop\Shoop;

use Eightfold\Events\Events;
use Eightfold\Events\Data\Month;

use Eightfold\Events\UI\Interfaces\Render;
use Eightfold\Events\UI\Traits\RenderImp;

use Eightfold\Events\UI\Interfaces\Formats;
use Eightfold\Events\UI\Traits\FormatsImp;

use Eightfold\Events\UI\Interfaces\Properties;
use Eightfold\Events\UI\Traits\PropertiesImp;

use Eightfold\Events\UI\Interfaces\Numbers;
use Eightfold\Events\UI\Traits\NumbersImp;

class GridForYear implements Render, Formats, Properties, Numbers
{
    use RenderImp, FormatsImp, PropertiesImp, NumbersImp;

    static public function forYear(
        string $path,
        string $class = \Eightfold\Events\UI\GridForYear::class
    )
    {
        return new $class($path);
    }

    public function __construct(string $path)
    {
        $eventsPath = Shoop::string($path)->divide("/")->dropLast()->join("/");
        $this->events = Events::init($eventsPath);
        $this->path = $path;
        $this->year = Shoop::string($this->path)->divide("/")->last()->int;
        $this->carbon = Carbon::now()->year($this->year);
    }

    public function unfold()
    {
        return $this->render();
    }

    public function render()
    {
        $hasEvents = Shoop::bool(false);
        $months = Shoop::int($this->totalGridItems())->range(1)
            ->each(function($month) use ($hasEvents) {
                $month = $this->events()->year($this->year())->month($month);
                if ($month->hasEvents()->notUnfolded()) {
                    $hasEvents = Shoop::bool(true);
                }
                return $this->gridItem($month);
            });

        $render = $hasEvents->is(false, function($result) use ($months) {
                if ($result) {
                    return $months;
                }
                return Shoop::array([
                    UIKit::p("No events found.")->attr("class ef-events-empty")
                ]);
            });

        return UIKit::div(
            $this->header(),
            $this->previousLink(),
            $this->nextLink(),
            ...$render
        )->attr("class ef-events-grid ef-events-grid-year");
    }

    public function header()
    {
        $title = $this->carbon()->copy()->format($this->yearTitleFormat);
        return UIKit::h2($title);
    }

    public function previousLink()
    {
        $year = $this->events()->previousYearWithEvents($this->year());
        $title = "";
        if ($year !== null) {
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
        if ($year !== null) {
            $format = $this->yearTitleFormat;
            $title = $this->carbon()->copy()->year($year->year())
                ->format($format);
        }
        return $this->navLink($year, $title, "ef-grid-next-year");
    }

    public function gridItem(Month $month)
    {
        $year = $this->events()->year($this->year());
        if ($month->hasEvents()->unfold()) {
            $cc = $this->carbon()->copy()
                ->year($year->year())->month($month->month());
            $total = $year->totalEvents();
            return UIKit::a(
                UIKit::abbr($cc->format($this->monthAbbrFormat))
                    ->attr("title ". $cc->format($this->monthTitleFormat)),
                UIKit::span($total->unfold())
            )->attr(
                "href ". $this->prefix() . $month->uri()
            );
        }
        return $this->gridItemBlank($month);
    }

    public function gridItemBlank($uriObject)
    {
        $cc = $this->carbon()->copy()
            ->year($uriObject->year())->month($uriObject->month());

        $abbr = $cc->format($this->monthAbbrFormat);
        $title = $cc->format($this->monthTitleFormat);

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
}
