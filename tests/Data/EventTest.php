<?php

namespace Eightfold\Events\Tests\Data;

use PHPUnit\Framework\TestCase;

use Eightfold\Shoop\Shoop;

use Eightfold\Events\Data\Event;

class EventTest extends TestCase
{
    private $path = "";

    public function setUp(): void
    {
        $this->path = Shoop::string(__DIR__)->divide("/")->dropLast()
            ->plus("test-events", "events")->join("/");
    }

    public function testCanInitialize()
    {
        $actual = Event::init($this->path->plus(
            "/". date("Y"),
            "/". date("n"),
            "/". date("j") .".event"
        ));
        $this->assertNotNull($actual);

        $event = Event::init($this->path->plus(
            "/1999",
            "/01",
            "/15.event"
        ));
        $this->assertEquals(1999, $event->year());
        $this->assertEquals(1, $event->month());
        $this->assertEquals(15, $event->day());
    }

    public function testCanGetYear()
    {
        $expected = date("Y");
        $actual = Event::init($this->path->plus(
            "/". date("Y"),
            "/". date("n"),
            "/". date("j") .".event"
        ))->year();
        $this->assertEquals($expected, $actual);
    }

    public function testCanGetMonth()
    {
        $expected = date("n");
        $actual = Event::init($this->path->plus(
            "/". date("Y"),
            "/". date("n"),
            "/". date("j") .".event"
        ))->month();
        $this->assertEquals($expected, $actual);
    }

    public function testCanGetDay()
    {
        $expected = date("j");
        $actual = Event::init($this->path->plus(
            "/". date("Y"),
            "/". date("n"),
            "/". date("j") .".event"
        ))->day();
        $this->assertEquals($expected, $actual);
    }

    public function testCanGetContent()
    {
        $expected = "Hello, World!";
        $actual = Event::init($this->path->plus(
            "/2020",
            "/05",
            "/20.event"
        ))->content();
        $this->assertEquals($expected, $actual);

        $expected = "Hello, Event!";
        $actual = Event::init($this->path->plus(
            "/2020",
            "/05",
            "/21.event"
        ))->content();
        $this->assertEquals($expected, $actual);
    }

    public function testCanGetUri()
    {
        $expected = "/2020/05/03";
        $actual = Event::init($this->path->plus(
            "/2020",
            "/05",
            "/03.event"
        ))->uri();
        $this->assertEquals($expected, $actual);
    }
}
