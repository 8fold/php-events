<?php

namespace Eightfold\Events\Tests;

use PHPUnit\Framework\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\Shoop\Shoop;

use Eightfold\Events\Events;

class EventsTest extends TestCase
{
    private $path = "";

    public function setUp(): void
    {
        $this->path = Shoop::this(__DIR__)->divide("/")
            ->append(["test-events", "events"])->asString("/");
    }

    /**
     * @test
     */
    public function seek_year_with_events()
    {
        AssertEquals::applyWith(
            2022,
            "string",
            11.12, // 10.23, // 8.61, // 6.71, // 6.19, // 5.95,
            444 // 443 // 439
        )->unfoldUsing(
            Events::fold($this->path->unfold())->nextYearWithEvents(2020)->year()
        );

        AssertEquals::applyWith(
            2022,
            "string",
            21.24, // 21.22, // 15.31, // 7.34, // 7.24, // 7.11, // 6.41, // 5.36, // 4.7, // 4.1, // 3.63,
            1
        )->unfoldUsing(
            Events::fold($this->path->unfold())->previousYearWithEvents(2023)->year()
        );
    }

    /**
     * @test
     */
    public function seek_month_with_events()
    {
        AssertEquals::applyWith(
            5,
            "string",
            50.23, // 43.73, // 34.5, // 21.3, // 9.32, // 7.66, // 7.56, // 7.34, // 7.3, // 7.18,
            594 // 496 // 486 // 481
        )->unfoldUsing(
            Events::fold($this->path->unfold())->nextMonthWithEvents(1990, 4)->month()
        );

        AssertEquals::applyWith(
            5,
            "string",
            21.17, // 20.83, // 15.07, // 7.35, // 7.33, // 7.07, // 6.96,
            451
        )->unfoldUsing(
            Events::fold($this->path->unfold())->nextMonthWithEvents(2020, 4)->month()
        );

        AssertEquals::applyWith(
            5,
            "string",
            28.33, // 25.19, // 11.59, // 10.07, // 9.65, // 9.43, // 9.1, // 8.7, // 7.91, // 5.47, // 5.3, // 5.07, // 4.78, // 4.7, // 4.61,
            28
        )->unfoldUsing(
            Events::fold($this->path->unfold())->nextMonthWithEvents(2020, 12)->month()
        );

        AssertEquals::applyWith(
            5,
            "string",
            10.76, // 10.71, // 5.27, // 4.88, // 3.52, // 3.14, // 2.53, // 1.85, // 1.84, // 1.81, // 1.78,
            1
        )->unfoldUsing(
            Events::fold($this->path->unfold())->previousMonthWithEvents(2020, 7)->month()
        );

        AssertEquals::applyWith(
            12,
            "string",
            15.49, // 13.73, // 10.31, // 8.67,
            496
        )->unfoldUsing(
            Events::fold($this->path->unfold())->previousMonthWithEvents(2023, 1)->month()
        );

        AssertEquals::applyWith(
            2022,
            "string",
            11.23, // 9.9,
            1
        )->unfoldUsing(
            Events::fold($this->path->unfold())->previousMonthWithEvents(2023, 10)->year()
        );
    }
}
