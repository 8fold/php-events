<?php

namespace Eightfold\Events\Tests;

use PHPUnit\Framework\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\ShoopShelf\Shoop;

use Eightfold\Events\Events;
use Eightfold\Events\Grid;

class GridTest extends TestCase
{
    private $path = "";

    public function setUp(): void
    {
        $this->path = Shoop::this(__DIR__)->divide("/")
            ->append(["test-events", "events"])->asString("/");
    }

// -> totals for rendering

    /**
     * @test
     */
    public function testTotalGridItems()
    {
        AssertEquals::applyWith(
            12,
            "integer",
            3.39, // 1.63, // 1.31, // 1.12, // 1.1, // 0.91, // 0.88, // 0.85, // 0.76, // 0.66, // 0.55, // 0.51, // 0.5, // 0.49,
            103 // 98 // 25
        )->unfoldUsing(
            Grid::forYear($this->path->unfold(), 2020)->totalGridItems()
        );

        AssertEquals::applyWith(
            4,
            "integer",
            10.82, // .87,
            1909 // 1883 // 1882 // 1881
        )->unfoldUsing(
            Grid::forMonth($this->path->unfold(), 2020, 5)->totalStartGridBlanks()
        );

        AssertEquals::applyWith(
            31,
            "integer",
            0.81, // 0.72, // 0.49, // 0.41,
            25
        )->unfoldUsing(
            Grid::forMonth($this->path->unfold(), 2020, 5)->daysInMonth()
        );

        AssertEquals::applyWith(
            0,
            "integer",
            1.13, // 0.68, // 0.32, // 0.15, // 0.14,
            1
        )->unfoldUsing(
            Grid::forMonth($this->path->unfold(), 2020, 5)->totalEndGridBlanks()
        );

        // break
        AssertEquals::applyWith(
            2,
            "integer",
            0.45, // 0.32, // 0.15, // 0.11, // 0.1, // 0.09,
            1
        )->unfoldUsing(
            Grid::forMonth($this->path->unfold(), 2020, 4)->totalStartGridBlanks()
        );

        AssertEquals::applyWith(
            30,
            "integer",
            0.61, // 0.58, // 0.18, // 0.17, // 0.16, // 0.14,
            1
        )->unfoldUsing(
            Grid::forMonth($this->path->unfold(), 2020, 4)->daysInMonth()
        );


        AssertEquals::applyWith(
            3,
            "integer",
            0.18, // 0.17, // 0.14, // 0.12,
            1
        )->unfoldUsing(
            Grid::forMonth($this->path->unfold(), 2020, 4)->totalEndGridBlanks()
        );
    }

// -> forYear
    /**
     * @test
     */
    public function render_year()
    {
        AssertEquals::applyWith(
            '<h2>2020</h2>',
            "string",
            10.8, // 10.42,
            2167
        )->unfoldUsing(
            Grid::forYear($this->path->unfold(), 2020)->header()
        );
        // $grid = Grid::forYear($this->path->plus("/2020"));

        // $expected = '<h2>2020</h2>';
        // $actual = $grid->header();
        // $this->assertEquals($expected, $actual->unfold());

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
    /**
     * @test
     */
    public function render_month()
    {
        AssertEquals::applyWith(
            '<h2>May 2020</h2>',
            "string",
            22.58,
            2214 // 2209 // 2199 // 2198 // 2197 // 2191 // 2189 // 2188 // 2187 // 2186 // 2185
        )->unfoldUsing(
            Grid::forMonth($this->path->unfold(), 2020, 5)->header()
        );

        AssertEquals::applyWith(
            '<span class="ef-grid-previous-month"></span>',
            "string",
            9.3, // 9.17, // 8.76,
            739 // 738 // 734 // 733
        )->unfoldUsing(
            Grid::forMonth($this->path->unfold(), 2020, 5)->previousLink()
        );

        AssertEquals::applyWith(
            '<a class="ef-grid-previous-month" href="/events/2020/12" title="December 2020"><span>December 2020</span></a>',
            "string",
            46.3, // 36.13, // 15.47, // 15.13, // 13.58, // 13.14, // 10.89, // 9.02, // 8.81, // 7.39,
            94 // 30 // 28
        )->unfoldUsing(
            Grid::forMonth($this->path->unfold(), 2022, 5)->previousLink()
        );

        AssertEquals::applyWith(
            '<span class="ef-grid-next-month"></span>',
            "string",
            5.55, // 4.88, // 4.23, // 3.48, // 3.17, // 2.09, // 1.73, // 1.48, // 1.09, // 1.08, // 1.06, // 0.99,
            64
        )->unfoldUsing(
            Grid::forMonth($this->path->unfold(), 2023, 5)->nextLink()
        );

        AssertEquals::applyWith(
            '<a class="ef-grid-next-month" href="/events/2022/05" title="May 2022"><span>May 2022</span></a>',
            "string",
            32.18, // 24.39, // 22.85, // 21.31,
            2959 // 2958
        )->unfoldUsing(
            Grid::forMonth($this->path->unfold(), 2020, 12)->nextLink()
        );

        AssertEquals::applyWith(
            '<button role="presentation" aria-disabled="true" disabled><abbr title="1st of February 2020">1</abbr></button>',
            "string",
            24.96, // 20.39,
            2955
        )->unfoldUsing(
            Grid::forMonth($this->path->unfold(), 2020, 2)->gridItem(1)
        );

        AssertEquals::applyWith(
            '<button id="toggle-20200522" class="calendar-date" onclick="EFEventsModals.init(this, 20200522)" aria-expanded="false"><abbr title="22nd of May 2020">22</abbr><span>Hello, Event!</span><span>Hello, Day?</span></button>',
            "string",
            25.5, // 24.31, // 23.61,
            2994
        )->unfoldUsing(
            Grid::forMonth($this->path->unfold(), 2020, 5)->gridItem(22)
        );
    }
}
