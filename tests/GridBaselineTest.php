<?php

declare(strict_types=1);

namespace Eightfold\Events\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\Events\Grid;

use Eightfold\FileSystem\Item;

class DateBaselineTest extends TestCase
{
    private string $path = '';

    public function setUp(): void
    {
        $this->path = Item::create(__DIR__)->append('test-events', 'events')
            ->thePath();
    }

    /**
     * @test
     *
     * @group ui
     * @group grid
     * @group month
     */
    public function grid_render_month(): void
    {
        $this->assertEquals(
            '<h2>May 2020</h2>',
            Grid::forMonth($this->path, 2020, 5)->header()->build()
        );

        $this->assertEquals(
            '<span class="ef-grid-previous-month"></span>',
            Grid::forMonth($this->path, 2020, 5)->previousLink()->build()
        );
    }

    /**
     * @test
     *
     * @group ui
     * @group grid
     * @group year
     */
    public function grid_render_year(): void
    {
        $this->assertEquals(
            '<h2>2020</h2>',
            Grid::forYear($this->path, 2020)->header()->build()
        );
    }

    /**
     * @test
     *
     * @group ui
     * @group grid
     */
    public function grid_total_items(): void
    {
        $this->assertEquals(
            12,
            Grid::forYear($this->path, 2020)->totalGridItems()
        );

        $this->assertEquals(
            4,
            Grid::forMonth($this->path, 2020, 5)->totalStartGridBlanks()
        );

        $this->assertEquals(
            31,
            Grid::forMonth($this->path, 2020, 5)->daysInMonth()
        );

        $this->assertEquals(
            0,
            Grid::forMonth($this->path, 2020, 5)->totalEndGridBlanks()
        );

        $this->assertEquals(
            2,
            Grid::forMonth($this->path, 2020, 4)->totalStartGridBlanks()
        );

        $this->assertEquals(
            30,
            Grid::forMonth($this->path, 2020, 4)->daysInMonth()
        );

        $this->assertEquals(
            3,
            Grid::forMonth($this->path, 2020, 4)->totalEndGridBlanks()
        );
    }
}
