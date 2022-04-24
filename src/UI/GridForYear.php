<?php

declare(strict_types=1);

namespace Eightfold\Events\UI;

use Eightfold\HTMLBuilder\Element as HtmlElement;

use DateTime;

// use Carbon\Carbon;

use Eightfold\Events\Data\Year;

use Eightfold\Events\Events;

use Eightfold\Events\Implementations\Root as RootImp;
use Eightfold\Events\Implementations\Events as EventsImp;
// use Eightfold\Events\Implementations\Carbon as CarbonImp;
use Eightfold\Events\Implementations\Parts as PartsImp;
use Eightfold\Events\Implementations\Render as RenderImp;
use Eightfold\Events\Implementations\Year as YearImp;

class GridForYear
{
    use RootImp;
    use EventsImp;
    // use CarbonImp;
    use PartsImp;
    use RenderImp;
    use YearImp;

    private $carbon;

    private string $yearTitleFormat = 'Y';

    private string $monthAbbrFormat = 'M';

    protected string $monthTitleFormat = 'F Y';

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

    public function carbon(): DateTime
    {
        if ($this->carbon === null) {
            $this->carbon = (new DateTime())->setDate(
                $this->year(),
                1,
                10
            );
            // $this->carbon = Carbon::now()
            //     ->year($this->year())->month(1)->day(10)
            //     ->startOfWeek(Carbon::MONDAY);
        }
        return $this->carbon;
    }

    public function totalGridItems(): int
    {
        return Year::totalMonthsInYear();
    }

    public function header(): HtmlElement
    {
        $title = $this->carbon()->copy()->format($this->yearTitleFormat);
        return HtmlElement::h2($title);
    }

    public function previousLink(): HtmlElement
    {
        $year = $this->events()->previousYearWithEvents($this->year());
        $title = '';

        if (is_object($year)) {
            $format = $this->yearTitleFormat;

            $carbon = clone $this->carbon();
            die(var_dump($carbon));
            $title = $this->carbon()->copy()->year($year->year())
                ->format($format);
        }

        return $this->navLink($year, $title, 'ef-grid-previous-year');
    }

    public function nextLink(): HtmlElement
    {
        $year = $this->events()->nextYearWithEvents($this->year());
        $title = '';

        if (is_object($year)) {
            $format = $this->yearTitleFormat;
            $title = $this->carbon()->copy()->year($year->year())
                ->format($format);
        }

        return $this->navLink($year, $title, 'ef-grid-next-year');
    }

    public function gridItem(int $itemNumber): HtmlElement
    {
        $year = $this->events()->year($this->year());
        if (! $year) {
            return $this->gridItemBlank($itemNumber);
        }

        $month = $this->events()->month($this->year(), $itemNumber);
        if (! is_object($month) or (is_object($month) and ! $month->hasEvents())) {
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
                ->props('title ' . $title),
            HtmlElement::span($total)
        )->props(
            'href ' . $this->uriPrefix() . $month->uri()
        );
    }

    public function gridItemBlank(int $itemNumber): HtmlElement
    {
        $cc = $this->carbon()->copy()
            ->year($this->year())->month($itemNumber);

        $abbr = $cc->format($this->monthAbbrFormat);
        $title = $cc->format($this->monthTitleFormat);

        return HtmlElement::button(
            HtmlElement::abbr($abbr)->props("title {$title}")
        )->props(
            'disabled disabled',
            'aria-disabled true',
            'role presentation'
        );
    }

    public function unfold(): string
    {
        $range = range(1, $this->totalGridItems());
        $items = [];
        foreach ($range as $itemNumber) {
            $items[] = $this->gridItem($itemNumber);
        }

        return HtmlElement::div(
            $this->header(),
            $this->previousLink(),
            $this->nextLink(),
            ...$items
        )->props('class ef-events-grid ef-events-grid-year')->build();
    }
}
