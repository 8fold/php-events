<?php

namespace Eightfold\Events\Tests\UI;

use PHPUnit\Framework\TestCase;

use Eightfold\Shoop\Shoop;
use Eightfold\Events\Events;
use Eightfold\Events\Data\Month;
use Eightfold\Events\UI\GridForYear;

class GridYearTest extends TestCase
{
    private $path = "";

    public function setUp(): void
    {
        $this->path = Shoop::string(__DIR__)->divide("/")->dropLast()
            ->plus("test-events", "events")->join("/");
    }

// -> forYear
    public function testRenderHeader()
    {
        $grid = GridForYear::forYear($this->path->plus("/2020"));

        $expected = '<h2>2020</h2>';
        $actual = $grid->header();
        $this->assertEquals($expected, $actual->unfold());
    }

    public function testRenderNextAndPreviousLink()
    {
        $grid = GridForYear::forYear($this->path->plus("/2020"));

        $expected = '<span class="ef-grid-previous-year"></span>';
        $actual = $grid->previousLink();
        $this->assertEquals($expected, $actual->unfold());

        $gridFuture = GridForYear::forYear($this->path->plus("/2021"));
        $expected = '<a class="ef-grid-previous-year" href="/events/2020" title="2020"><span>2020</span></a>';
        $actual = $gridFuture->previousLink();
        $this->assertEquals($expected, $actual->unfold());

        $expected = '<a class="ef-grid-next-year" href="/events/2022" title="2022"><span>2022</span></a>';
        $actual = $grid->nextLink();
        $this->assertEquals($expected, $actual->unfold());
    }

    public function testGridItem()
    {
        $month = Month::init($this->path->plus("/2020/05"));

        $expected = '<a href="/events/2020/05"><abbr title="May 2020">May</abbr><span>5</span></a>';
        $actual = GridForYear::forYear($this->path->plus("/2020"))
            ->gridItem($month);
        $this->assertEquals($expected, $actual->unfold());
    }
}
