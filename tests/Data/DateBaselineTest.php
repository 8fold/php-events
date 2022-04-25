<?php

declare(strict_types=1);

namespace Eightfold\Events\Tests\Data;

use PHPUnit\Framework\TestCase;

use SplFileInfo;

use Eightfold\Events\Data\Date;
use Eightfold\Events\Data\Event;

class DateBaselineTest extends TestCase
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
     * @group data
     * @group date
     */
    public function date_has_details(): void
    {
        $date = Date::fold($this->path, 1999, 1, 10);

        // 3.14ms 29kb
        $result = $date->yearString();
        $this->assertIsString($result);
        $this->assertEquals('1999', $result);

        $result = $date->year();
        $this->assertIsInt($result);
        $this->assertEquals(1999, $result);

        // 2.51ms 33kb
        $result = $date->monthString();
        $this->assertIsString($result);
        $this->assertEquals('01', $result);

        $result = $date->month();
        $this->assertIsInt($result);
        $this->assertEquals(1, $result);

        // 0.03ms 1kb
        $result = $date->dateString();
        $this->assertIsString($result);
        $this->assertEquals('10', $result);

        $result = $date->date();
        $this->assertIsInt($result);
        $this->assertEquals(10, $result);
    }

    /**
     * @test
     *
     * @group data
     * @group date
     */
    public function date_has_content(): void
    {
        // 15.43ms 521kb
        $this->assertEquals(
            [
                $this->path . '/2020/05/21.event' =>
                    (new Event(
                        $this->path,
                        2020,
                        5,
                        21,
                        1,
                        // new SplFileInfo($this->path . '/2020/05/21.event')
                        // Item::create($this->path . '/2020/05/21.event')
                    ))
            ],
            Date::fold($this->path, 2020, 5, 21)->content()
        );

        // 11.41ms 327kb
        $this->assertEquals(
            Date::fold($this->path, 2020, 5, 22)->content(),
            [
                $this->path . '/2020/05/22_1.event' =>
                    (new Event(
                        $this->path,
                        2020,
                        5,
                        22,
                        1,
                        // new SplFileInfo($this->path . '/2020/05/22_1.event')
                        // Item::create($this->path . '/2020/05/22_1.event')
                    )),
                $this->path . '/2020/05/22_2.event' =>
                    (new Event(
                        $this->path,
                        2020,
                        5,
                        22,
                        2,
                        // new SplFileInfo($this->path . '/2020/05/22_2.event')
                        // Item::create($this->path . '/2020/05/22_2.event')
                    ))
            ]);

        $result = Date::fold($this->path, 2020, 5, 22)->count();
        $this->assertIsInt($result);
        $this->assertEquals(2, $result);

        $this->assertFalse(Date::fold($this->path, 2020, 5, 23)->hasEvents());
    }
}
