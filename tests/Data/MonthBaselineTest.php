<?php

declare(strict_types=1);

namespace Eightfold\Events\Tests\Data;

use PHPUnit\Framework\TestCase;

use SplFileInfo;

use Eightfold\Events\Data\Month;
use Eightfold\Events\Data\Event;

class MonthBaselineTest extends TestCase
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
     * @group month
     */
    public function month_can_answer_before_or_after(): void
    {
        $this->assertFalse(Month::fold($this->path, 2020, 7)->isAfter(7));

        $this->assertTrue(Month::fold($this->path, 2020, 7)->isAfter(6));

        $this->assertFalse(Month::fold($this->path, 2020, 7)->isAfter(8));

        $this->assertFalse(Month::fold($this->path, 2020, 7)->isBefore(7));

        $this->assertFalse(Month::fold($this->path, 2020, 7)->isBefore(6));

        $this->assertTrue(Month::fold($this->path, 2020, 7)->isBefore(8));
    }

    /**
     * @test
     *
     * @group data
     * @group month
     */
    public function month_has_details(): void
    {
        $month = Month::fold($this->path, 1999, 1);

        // 3.14ms 29kb
        $result = $month->yearString();
        $this->assertIsString($result);
        $this->assertEquals('1999', $result);

        $result = $month->year();
        $this->assertIsInt($result);
        $this->assertEquals(1999, $result);

        // 2.51ms 33kb
        $result = $month->monthString();
        $this->assertIsString($result);
        $this->assertEquals($result, '01');

        $result = $month->month(false);
        $this->assertIsInt($result);
        $this->assertEquals(1, $result);

        $result = Month::fold($this->path, 2020, 5)->daysInMonth();
        $this->assertIsInt($result);
        $this->assertEquals(31, $result);
    }

    /**
     * @test
     *
     * @group data
     * @group month
     */
    public function month_has_content(): void
    {
        // 10.6ms 396kb
        $result = Month::fold($this->path, 2020, 5)->count();
        $this->assertIsInt($result);
        $this->assertEquals(5, $result);

        // 13.75ms 415kb
        $this->assertTrue(Month::fold($this->path, 2020, 5)->hasEvents());

        // 1.36ms 1kb
        $this->assertFalse(Month::fold($this->path, 2020, 6)->hasEvents());

        // 1.26ms 1
        $this->assertFalse(Month::fold($this->path, 2020, 10)->couldHaveEvents());
    }
}
