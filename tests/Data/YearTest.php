<?php

namespace Eightfold\Events\Tests\Data;

use PHPUnit\Framework\TestCase;

use Eightfold\Shoop\Shoop;

use Eightfold\Events\Data\Event;

use Eightfold\Events\Data\Year;

class YearTest extends TestCase
{
    private $path = "";

    public function setUp(): void
    {
        $this->path = Shoop::string(__DIR__)->divide("/")->dropLast()
            ->plus("test-events", "events")->join("/");
    }

    public function testCanInitialize()
    {
        $actual = Year::init($this->path->plus("/". date("Y")));
        $this->assertNotNull($actual);

        $event = Year::init($this->path->plus("/1999"));
        $this->assertEquals(1999, $event->year());
    }

    public function testCanGetYear()
    {
        $expected = date("Y");
        $actual = Year::init($this->path->plus("/". date("Y")))->year();
        $this->assertEquals($expected, $actual);
    }

    public function testCanGetMonths()
    {
        $expected = 2;
        $actual = Year::init($this->path->plus("/2020"))->months()->count;
        $this->assertEquals($expected, $actual);

        $expected = 8;
        $actual = Year::init($this->path->plus("/2020"))->events()->count;
        $this->assertEquals($expected, $actual);

        $expected = 5;
        $actual = Year::init($this->path->plus("/2020"))->firstMonthWithEvents()
            ->month();
        $this->assertEquals($expected, $actual);

        $expected = 12;
        $actual = Year::init($this->path->plus("/2020"))->lastMonthWithEvents()
            ->month();
        $this->assertEquals($expected, $actual);
    }

    public function testCanGetEvents()
    {
        $expected = 8;
        $actual = Year::init($this->path->plus("/2020"))->events()->count;
        $this->assertEquals($expected, $actual);

        $expected = 8;
        $actual = Year::init($this->path->plus("/2020"))->totalEvents();
        $this->assertEquals($expected, $actual->unfold());

        $expected = true;
        $actual = Year::init($this->path->plus("/2020"))->couldHaveEvents();
        $this->assertTrue($actual);

        $expected = false;
        $actual = Year::init($this->path->plus("/2021"))->couldHaveEvents();
        $this->assertFalse($actual);
    }

    public function testCanGetDataPath()
    {
        $expected = $this->path ."/2020/05";
        $actual = Year::init($this->path->plus("/2020"))->dataPaths()->first;
        $this->assertEquals($expected, $actual);

        $actual = Year::init($this->path->plus("/2020"))->couldHaveEvents();
        $this->assertTrue($actual);

        $actual = Year::init($this->path->plus("/2021"))->couldHaveEvents();
        $this->assertFalse($actual);
    }

    public function testCanGetUri()
    {
        $expected = "/2020";
        $actual = Year::init($this->path->plus("/2020"))->uri();
        $this->assertEquals($expected, $actual);
    }
}
