<?php

namespace Eightfold\Events\Tests\Data;

use PHPUnit\Framework\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\Shoop\Shoop;

use Eightfold\Events\Data\Year;
use Eightfold\Events\Data\Months;
use Eightfold\Events\Data\Month;

use Eightfold\FileSystem\Item;

/**
 * @group Year
 */
class YearTest extends TestCase
{
    private $path = "";

    public function setUp(): void
    {
        $this->path = Item::create(__DIR__)
            ->up()->append('test-events', 'events')->thePath();
    }

    /**
     * @test
     * @group old-focus
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
            0.95, // 0.69, // 0.33, // 0.24, // 0.16, // 0.14,
            1
        )->unfoldUsing(
            Year::fold($this->path->unfold(), 2021)->couldHaveEvents()
        );
    }
}
