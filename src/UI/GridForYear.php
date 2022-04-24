<?php

declare(strict_types=1);

namespace Eightfold\Events\UI;

use Eightfold\HTMLBuilder\Element as HtmlElement;

use DateTime;

use Eightfold\Events\Data\Year;

use Eightfold\Events\Events;

use Eightfold\Events\Implementations\Root as RootImp;
use Eightfold\Events\Implementations\Events as EventsImp;
use Eightfold\Events\Implementations\Parts as PartsImp;
use Eightfold\Events\Implementations\Render as RenderImp;
use Eightfold\Events\Implementations\Year as YearImp;

class GridForYear
{
    use RootImp;
    use EventsImp;
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
        }
        return $this->carbon;
    }

    public function totalGridItems(): int
    {
        return Year::totalMonthsInYear();
    }

    public function header(): HtmlElement
    {
        $cc = clone $this->carbon();

        $title = $cc->format($this->yearTitleFormat);
        return HtmlElement::h2($title);
    }

    public function previousLink(): HtmlElement
    {
        $year = $this->events()->previousYearWithEvents($this->year());
        $title = '';

        if (is_object($year)) {
            $format = $this->yearTitleFormat;

            $cc = clone $this->carbon();
            $cc->setDate($year->year(), 6, 10);

            $title = $cc->format($format);
        }

        return $this->navLink($year, $title, 'ef-grid-previous-year');
    }

    public function nextLink(): HtmlElement
    {
        $year = $this->events()->nextYearWithEvents($this->year());
        $title = '';

        if (is_object($year)) {
            $format = $this->yearTitleFormat;

            $cc = clone $this->carbon();
            $cc->setDate($year->year(), 6, 1);

            $title = $cc->format($format);
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

        $cc = clone $this->carbon();
        $cc->setDate($month->year(), $month->month(), 1);

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
        $cc = clone $this->carbon();
        $cc->setDate($this->year(), $itemNumber, 1);

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
