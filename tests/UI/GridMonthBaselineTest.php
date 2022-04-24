<?php

declare(strict_types=1);

namespace Eightfold\Events\Tests\UI;

use PHPUnit\Framework\TestCase;

use Eightfold\Events\UI\GridForMonth;

use Eightfold\FileSystem\Item;

class GridMonthBaselineTest extends TestCase
{
    private string $path = '';

    public function setUp(): void
    {
        $this->path = Item::create(__DIR__)->up()
            ->append('test-events', 'events')
            ->thePath();
    }

    /**
     * @test
     *
     * @group ui
     * @group month
     */
    public function month_can_go_to_next_or_previous_year_for_next_or_previous_link(): void
    {
        $this->assertEquals(
            '<a class="ef-grid-next-month" href="/events/2022/05" title="May 2022"><span>May 2022</span></a>',
            GridForMonth::fold($this->path, 2020, 12)->nextLink()->build()
        );

        $this->assertEquals(
            '<a class="ef-grid-previous-month" href="/events/2020/12" title="December 2020"><span>December 2020</span></a>',
            GridForMonth::fold($this->path, 2022, 5)->previousLink()->build()
        );
    }

    /**
     * @test
     *
     * @group ui
     * @group month
     */
    public function month_grid_has_next_and_previous_links(): void
    {
        $this->assertEquals(
            '<span class="ef-grid-previous-month"></span>',
            GridForMonth::fold($this->path, 2020, 5)->previousLink()->build()
        );

        $this->assertEquals(
            '<span class="ef-grid-next-month"></span>',
            GridForMonth::fold($this->path, 2023, 5)->nextLink()->build()
        );

        $this->assertEquals(
            '<a class="ef-grid-next-month" href="/events/2022/05" title="May 2022"><span>May 2022</span></a>',
            GridForMonth::fold($this->path, 2020, 12)->nextLink()->build()
        );

        $this->assertEquals(
            '<button role="presentation" aria-disabled="true" disabled><abbr title="1st of February 2020">1</abbr></button>',
            GridForMonth::fold($this->path, 2020, 2)->gridItem(1)->build()
        );

        $this->assertEquals(
            '<button id="toggle-20200522" class="calendar-date" aria-expanded="false" onclick="EFEventsModals.init(this, 20200522)"><abbr title="22nd of May 2020">22</abbr><span>Hello, Event!</span><span>Hello, Day?</span></button>',
            GridForMonth::fold($this->path, 2020, 5)->gridItem(22)->build()
        );
    }

    /**
     * @test
     *
     * @group ui
     * @group month
     */
    public function month_grid_has_expected_title(): void
    {
        $this->assertEquals(
           '<h2>May 2020</h2>',
           GridForMonth::fold($this->path, 2020, 5)->header()->build()
        );
    }
}
