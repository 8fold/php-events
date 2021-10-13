<?php

declare(strict_types=1);

namespace Eightfold\Events\UI;

use Eightfold\HTMLBuilder\Element as HtmlElement;

// use Eightfold\Events\UI\GridAbstract;
// use Eightfold\Foldable\Fold;
// use Eightfold\Foldable\Foldable;

use Carbon\Carbon;

// use Eightfold\Markup\UIKit;
// use Eightfold\Shoop\Shoop;

use Eightfold\Events\Data\Year;

use Eightfold\Events\Events;

use Eightfold\Events\Implementations\Root as RootImp;
use Eightfold\Events\Implementations\Render as RenderImp;
use Eightfold\Events\Implementations\Year as YearImp;

class GridForYear // extends GridAbstract
{
    use RootImp;
    use RenderImp;
    use YearImp;

    private $carbon;

    private $events;

    private $yearTitleFormat = "Y";

    private $monthAbbrFormat = "M";

    protected $monthTitleFormat = "F Y";

    public static function fold(
        string $root,
        int $year,
        string $uriPrefix = '/events'
    ): GridForYear {
        return new GridForYear($root, $year);
    }

    public function __construct(
        string $root,
        int $year,
        string $uriPrefix = '/events'
    ) {
        $this->root = $root;
        $this->parts = [$year];
        $this->uriPrefix = $uriPrefix;
    }

    public function carbon()
    {
        if ($this->carbon === null) {
            $this->carbon = Carbon::now()
                ->year($this->year())->month(1)->day(10)
                ->startOfWeek(Carbon::MONDAY);
        }
        return $this->carbon;
    }

    public function totalGridItems(): int
    {
        return Year::totalMonthsInYear();
    }

    public function events()
    {
        if ($this->events === null) {
            $this->events = Events::fold($this->root());
        }
        return $this->events;
    }

    public function header()
    {
        $title = $this->carbon()->copy()->format($this->yearTitleFormat);
        return HtmlElement::h2($title);
    }

    public function previousLink()
    {
        $year = $this->events()->previousYearWithEvents($this->year());
        $title = '';

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

    public function gridItem(int $itemNumber)
    {
        $year = $this->events()->year($this->year());
        if (! $year) {
            return $this->gridItemBlank($itemNumber);
        }

        $month = $this->events()->month($this->year(), $itemNumber);
        if (! $month or ! $month->hasEvents()) {
            return $this->gridItemBlank($itemNumber);
        }

        $cc = $this->carbon()->copy()
            ->year($month->year())
            ->month($month->month());

        $abbr   = $cc->format($this->monthAbbrFormat);
        $title  = $cc->format($this->monthTitleFormat);
        $total = strval($month->count());

        return HtmlElement::a(
            HtmlElement::abbr($abbr)
                ->props("title ". $title),
            HtmlElement::span($total)
        )->props(
            "href ". $this->uriPrefix() . $month->uri()
        );
    }

    public function gridItemBlank(int $itemNumber)
    {
        $cc = $this->carbon()->copy()
            ->year($this->year())->month($itemNumber);

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

    public function unfold(): string
    {
        $range = range(1, $this->totalGridItems());
        $items = Shoop::this($range)->each(function($month) {
            return $this->gridItem($month);
        })->unfold();
        return UIKit::div(
            $this->header(),
            $this->previousLink(),
            $this->nextLink(),
            ...$items
        )->attr("class ef-events-grid ef-events-grid-year");
    }




    // public function carbon()
    // {
    //     if ($this->carbon === null) {
    //         $this->carbon = Carbon::now()
    //             ->year($this->year(false));
    //     }
    //     return $this->carbon;
    // }





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
        // return UIKit::div(
        //     $this->header(),
        //     $this->previousLink(),
        //     $this->nextLink(),
        //     ...$render
        // )->attr("class ef-events-grid ef-events-grid-year");
    // }











    // public function year(): int
    // {
    //     return $this->year;
    // }
}
