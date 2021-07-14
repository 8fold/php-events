<?php

namespace Eightfold\Events\Tests\Data;

use PHPUnit\Framework\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\Shoop\Shoop;

use Eightfold\Events\Data\Event;

/**
 * @group Event
 */
class EventTest extends TestCase
{
    private $path = "";

    public function setUp(): void
    {
        $this->path = Shoop::this(__DIR__)->divide("/")->dropLast()
            ->append(["test-events", "events"])->asString("/");
    }

    /**
     * @test
     */
    public function initialize_and_event_details()
    {
        AssertEquals::applyWith(
            2020,
            "string",
            0.59, // 0.54, // 0.52, // 0.49, // 0.46, // 0.44,
            27 // 19
        )->unfoldUsing(
            Event::fold($this->path->unfold(), 2020, 5, 22, 2)->year()
        );

        AssertEquals::applyWith(
            "05",
            "string",
            0.51,
            30
        )->unfoldUsing(
            Event::fold($this->path->unfold(), 2020, 5, 22, 2)->month()
        );

        AssertEquals::applyWith(
            22,
            "string",
            0.55,
            1
        )->unfoldUsing(
            Event::fold($this->path->unfold(), 2020, 5, 22, 2)->date()
        );

        AssertEquals::applyWith(
            2,
            "integer",
            0.008, // 0.005, // 0.004,
            1
        )->unfoldUsing(
            Event::fold($this->path->unfold(), 2020, 5, 22, 2)->count()
        );
    }

    /**
     * @test
     */
    public function content()
    {
        AssertEquals::applyWith(
            "Hello, World!",
            "string",
            9.66, // 4.41, // 4.23, // 0.45,
            319 // 19
        )->unfoldUsing(
            Event::fold($this->path->unfold(), 2020, 5, 20, 1)->content()
        );

        AssertEquals::applyWith(
            "Hello, Day?\n\nSomething\n",
            "string",
            2.29, // 1.74, // 1.53, // 1.11, // 0.4, // 0.32,
            1
        )->unfoldUsing(
            Event::fold($this->path->unfold(), 2020, 5, 22, 2)->content()
        );

        AssertEquals::applyWith(
            "",
            "string",
            2.17, // 0.58,
            83 // 19
        )->unfoldUsing(
            Event::fold($this->path->unfold(), 2020, 5, 23, 2)->content()
        );
    }

    /**
     * @test
     */
    public function has_events()
    {
        AssertEquals::applyWith(
            true,
            "boolean",
            2.72,
            92
        )->unfoldUsing(
            Event::fold($this->path->unfold(), 2020, 5, 20, 1)->hasEvents()
        );

        AssertEquals::applyWith(
            true,
            "boolean",
            0.84, // 0.79, // 0.64, // 0.23, // 0.2, // 0.19, // 0.14,
            1
        )->unfoldUsing(
            Event::fold($this->path->unfold(), 2020, 5, 22, 2)->hasEvents()
        );

        AssertEquals::applyWith(
            false,
            "boolean",
            0.79, // 0.58, // 0.2, // 0.18, // 0.12, // 0.11,
            1
        )->unfoldUsing(
            Event::fold($this->path->unfold(), 2020, 5, 23, 2)->hasEvents()
        );

        AssertEquals::applyWith(
            false,
            "boolean",
            0.65, // 0.33, // 0.18, // 0.15, // 0.14, // 0.12, // 0.11,
            1
        )->unfoldUsing(
            Event::fold($this->path->unfold(), 2020, 5, 23, 2)->couldHaveEvents()
        );
    }
}
