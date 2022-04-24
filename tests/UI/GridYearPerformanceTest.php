<?php

declare(strict_types=1);

namespace Eightfold\Events\Tests\UI;

use PHPUnit\Framework\TestCase;

use Eightfold\Events\Grid;

use Eightfold\FileSystem\Item;

class GridYearPerformanceTest extends TestCase
{
    private string $path = '';

    private string $grid = '';

    public function setUp(): void
    {
        $this->path = Item::create(__DIR__)->up()
            ->append('test-events', 'events')
            ->thePath();

        $this->grid = '<div class="ef-events-grid ef-events-grid-year"><h2>2020</h2><span class="ef-grid-previous-year"></span><a class="ef-grid-next-year" href="/events/2022" title="2022"><span>2022</span></a><button role="presentation" aria-disabled="true" disabled><abbr title="January 2020">Jan</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="February 2020">Feb</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="March 2020">Mar</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="April 2020">Apr</abbr></button><a href="/events/2020/05"><abbr title="May 2020">May</abbr><span>5</span></a><button role="presentation" aria-disabled="true" disabled><abbr title="June 2020">Jun</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="July 2020">Jul</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="August 2020">Aug</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="September 2020">Sep</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="October 2020">Oct</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="November 2020">Nov</abbr></button><a href="/events/2020/12"><abbr title="December 2020">Dec</abbr><span>3</span></a></div>';
    }

    /**
     * @test
     *
     * @group ui
     * @group year
     */
    public function year_grid_is_speedy(): void
    {
        $start = hrtime(true);

        $result = Grid::forYear($this->path, 2020)->unfold();

        $end = hrtime(true);

        $this->assertEquals(
            $this->grid,
            $result
        );

        $elapsed = $end - $start;
        $ms      = $elapsed/1e+6;

        $this->assertLessThan(6, $ms); // previous 1835.7ms
    }

    /**
     * @test
     *
     * @group ui
     * @group year
     */
    public function year_grid_is_small(): void
    {
        $start = memory_get_usage();

        $result = Grid::forYear($this->path, 2020, 5)->unfold();

        $end = memory_get_usage();

        $this->assertEquals(
            $this->grid,
            $result
        );

        $used = $end - $start;
        $kb   = round($used/1024.2);

        $this->assertLessThan(3, $kb); // previous 4165kb
    }
}
