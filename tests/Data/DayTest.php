<?php

namespace Eightfold\Events\Tests\Data;

use PHPUnit\Framework\TestCase;

use Eightfold\Shoop\Shoop;

use Eightfold\Events\Data\Event;

use Eightfold\Events\Data\Day;

class DayTest extends TestCase
{
    private $path = "";

    public function setUp(): void
    {
        $this->path = Shoop::string(__DIR__)->divide("/")->dropLast()
            ->plus("test-events", "events")->join("/");
    }

    public function testCanInitialize()
    {
        $actual = Day::init($this->path->plus(
            "/". date("Y") ."/". date("n") ."/". date("j")));
        $this->assertNotNull($actual);

        $event = Day::init($this->path->plus("/1999/01/15"));
        $this->assertEquals(1999, $event->year());
        $this->assertEquals(1, $event->month());
        $this->assertEquals(15, $event->day());
    }

    public function testCanGetYear()
    {
        $expected = date("Y");
        $actual = Day::init($this->path->plus(
            "/". date("Y") ."/". date("n") ."/". date("j")
        ))->year();
        $this->assertEquals($expected, $actual);
    }

    public function testCanGetEvents()
    {
        $expected = Shoop::array([
            Event::init($this->path->plus("/2020/05/20.event"))
        ])->first()->content();
        $actual = Day::init($this->path->plus("/2020/05/20"))
            ->events()->first()->content();
        $this->assertSame($expected->unfold(), $actual->unfold());

        $expected = 2;
        $actual = Day::init($this->path->plus("/2020/05/22"))->totalEvents();
        $this->assertEquals($expected, $actual->unfold());

        $actual = Day::init($this->path->plus("/2020/01/10"))->hasEvents();
        $this->assertFalse($actual->unfold());
    }

    public function testCanGetDataPath()
    {
        $expected = $this->path ."/2020/05/20.event";
        $actual = Day::init($this->path->plus("/2020/05/20"))->dataPaths();
        $this->assertEquals($expected, $actual->first);

        $actual = Day::init($this->path->plus("/2020/05/20"))->couldHaveEvents();
        $this->assertTrue($actual);

        $actual = Day::init($this->path->plus("/2020/05/22"))->couldHaveEvents();
        $this->assertTrue($actual);

        $actual = Day::init($this->path->plus("/2020/05/23"))->couldHaveEvents();
        $this->assertFalse($actual);
    }

    public function testCanGetUri()
    {
        $expected = "/2020/05/03";
        $actual = Day::init($this->path->plus("/2020/05/03"))->uri();
        $this->assertEquals($expected, $actual);
    }
}
