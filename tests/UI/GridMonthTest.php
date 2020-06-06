<?php

namespace Eightfold\Events\Tests\UI;

use PHPUnit\Framework\TestCase;

use Eightfold\Shoop\Shoop;
use Eightfold\Events\Events;
use Eightfold\Events\UI\GridForMonth;

class GridMonthTest extends TestCase
{
    private $path = "";

    public function setUp(): void
    {
        $this->path = Shoop::string(__DIR__)->divide("/")->dropLast()
            ->plus("test-events", "events")->join("/");
    }

// -> forMonth
    public function testRenderHeader()
    {
        $grid = GridForMonth::forMonth($this->path->plus("/2020/05"));

        $expected = '<h2>May 2020</h2>';
        $actual = $grid->header();
        $this->assertEquals($expected, $actual->unfold());
    }

    public function testRenderPreviousLink()
    {
        $grid = GridForMonth::forMonth($this->path->plus("/2020/05"));

        $expected = '<span class="ef-grid-previous-month"></span>';
        $actual = $grid->previousLink();
        $this->assertEquals($expected, $actual->unfold());

        $gridFuture = GridForMonth::forMonth($this->path->plus("/2023/05"));
        $expected = '<span class="ef-grid-next-month"></span>';
        $actual = $gridFuture->nextLink();
        $this->assertEquals($expected, $actual->unfold());

        $expected = '<a class="ef-grid-next-month" href="/events/2020/12" title="December 2020"><span>December 2020</span></a>';
        $actual = $grid->nextLink();
        $this->assertEquals($expected, $actual->unfold());

        $events = Events::init($this->path);
        $expected = '<button aria-disabled="true" disabled><abbr title="1st of February 2020">1</abbr></button>';
        $actual = $grid->gridItem(
            $events->year(2020)->month(2)->day(1)
        );
        $this->assertEquals($expected, $actual->unfold());

        $expected = '<button id="toggle-20200522" class="calendar-date" onclick="EFEventsModals.init(this, 20200522)" aria-expanded="false"><abbr title="22nd of May 2020">22</abbr><span>Hello, Event!</span><span>Hello, Day?</span></button>';
        $actual = $grid->gridItem(
            $events->year(2020)->month(5)->day(22)
        );
        $this->assertEquals($expected, $actual->unfold());
    }
}
