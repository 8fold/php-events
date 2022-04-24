<?php

declare(strict_types=1);

namespace Eightfold\Events\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\Events\Events;

use Eightfold\FileSystem\Item;

use Eightfold\Events\Data\Year;
use Eightfold\Events\Data\Years;

class EventsBaselineTest extends TestCase
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
     * @group events
     * @group year
     * @group data
     */
    public function event_seeking(): void
    {
        $this->assertEquals(
            2022,
            Events::fold($this->path)->nextYearWithEvents(2020)->year()
        );

        $this->assertEquals(
            2022,
            Events::fold($this->path)->previousYearWithEvents(2023)->year()
        );

        $this->assertEquals(
            5,
            Events::fold($this->path)->nextMonthWithEvents(1990, 4)->month()
        );

        $this->assertEquals(
            5,
            Events::fold($this->path)->nextMonthWithEvents(2020, 4)->month()
        );

        $this->assertEquals(
            5,
            Events::fold($this->path)->nextMonthWithEvents(2020, 12)->month()
        );

        $this->assertEquals(
            5,
            Events::fold($this->path)->previousMonthWithEvents(2020, 7)->month()
        );

        $this->assertEquals(
            12,
            Events::fold($this->path)->previousMonthWithEvents(2023, 1)->month()
        );

        $this->assertEquals(
            2022,
            Events::fold($this->path)->previousMonthWithEvents(2023, 10)->year()
        );
    }

    /**
     * @test
     *
     * @group events
     * @group years
     * @group data
     */
    public function events_has_content(): void
    {
        $this->assertEquals(
            Events::fold($this->path)->years()->content(),
            [
                "i2020" => Year::fold($this->path, 2020),
                "i2022" => Year::fold($this->path, 2022)
            ]
        );
    }
}
