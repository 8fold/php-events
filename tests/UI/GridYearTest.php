<?php

namespace Eightfold\Events\Tests\UI;

use PHPUnit\Framework\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\Shoop\Shoop;
use Eightfold\Events\Events;
use Eightfold\Events\Data\Month;
use Eightfold\Events\UI\GridForYear;

class GridYearTest extends TestCase
{
    private $path = "";

    public function setUp(): void
    {
        $this->path = Shoop::this(__DIR__)->divide("/")->dropLast()
            ->append(["test-events", "events"])->asString("/");
    }

// -> forYear
    /**
     * @test
     */
    public function render_header()
    {
        AssertEquals::applyWith(
            '<h2>2020</h2>',
            "string",
            11.29,
            2163
        )->unfoldUsing(
            GridForYear::fold($this->path->unfold(), 2020)->header()
        );
    }

    /**
     * @test
     */
    public function next_and_previous_link()
    {
        AssertEquals::applyWith(
            '<span class="ef-grid-previous-year"></span>',
            "string",
            8.7,
            590 // 589 // 588
        )->unfoldUsing(
            GridForYear::fold($this->path->unfold(), 2020)->previousLink()
        );

        AssertEquals::applyWith(
            '<a class="ef-grid-previous-year" href="/events/2020" title="2020"><span>2020</span></a>',
            "string",
            14.45, // 12.78,
            2955
        )->unfoldUsing(
            GridForYear::fold($this->path->unfold(), 2021)->previousLink()
        );

        AssertEquals::applyWith(
            '<a class="ef-grid-next-year" href="/events/2022" title="2022"><span>2022</span></a>',
            "string",
            6.52, // 6.04, // 4.47, // 4.02, // 3.5, // 3.39, // 3.33, // 3.16,
            1
        )->unfoldUsing(
            GridForYear::fold($this->path->unfold(), 2021)->nextLink()
        );
    }

    /**
     * @test
     */
    public function grid_item()
    {
        AssertEquals::applyWith(
            '<a href="/events/2020/05"><abbr title="May 2020">May</abbr><span>4</span></a>',
            "string",
            21, // 19.31, // 19.23, // 18.49,
            2973
        )->unfoldUsing(
            GridForYear::fold($this->path->unfold(), 2020)->gridItem(5)
        );
    }
}
