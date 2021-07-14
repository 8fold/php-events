<?php

namespace Eightfold\Events\Tests\Data;

use PHPUnit\Framework\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\Shoop\Shoop;

use Eightfold\Events\Data\Month;
use Eightfold\Events\Data\Event;

/**
 * @group Month
 */
class MonthTest extends TestCase
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
            2.73, // 2.01, // 0.66, // 0.49, // 0.47, // 0.42,
            82 // 33 // 30 // 29
        )->unfoldUsing(
            Month::fold($this->path->unfold(), 1999, 1)->year()
        );

        AssertEquals::applyWith(
            1,
            "string",
            0.54, // 0.49, // 0.01, // 0.004, // 0.003,
            30
        )->unfoldUsing(
            Month::fold($this->path->unfold(), 1999, 1)->month()
        );
    }

    /**
     * @test
     */
    public function days_in_month()
    {
        AssertEquals::applyWith(
            31,
            "integer",
            27.03, // 16.3,
            2013 // 1967 // 1919 // 1905 // 1903 // 1901
        )->unfoldUsing(
            Month::fold($this->path->unfold(), 2020, 5)->daysInMonth()
        );
    }

    /**
     * @test
     */
    public function content()
    {
        // TODO: DaysCollection with fluent api
        AssertEquals::applyWith(
            4,
            "integer",
            10.6, // 10.37, // 9.23,
            396
        )->unfoldUsing(
            Month::fold($this->path, 2020, 5)->count()
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
            13.1, // 9.36,
            416 // 415
        )->unfoldUsing(
            Month::fold($this->path->unfold(), 2020, 5)->hasEvents()
        );

        AssertEquals::applyWith(
            false,
            "boolean",
            1.36, // 1.26, // 1.16, // 0.27, // 0.26, // 0.25, // 0.23, // 0.21,
            1
        )->unfoldUsing(
            Month::fold($this->path->unfold(), 2020, 6)->hasEvents()
        );

        AssertEquals::applyWith(
            false,
            "boolean",
            1.09, // 0.97, // 0.31, // 0.28,
            1
        )->unfoldUsing(
            Month::fold($this->path->unfold(), 2020, 10)->couldHaveEvents()
        );

        AssertEquals::applyWith(
            true,
            "boolean",
            5.65, // 5.57, // 5.32,
            414
        )->unfoldUsing(
            Month::fold($this->path->unfold(), 2022, 12)->hasEvents()
        );
    }
}
