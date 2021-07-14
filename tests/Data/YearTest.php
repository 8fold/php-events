<?php

namespace Eightfold\Events\Tests\Data;

use PHPUnit\Framework\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\Shoop\Shoop;

use Eightfold\Events\Data\Year;
use Eightfold\Events\Data\Months;
use Eightfold\Events\Data\Month;

/**
 * @group Year
 */
class YearTest extends TestCase
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
    public function root_and_parts()
    {
        AssertEquals::applyWith(
            $this->path->unfold(),
            "string",
            1.19,
            19 // 12
        )->unfoldUsing(
            Year::fold($this->path->unfold(), 2020)->root()
        );

        AssertEquals::applyWith(
            [
                2020
            ],
            "array",
            0.2, // 0.16, // 0.004,
            9
        )->unfoldUsing(
            Year::fold($this->path->unfold(), 2020)->parts()
        );
    }

    /**
     * @test
     */
    public function year()
    {
        AssertEquals::applyWith(
            date("Y"),
            "string",
            0.36,
            12
        )->unfoldUsing(
            Year::fold($this->path->unfold(), date("Y"))->year()
        );
    }

    /**
     * @test
     */
    public function get_all_months()
    {
        AssertEquals::applyWith(
            [
                "i05" => Month::fold($this->path->unfold(), 2020, 5),
                "i12" => Month::fold($this->path->unfold(), 2020, 12)
            ],
            "array",
            3.15, // 3.14, // 2.9, // 2.79,
            310
        )->unfoldUsing(
            Year::fold($this->path->unfold(), 2020)->content()
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
            13.32, // 10.43, // 6.27,
            421
        )->unfoldUsing(
            Year::fold($this->path->unfold(), 2020)->hasEvents()
        );

        AssertEquals::applyWith(
            false,
            "boolean",
            1.04, // 0.72, // 0.36, // 0.21, // 0.2, // 0.19, // 0.18,
            1
        )->unfoldUsing(
            Year::fold($this->path->unfold(), 2021)->hasEvents()
        );

        AssertEquals::applyWith(
            false,
            "boolean",
            0.69, // 0.33, // 0.24, // 0.16, // 0.14,
            1
        )->unfoldUsing(
            Year::fold($this->path->unfold(), 2021)->couldHaveEvents()
        );
    }
}
