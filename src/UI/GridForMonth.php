<?php

declare(strict_types=1);

namespace Eightfold\Events\UI;

// use Eightfold\Events\UI\GridAbstract;

use Carbon\Carbon;

use Eightfold\HTMLBuilder\Element as HtmlElement;

use Eightfold\Events\Events;

// use Eightfold\Markup\UIKit;
// use Eightfold\ShoopShelf\Shoop;

use Eightfold\Events\Implementations\Root as RootImp;
use Eightfold\Events\Implementations\Render as RenderImp;
use Eightfold\Events\Implementations\Parts as PartsImp;
use Eightfold\Events\Implementations\Year as YearImp;
use Eightfold\Events\Implementations\Month as MonthImp;

use Eightfold\Events\Data\Date;

class GridForMonth // extends GridAbstract
{
    use RootImp;
    use RenderImp;
    use PartsImp;
    use YearImp;
    use MonthImp;

    private $events;

    private $carbon;

    private $weeksToDisplay = 6;

    private $monthAbbrFormat = "M";
    private $monthTitleFormat = "F Y";

    private $dayAbbrFormat = "j";
    private $dayTitleFormat = "jS \\of F Y";

    public static function fold(
        string $root,
        int $year,
        int $month,
        string $uriPrefix = '/events'
    ): GridForMonth {
        return new GridForMonth($root, $year, $month);
    }

    public function __construct(string $root, int $year, int $month)
    {
        $this->root = $root;
        $this->parts = [$year, $month];

        $endBlanks = $this->totalEndGridBlanks();
        if ($endBlanks >= 7) {
            $this->weeksToDisplay -= 1;
        }
    }

    public function events()
    {
        if ($this->events === null) {
            $this->events = Events::fold($this->root());
        }
        return $this->events;
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
        return HtmlElement::h2($title);
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

        $id     = $cc->format("Y") . $cc->format('m') . $cc->format('d');
        $abbr   = $cc->format("j");
        $title  = $cc->format($this->dayTitleFormat);

        $events = [];
        foreach ($date->content() as $event) {
            $events[] = HtmlElement::span($event->title());
        }

        return HtmlElement::button(
                HtmlElement::abbr($abbr)->props('title ' . $title),
                ...$events
            )->props(
                'id toggle-' . $id,
                'aria-expanded false',
                'class calendar-date',
                'onclick EFEventsModals.init(this, ' . $id . ')'
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

        return HtmlElement::button(
            HtmlElement::abbr($abbr)->props("title ". $title)
        )->props(
            "disabled disabled",
            "aria-disabled true",
            "role presentation"
        );
    }

    public function bookEndBlank()
    {
        return HtmlElement::button()->props(
            'disabled disabled',
            'aria-disabled true',
            'role presentation'
        );
    }

    private function eventsModalItem(Date $date)
    {
        if (! $date->hasEvents()) {
            return [''];
        }

        $eventParts = [];
        foreach ($date->content() as $event) {
            $title = $event->title();
            $body  = $event->body();
            if (! empty($body)) {
                $eventParts[] = HtmlElement::h4($title);
                $eventParts[] = HtmlElement::markdown($body);
            }
            $eventParts[] = '';
        }

        $filtered = array_filter($eventParts);
        $filtered[] = HtmlElement::button(
            HtmlElement::span('close')
        )->props('onclick EFEventsModals.closeAll()');

        $year  = $date->year();
        $month = $date->month();
        $day   = $date->date();
        $id = "id {$year}{$month}{$day}";
        $heading = Carbon::now()->year($year)->month($month)->day($day)
            ->format($this->dayTitleFormat);
        return HtmlElement::div(
            HtmlElement::h3($heading),
            ...$eventParts
        )->props($id, 'role dialog');
    }

    public function dayTitles()
    {
        $abbr = [
            'Mon' => 'Monday',
            'Tue' => 'Tuesday',
            'Wed' => 'Wednesday',
            'Thu' => 'Thursday',
            'Fri' => 'Friday',
            'Sat' => 'Saturday',
            'Sun' => 'Sunday'
        ];

        $b = [];
        foreach ($abbr as $short => $long) {
            $b[] = HtmlElement::abbr($short)
                ->props("title {$long}", 'class ef-weekday-heading');
        }

        return $b;
    }

    public function unfold(): string
    {
        $totalGridItems = $this->totalGridItems();
        $startingBlanks = $this->totalStartGridBlanks(); // start blank grid items
        $endingBlanks = $this->totalEndGridBlanks(); // end blank grid items

        $eventItems = [];
        $gridItems = range(1, $totalGridItems);
        $gItems = [];
        foreach ($gridItems as $itemNumber) {
            if ($itemNumber <= $startingBlanks) {
                $gItems[] = $this->bookEndBlank();

            } elseif ($itemNumber > ($totalGridItems - $endingBlanks)) {
                $gItems[] = $this->bookEndBlank();

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
                $gItems[] = $this->gridItem($i);

            }
        }

        $filtered = array_filter($gItems);

        return HtmlElement::div(
                $this->header(),
                $this->previousLink(),
                $this->nextLink(),
                HtmlElement::div(...$eventItems)->props(
                    'id ef-events-modals',
                    'onclick EFEventsModals.closeAll()',
                    'aria-hidden true'
                ),
                ...array_merge($this->dayTitles(), $gItems)
            )->props('class ef-events-grid ef-events-grid-month', 'aria-live assertive')
        ->build();
    }
}
