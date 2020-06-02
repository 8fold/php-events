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
        $this->path = Shoop::string(__DIR__)->divide("/")->dropLast()->plus("test-events", "events")->join("/");
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

        $calendar = Events::init($this->path);
        $hasEvents = $calendar->dateHasEvents(2020, 5, 20);
        $this->assertTrue($hasEvents->unfold());

        $hasEvents = $calendar->dateHasEvents(2019, 6, 20);
        $this->assertFalse($hasEvents->unfold());
    }

    public function testMonthHasEvents()
    {
        $calendar = Events::init($this->path);
        $hasEvents = $calendar->monthHasEvents(2020, 4);
        $this->assertFalse($hasEvents->unfold());

        $hasEvents = $calendar->monthHasEvents(2020, 5);
        $this->assertTrue($hasEvents->unfold());
    }

    public function testYearHasEvents()
    {
        $calendar = Events::init($this->path);
        $hasEvents = $calendar->yearHasEvents(2020);
        $this->assertTrue($hasEvents->unfold());

        $hasEvents = $calendar->yearHasEvents(2021);
        $this->assertFalse($hasEvents->unfold());
    }

    public function testNextYearWithEvents()
    {
        $calendar = Events::init($this->path);

        $expected = 2022;
        $actual = $calendar->nextYearWithEvents(2020);
        $this->assertEquals($expected, $actual->year());

        $actual = $calendar->nextYearWithEvents(2022);
        $this->assertNull($actual);
    }

    public function testPreviousYearWithEvents()
    {
        $calendar = Events::init($this->path);
        $expected = 2022;
        $actual = $calendar->previousYearWithEvents(2023);
        $this->assertEquals($expected, $actual->year());

        $expected = 2020;
        $actual = $calendar->previousYearWithEvents(2022);
        $this->assertEquals($expected, $actual->year());

        $actual = $calendar->previousYearWithEvents(2010);
        $this->assertNull($actual);
    }

    public function testNextMonthWithEvents()
    {
        $calendar = Events::init($this->path);

        $expected = 5;
        $actual = $calendar->nextMonthWithEvents(2020, 4);
        $this->assertEquals($expected, $actual->month());

        $expected = 12;
        $actual = $calendar->nextMonthWithEvents(2020, 5);
        $this->assertEquals($expected, $actual->month());

        // TODO: stays confined to year, should it try next year??
        $expected = 4;
        $actual = $calendar->nextMonthWithEvents(2020, 12);
        $this->assertNull($actual);

        $actual = $calendar->nextMonthWithEvents(2022, 12);
        $this->assertNull($actual);
    }

    public function testPreviousMonthWithEvents()
    {
        $calendar = Events::init($this->path);

        $expected = 5;
        $actual = $calendar->previousMonthWithEvents(2020, 10);
        $this->assertEquals($expected, $actual->month());

        $expected = 5;
        $actual = $calendar->previousMonthWithEvents(2022, 12);
        $this->assertEquals($expected, $actual->month());

        $actual = $calendar->previousYearWithEvents(2010);
        $this->assertNull($actual);
    }

    public function testUriForMonthWithNearestEvent()
    {
        $calendar = Events::init($this->path);

        $expected = "/2022/12";
        $actual = $calendar->nearestMonthWithEvents(2023, 6);
        $this->assertEquals($expected, $actual->uri());

        $expected = "/2022/05";
        $actual = $calendar->nearestMonthWithEvents(2021, 6);
        $this->assertEquals($expected, $actual->uri());
    }

    public function testUriForYearWithNearestEvent()
    {
        $calendar = Events::init($this->path);

        $expected = 2022;
        $actual = $calendar->nearestYearWithEvents(2020);
        $this->assertEquals($expected, $actual->year());

        $expected = 2022;
        $actual =$calendar->nearestYearWithEvents(2023);
        $this->assertEquals($expected, $actual->year());

        $expected = "/2022";
        $actual = $calendar->nearestYearWithEvents(2020);
        $this->assertEquals($expected, $actual->uri());

        $expected = "/2022";
        $actual =$calendar->nearestYearWithEvents(2023);
        $this->assertEquals($expected, $actual->uri());
    }
}
