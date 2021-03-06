<?php

namespace Eightfold\Events\Tests\Data;

use PHPUnit\Framework\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\Shoop\Shoop;

use Eightfold\Events\Data\Date;
use Eightfold\Events\Data\Event;

/**
 * @group Date
 */
class DateTest extends TestCase
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
            1999,
            "string",
            1.54, // 0.62, // 0.46,
            27 // 26 // 19
        )->unfoldUsing(
            Date::fold($this->path->unfold(), 1999, 1, 10)->year()
        );

        AssertEquals::applyWith(
            1,
            "string",
            0.53, // 0.5, // 0.48, // 0.46, // 0.45, // 0.43,
            30
        )->unfoldUsing(
            Date::fold($this->path->unfold(), 1999, 1, 10)->month()
        );

        AssertEquals::applyWith(
            10,
            "string",
            0.01, // 0.005,
            1
        )->unfoldUsing(
            Date::fold($this->path->unfold(), 1999, 1, 10)->date()
        );
    }

    /**
     * @test
     */
    public function content()
    {
        AssertEquals::applyWith(
            [
                Event::fold($this->path->unfold(), 2020, 5, 21, 1)
            ],
            "array",
            10.65, // 3.77, // 3.41, // 3.31,
            327
        )->unfoldUsing(
            Date::fold($this->path->unfold(), 2020, 5, 21)->content()
        );

        AssertEquals::applyWith(
            [
                Event::fold($this->path->unfold(), 2020, 5, 22, 1),
                Event::fold($this->path->unfold(), 2020, 5, 22, 2)
            ],
            "array",
            3.55, // 3.54,
            327
        )->unfoldUsing(
            Date::fold($this->path->unfold(), 2020, 5, 22)->content()
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
            6.75, // 5.58,
            350
        )->unfoldUsing(
            Date::fold($this->path->unfold(), 2020, 5, 20)->hasEvents()
        );

        AssertEquals::applyWith(
            true,
            "boolean",
            0.94, // 0.78, // 0.75,
            1
        )->unfoldUsing(
            Date::fold($this->path->unfold(), 2020, 5, 22)->hasEvents()
        );

        AssertEquals::applyWith(
            false,
            "boolean",
            1.07, // 0.85, // 0.75, // 0.73, // 0.69, // 0.64, // 0.63,
            1
        )->unfoldUsing(
            Date::fold($this->path->unfold(), 2020, 5, 23)->hasEvents()
        );

        AssertEquals::applyWith(
            false,
            "boolean",
            0.84, // 0.77, // 0.67, // 0.63,
            1
        )->unfoldUsing(
            Date::fold($this->path->unfold(), 2020, 5, 23)->couldHaveEvents()
        );
    }
}
