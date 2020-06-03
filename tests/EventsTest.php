<?php

namespace Eightfold\Events\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\Shoop\Shoop;

use Eightfold\Events\Events;

class EventsTest extends TestCase
{
    private $path;

    public function setUp(): void
    {
        $this->path = Shoop::string(__DIR__)->divide("/")->dropLast()
            ->plus("tests", "test-events", "events")->join("/");
    }

    public function testCanGetYears()
    {
        $expected = 2;
        $actual = Events::init($this->path)->years()->count;
        $this->assertEquals($expected, $actual);
    }

    public function testDateHasEvents()
    {
        $actual = Events::init($this->path)->couldHaveEvents();
        $this->assertTrue($actual);

        $events = Events::init($this->path);
        $hasEvents = $events->dateHasEvents(2020, 5, 20);
        $this->assertTrue($hasEvents->unfold());

        $hasEvents = $events->dateHasEvents(2019, 6, 20);
        $this->assertFalse($hasEvents->unfold());
    }

    public function testMonthHasEvents()
    {
        $events = Events::init($this->path);
        $hasEvents = $events->monthHasEvents(2020, 4);
        $this->assertFalse($hasEvents->unfold());

        $hasEvents = $events->monthHasEvents(2020, 5);
        $this->assertTrue($hasEvents->unfold());
    }

    public function testYearHasEvents()
    {
        $events = Events::init($this->path);
        $hasEvents = $events->yearHasEvents(2020);
        $this->assertTrue($hasEvents->unfold());

        $hasEvents = $events->yearHasEvents(2021);
        $this->assertFalse($hasEvents->unfold());
    }

    public function testNextYearWithEvents()
    {
        $events = Events::init($this->path);

        $expected = 2022;
        $actual = $events->nextYearWithEvents(2020);
        $this->assertEquals($expected, $actual->year());

        $actual = $events->nextYearWithEvents(2022);
        $this->assertNull($actual);
    }

    public function testPreviousYearWithEvents()
    {
        $events = Events::init($this->path);
        $expected = 2022;
        $actual = $events->previousYearWithEvents(2023);
        $this->assertEquals($expected, $actual->year());

        $expected = 2020;
        $actual = $events->previousYearWithEvents(2022);
        $this->assertEquals($expected, $actual->year());

        $actual = $events->previousYearWithEvents(2010);
        $this->assertNull($actual);
    }

    public function testNextMonthWithEvents()
    {
        $events = Events::init($this->path);

        $expected = 5;
        $actual = $events->nextMonthWithEvents(2020, 4);
        $this->assertEquals($expected, $actual->month());

        $expected = 12;
        $actual = $events->nextMonthWithEvents(2020, 5);
        $this->assertEquals($expected, $actual->month());

        // TODO: stays confined to year, should it try next year??
        $expected = 4;
        $actual = $events->nextMonthWithEvents(2020, 12);
        $this->assertNull($actual);

        $actual = $events->nextMonthWithEvents(2022, 12);
        $this->assertNull($actual);
    }

    public function testPreviousMonthWithEvents()
    {
        $events = Events::init($this->path);

        $expected = 5;
        $actual = $events->previousMonthWithEvents(2020, 10);
        $this->assertEquals($expected, $actual->month());

        $expected = 5;
        $actual = $events->previousMonthWithEvents(2022, 12);
        $this->assertEquals($expected, $actual->month());

        $actual = $events->previousYearWithEvents(2010);
        $this->assertNull($actual);
    }

    public function testUriForMonthWithNearestEvent()
    {
        $events = Events::init($this->path);

        $expected = "/2022/12";
        $actual = $events->nearestMonthWithEvents(2023, 6);
        $this->assertEquals($expected, $actual->uri());

        $expected = "/2022/05";
        $actual = $events->nearestMonthWithEvents(2021, 6);
        $this->assertEquals($expected, $actual->uri());
    }

    public function testUriForYearWithNearestEvent()
    {
        $events = Events::init($this->path);

        $expected = 2022;
        $actual = $events->nearestYearWithEvents(2020);
        $this->assertEquals($expected, $actual->year());

        $expected = 2022;
        $actual =$events->nearestYearWithEvents(2023);
        $this->assertEquals($expected, $actual->year());

        $expected = "/2022";
        $actual = $events->nearestYearWithEvents(2020);
        $this->assertEquals($expected, $actual->uri());

        $expected = "/2022";
        $actual =$events->nearestYearWithEvents(2023);
        $this->assertEquals($expected, $actual->uri());
    }
}
