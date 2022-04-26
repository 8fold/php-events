<?php

declare(strict_types=1);

namespace Eightfold\Events\Tests\UI;

use PHPUnit\Framework\TestCase;

use SplFileInfo;

use Eightfold\Events\UI\GridForYear;

class GridYearBaselineTest extends TestCase
{
    private string $path = '';

    public function setUp(): void
    {
        $this->path = (new SplFileInfo(__DIR__ . '/../test-events/events'))
            ->getRealPath();
    }

    /**
     * @test
     *
     * @group ui
     * @group year
     */
    public function year_grid_has_next_links(): void
    {
        $this->assertEquals(
            '<a class="ef-grid-next-year" href="/events/2022" title="2022"><span>2022</span></a>',
            (new GridForYear($this->path, 2021))->nextLink()->build()
        );

        $this->assertEquals(
            '<a href="/events/2020/05"><abbr title="May 2020">May</abbr><span>5</span></a>',
            (new GridForYear($this->path, 2020))->gridItem(5)->build()
        );

        $this->assertEquals(
            '<a class="ef-grid-next-year" href="/events/2020" title="2020"><span>2020</span></a>',
            (new GridForYear($this->path, 2019))->nextLink()->build()
        );
    }

    /**
     * @test
     *
     * @group ui
     * @group year
     */
    public function year_grid_has_previous_links(): void
    {
        $this->assertEquals(
            '<span class="ef-grid-previous-year"></span>',
            (new GridForYear($this->path, 2020))->previousLink()->build()
        );

        $this->assertEquals(
            '<a class="ef-grid-previous-year" href="/events/2020" title="2020"><span>2020</span></a>',
            (new GridForYear($this->path, 2021))->previousLink()->build()
        );

        $this->assertEquals(
            '<a class="ef-grid-previous-year" href="/events/2022" title="2022"><span>2022</span></a>',
            (new GridForYear($this->path, 2023))->previousLink()->build()
        );
    }

    /**
     * @test
     *
     * @group ui
     * @group year
     */
    public function year_grid_has_expected_title(): void
    {
        $this->assertEquals(
            '<h2>2020</h2>',
            (new GridForYear($this->path, 2020))->header()->build()
        );
    }
}
