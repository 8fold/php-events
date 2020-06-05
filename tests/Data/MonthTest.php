<?php

namespace Eightfold\Events\Tests\Data;

use PHPUnit\Framework\TestCase;

use Eightfold\Shoop\Shoop;

use Eightfold\Events\Data\Event;

use Eightfold\Events\Data\Month;

class MonthTest extends TestCase
{
    private $path = "";

    public function setUp(): void
    {
        $this->path = Shoop::string(__DIR__)->divide("/")->dropLast()
            ->plus("test-events", "events")->join("/");
    }

    public function testCanInitialize()
    {
        $actual = Month::init($this->path->plus("/". date("Y") ."/". date("n")));
        $this->assertNotNull($actual);

        $month = Month::init($this->path->plus("/1999/01"));
        $this->assertEquals(1999, $month->year());
        $this->assertEquals(1, $month->month());
    }

    public function testCanGetYear()
    {
        $expected = date("Y");
        $actual = Month::init($this->path->plus("/". date("Y") ."/". date("n")))
            ->year();
        $this->assertEquals($expected, $actual);
    }

    public function testCanGetMonth()
    {
        $expected = date("n");
        $actual = Month::init($this->path->plus("/". date("Y") ."/". date("n")))
            ->month();
        $this->assertEquals($expected, $actual);
    }

    public function testCanGetDays()
    {
        $expected = 31;
        $actual = Month::init($this->path->plus("/2020/05"))->totalDays();
        $this->assertEquals($expected, $actual);

        $expected = 4;
        $actual = Month::init($this->path->plus("/2020/05"));
        $this->assertEquals($expected, $actual->days()->count);

        $expected = "Hello, World!";
        $actual = $actual->day(20)->events()->first()->content();
        $this->assertEquals($expected, $actual);
    }

    public function testCanGetEvents()
    {
        $expected = 5;
        $actual = Month::init($this->path->plus("/2020/05"))->events()->count;
        $this->assertEquals($expected, $actual);

        $actual = Month::init($this->path->plus("/2020/05"))->totalEvents();
        $this->assertEquals($expected, $actual->unfold());
    }

    public function testCanGetDataPath()
    {
        $expected = $this->path ."/2020/05/19.event";
        $actual = Month::init($this->path ."/2020/05")->dataPaths()->first;
        $this->assertEquals($expected, $actual);

        $actual = Month::init($expected)->couldHaveEvents();
        $this->assertTrue($actual);

        $actual = Month::init($this->path->plus("/2021/05"))->couldHaveEvents();
        $this->assertFalse($actual);
    }

    public function testCanGetUri()
    {
        $expected = "/2020/05";
        $actual = Month::init($this->path->plus("/2020/05"))->uri();
        $this->assertEquals($expected, $actual);
    }
}
