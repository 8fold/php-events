<?php

declare(strict_types=1);

namespace Eightfold\Events\UI;

use DateTime;

use Eightfold\HTMLBuilder\Element as HtmlElement;
use Eightfold\Markdown\Markdown;

use Eightfold\Events\Events;

use Eightfold\Events\Implementations\Root as RootImp;
use Eightfold\Events\Implementations\Events as EventsImp;
use Eightfold\Events\Implementations\Render as RenderImp;
use Eightfold\Events\Implementations\Parts as PartsImp;
use Eightfold\Events\Implementations\Year as YearImp;
use Eightfold\Events\Implementations\Month as MonthImp;

use Eightfold\Events\Data\Date;

class GridForMonth
{
    use RootImp;
    use EventsImp;
    use RenderImp;
    use PartsImp;
    use YearImp;
    use MonthImp;

    private DateTime $carbon;

    private int $weeksToDisplay = 6;

    // private string $monthAbbrFormat = 'M';
    private string $monthTitleFormat = 'F Y';

    private string $dayAbbrFormat = 'j';
    private string $dayTitleFormat = 'jS \\of F Y';

    public function __construct(
        string $root,
        int $year,
        int $month,
        string $uriPrefix = '/events'
    ) {
        $this->root = $root;
        $this->parts = [$year, $month];
        $this->uriPrefix = $uriPrefix;

        $endBlanks = $this->totalEndGridBlanks();
        if ($endBlanks >= 7) {
            $this->weeksToDisplay -= 1;
        }
    }

    public function carbon(): DateTime
    {
        if (isset($this->carbon) === false) {
            $this->carbon = (new DateTime())->setDate(
                $this->year(),
                $this->month(),
                10
            );
        }
        return $this->carbon;
    }

    public function totalStartGridBlanks(): int
    {
        $carbon = clone $this->carbon();
        $carbon = $carbon->modify('first day of this month');
        return intval($carbon->format('N')) - 1;
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

// -> rendering
    public function header(): HtmlElement
    {
        $cc = clone $this->carbon();
        $title = $cc->format($this->monthTitleFormat);
        return HtmlElement::h2($title);
    }

    public function previousLink(): HtmlElement
    {
        $events = $this->events();
        $month = false;
        $title = '';
        if ($events !== null) {
            $month = $events->previousMonthWithEvents(
                $this->year(),
                $this->month()
            );

            if ($month !== false) {
                $format = $this->monthTitleFormat;

                $cc = clone $this->carbon();
                $title = $cc->setDate($month->year(), $month->month(), 1)
                    ->format($format);
            }
        }
        return $this->navLink($month, $title, 'ef-grid-previous-month');
    }

    public function nextLink(): HtmlElement
    {
        $events = $this->events();
        $month = false;
        $title = '';

        if ($events !== null) {
            $month = $events->nextMonthWithEvents(
                $this->year(),
                $this->month()
            );

            if (is_object($month)) {
                $format = $this->monthTitleFormat;

                $cc = clone $this->carbon();
                $cc->setDate($month->year(), $month->month(), 1);

                $title = $cc->format($format);
            }
        }
        return $this->navLink($month, $title, 'ef-grid-next-month');
    }

    public function gridItem(int $itemNumber): HtmlElement
    {
        $events = $this->events();
        $e = [];
        if ($events !== null) {
            $month = $events->month($this->year(), $this->month());
            if (! $month) {
                return $this->gridItemBlank($itemNumber);
            }

            $date = $events->date($this->year(), $this->month(), $itemNumber);
            if (! is_object($date) or (is_object($date) and ! $date->hasEvents())) {
                return $this->gridItemBlank($itemNumber);
            }

            $cc = clone $this->carbon();
            $cc->setDate($date->year(), $date->month(), $date->date());

            $id     = $cc->format('Y') . $cc->format('m') . $cc->format('d');
            $abbr   = $cc->format('j');
            $title  = $cc->format($this->dayTitleFormat);

            foreach ($date->content() as $event) {
                $e[] = HtmlElement::span($event->title());
            }

            return HtmlElement::button(
                HtmlElement::abbr($abbr)->props('title ' . $title),
                ...$e
            )->props(
                'id toggle-' . $id,
                'aria-expanded false',
                'class calendar-date',
                'onclick EFEventsModals.init(this, ' . $id . ')'
            );
        }
        return $this->gridItemBlank($itemNumber);
    }

    public function gridItemBlank(int $itemNumber): HtmlElement
    {
        $cc = clone $this->carbon();
        $cc = $cc->setDate($this->year(), $this->month(), $itemNumber);

        $abbr = $cc->format($this->dayAbbrFormat);
        $title = $cc->format($this->dayTitleFormat);

        return HtmlElement::button(
            HtmlElement::abbr($abbr)->props('title ' . $title)
        )->props(
            'disabled disabled',
            'aria-disabled true',
            'role presentation'
        );
    }

    public function bookEndBlank(): HtmlElement
    {
        return HtmlElement::button()->props(
            'disabled disabled',
            'aria-disabled true',
            'role presentation'
        );
    }

    /**
     * @param Date|bool|boolean $date
     * @return HtmlElement|string       [description]
     */
    private function eventsModalItem($date)
    {
        if (is_object($date)) {
            $eventParts = [];
            foreach ($date->content() as $event) {
                $title = $event->title();
                $body  = $event->body();
                if (! empty($body)) {
                    $eventParts[] = HtmlElement::h4($title);
                    $eventParts[] = Markdown::create()
                        ->minified()->convert($body);
                }
                $eventParts[] = '';
            }

            $filtered   = array_filter($eventParts);
            $filtered[] = HtmlElement::button(
                HtmlElement::span('close')
            )->props('onclick EFEventsModals.closeAll()');

            $year  = $date->year();
            $month = $date->month();
            $day   = $date->date();

            $yearString = $date->yearString();
            $monthString = $date->monthString();
            $dateString  = $date->dateString();

            $id    = "id {$yearString}{$monthString}{$dateString}";

            $heading = (new DateTime())->setDate($year, $month, $day)
                ->format($this->dayTitleFormat);

            return HtmlElement::div(
                HtmlElement::h3($heading),
                ...$filtered
            )->props($id, 'role dialog');
        }
        return '';
    }

    /**
     * @return array<HtmlElement> [description]
     */
    public function dayTitles(): array
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
                $events = $this->events();
                if ($events !== null) {
                    $date = $events->date($this->year(), $this->month(), $i);

                    if ($date) {
                        $eventItems[] = $this->eventsModalItem($date);
                    }

                    // blank date grid items
                    // event date grid items
                    $gItems[] = $this->gridItem($i);
                }
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
