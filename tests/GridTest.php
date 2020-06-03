<?php

namespace Eightfold\Events\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\Shoop\Shoop;
use Eightfold\Events\Events;
use Eightfold\Events\Grid;

class GridTest extends TestCase
{
    private $path = "";

    public function setUp(): void
    {
        $this->path = Shoop::string(__DIR__)->divide("/")
            ->plus("test-events", "events")->join("/");
    }

// -> totals for renddering
    public function testTotalGridItems()
    {
        $grid = Grid::forYear($this->path->plus("/2020"));

        $this->assertEquals(12, $grid->totalGridItems());

        $grid = Grid::forMonth($this->path->plus("/2020/05"));

        $this->assertEquals(4, $grid->totalStartGridBlanks());

        $this->assertEquals(31, $grid->totalDaysInMonth());

        $this->assertEquals(0, $grid->totalEndGridBlanks());

        $grid = Grid::forMonth($this->path->plus("/2020/04"));

        $this->assertEquals(2, $grid->totalStartGridBlanks());

        $this->assertEquals(30, $grid->totalDaysInMonth());

        $this->assertEquals(3, $grid->totalEndGridBlanks());

    }

// -> forYear
    public function testRenderYear()
    {
        $grid = Grid::forYear($this->path->plus("/2020"));

        $expected = '<h2>2020</h2>';
        $actual = $grid->header();
        $this->assertEquals($expected, $actual->unfold());

        // $expected = '<span class="ef-grid-previous-year"></span>';
        // $actual = $grid->previousLink();
        // $this->assertEquals($expected, $actual->unfold());

        // $gridFuture = Grid::forYear($this->path->plus("/2021"));
        // $expected = '<a class="ef-grid-previous-year" href="/events/2020" title="2020"><span>2020</span></a>';
        // $actual = $gridFuture->previousLink();
        // $this->assertEquals($expected, $actual->unfold());

        // $gridFuture = Grid::forYear($this->path->plus("/2023"));
        // $expected = '<span class="ef-grid-next-year"></span>';
        // $actual = $gridFuture->nextLink();
        // $this->assertEquals($expected, $actual->unfold());

        // $expected = '<a class="ef-grid-next-year" href="/events/2022" title="2022"><span>2022</span></a>';
        // $actual = $grid->nextLink();
        // $this->assertEquals($expected, $actual->unfold());

        // $month = Events::init($this->path)->year(2020)->month(1);
        // $expected = '<button aria-disabled="true" disabled><abbr title="January 2020">Jan</abbr></button>';
        // $actual = $grid->gridItem($month);
        // $this->assertEquals($expected, $actual->unfold());

        // $month = Events::init($this->path)->year(2020)->month(5);
        // $expected = '<a href="/events/2020/05"><abbr title="May 2020">May</abbr><span>8</span></a>';
        // $actual = $grid->gridItem($month);
        // $this->assertEquals($expected, $actual->unfold());
    }

// -> forMonth
    public function testRenderMonth()
    {
        $grid = Grid::forMonth($this->path->plus("/2020/05"));

        $expected = '<h2>May 2020</h2>';
        $actual = $grid->header();
        $this->assertEquals($expected, $actual->unfold());

        $expected = '<span class="ef-grid-previous-month"></span>';
        $actual = $grid->previousLink();
        $this->assertEquals($expected, $actual->unfold());

        $gridFuture = Grid::forMonth($this->path->plus("/2023/05"));
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

        $expected = '<button id="toggle-20200522" class="calendar-date" onclick="EFEventsModals.init(this, 20200522)"><abbr title="22nd of May 2020">22</abbr><span>Hello, Event!</span><span>Hello, Day?</span></button>';
        $actual = $grid->gridItem(
            $events->year(2020)->month(5)->day(22)
        );
        $this->assertEquals($expected, $actual->unfold());
    }
}
