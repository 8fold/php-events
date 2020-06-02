<?php

namespace Eightfold\Events\Tests\UI;

use PHPUnit\Framework\TestCase;

use Eightfold\Shoop\Shoop;
use Eightfold\Events\Events;
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

    public function testRenderPreviousLink()
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
}
