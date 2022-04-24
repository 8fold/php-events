<?php

declare(strict_types=1);

namespace Eightfold\Events\Tests\Data;

use PHPUnit\Framework\TestCase;

use Eightfold\Events\Data\Event;

use Eightfold\FileSystem\Item;

class EventBaselineTest extends TestCase
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
     * @group data
     * @group event
     */
    public function event_has_event_details(): void
    {
        $event = Event::fold($this->path, 2020, 5, 22, 2);

        // 0.59ms 27kb
        $result = $event->yearString();
        $this->assertIsString($result);
        $this->assertEquals('2020', $result);

        $result = $event->year();
        $this->assertIsInt($result);
        $this->assertEquals(2020, $result);

        // 0.51ms 30kb
        $result = $event->monthString();
        $this->assertIsString($result);
        $this->assertEquals('05', $result);

        $result = $event->month();
        $this->assertIsInt($result);
        $this->assertEquals(5, $result);

        // 0.008ms 1kb
        $result = $event->dateString();
        $this->assertIsString($result);
        $this->assertEquals('22', $result);

        $result = $event->date();
        $this->assertIsInt($result);
        $this->assertEquals(22, $result);
    }

    /**
     * @test
     *
     * @group data
     * @group event
     */
    public function event_has_content(): void
    {
        // 9.66ms 319kb
        $result = Event::fold($this->path, 2020, 5, 20, 1)->content();
        $this->assertIsString($result);
        $this->assertEquals('Hello, World!', $result);

        $result = Event::fold($this->path, 2020, 5, 22, 2)->content();
        $this->assertIsString($result);
        $this->assertEquals(<<<md
            Hello, Day?

            Something

            md,
            $result
        );

        $result = Event::fold($this->path, 2020, 5, 23, 2)->content();
        $this->assertIsString($result);
        $this->assertEmpty($result);
    }

    /**
     * @test
     *
     * @group data
     * @group event
     */
    public function event_can_be_separated_by_title_and_body(): void
    {
        $result = Event::fold($this->path, 2020, 5, 22, 2)->title();
        $this->assertIsString($result);
        $this->assertEquals('Hello, Day?', $result);

        $result = Event::fold($this->path, 2020, 5, 22, 2)->body();
        $this->assertIsString($result);
        $this->assertEquals('Something', $result);
    }

    /**
     * @test
     *
     * @group data
     * @group event
     */
    public function event_can_check_for_events(): void
    {
        // 2.72ms 92kb
        $this->assertTrue(Event::fold($this->path, 2020, 5, 20, 1)->hasEvents());

        $this->assertTrue(Event::fold($this->path, 2020, 5, 22, 2)->hasEvents());

        $this->assertFalse(Event::fold($this->path, 2020, 5, 23, 2)->hasEvents());
    }
}
