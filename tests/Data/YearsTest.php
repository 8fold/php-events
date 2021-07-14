<?php

namespace Eightfold\Events\Tests\Data;

use PHPUnit\Framework\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\Shoop\Shoop;

use Eightfold\Events\Data\Years;
use Eightfold\Events\Data\Year;

/**
 * @group Years
 */
class YearsTest extends TestCase
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
    public function parts()
    {
        AssertEquals::applyWith(
            $this->path->unfold(),
            "string",
            0.52, // 0.46,
            20 // 11
        )->unfoldUsing(
            Years::fold($this->path->unfold())->root()
        );

        AssertEquals::applyWith(
            $this->path->unfold(),
            "string",
            0.005, // 0.003,
            1
        )->unfoldUsing(
            Years::fold($this->path->unfold())->unfold()
        );
    }

    /**
     * @test
     */
    public function get_all_years()
    {
        AssertEquals::applyWith(
            [
                "i2020" => Year::fold($this->path->unfold(), 2020),
                "i2022" => Year::fold($this->path->unfold(), 2022)
            ],
            "array",
            3.38, // 2.75, // 2.71,
            301
        )->unfoldUsing(
            Years::fold($this->path->unfold())->content()
        );

        AssertEquals::applyWith(
            2,
            "integer",
            2.76,
            1
        )->unfoldUsing(
            Years::fold($this->path->unfold())->count()
        );
    }

    /**
     * @test
     */
    public function get_single_year()
    {
        AssertEquals::applyWith(
            2020,
            "string",
            8.88, // 3.81,
            360 // 359
        )->unfoldUsing(
            Years::fold($this->path->unfold())->year(2020)->year()
        );

        AssertEquals::applyWith(
            false,
            "boolean",
            1.41, // 0.69, // 0.5, // 0.43, // 0.33, // 0.31,
            1
        )->unfoldUsing(
            Years::fold($this->path->unfold())->year(2021)
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
            10.86, // 7.91,
            432 // 431
        )->unfoldUsing(
            Years::fold($this->path->unfold())->hasEvents()
        );

        AssertEquals::applyWith(
            true,
            "boolean",
            0.16, // 0.06, // 0.04, // 0.02,
            1
        )->unfoldUsing(
            Years::fold($this->path->unfold())->couldHaveEvents()
        );
    }
}
