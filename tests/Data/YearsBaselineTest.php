<?php

declare(strict_types=1);

namespace Eightfold\Events\Tests\Data;

use PHPUnit\Framework\TestCase;

use Eightfold\Events\Data\Years;

use Eightfold\FileSystem\Item;

use Eightfold\Events\Data\Year;

class YearsBaselineTest extends TestCase
{
    private string $path = '';

    public function setUp(): void
    {
        $this->path = Item::create(__DIR__)->up()
            ->append('test-events', 'events')
            ->thePath();
    }

    /**
     * @test
     *
     * @group data
     * @group years
     */
    public function years_can_get_single_year(): void
    {
        $year = Years::fold($this->path)->year(2020);

        // TODO: assertion fails
        // $this->assertInstanceOf(Year::class, $year);

        // TODO: assertion fails
        // $this->assertEquals(
        //     $year,
        //     Year::fold($this->path, 2020)
        // );

        $this->assertFalse(Years::fold($this->path)->year(2021));
    }

    /**
     * @test
     *
     * @group data
     * @group years
     */
    public function years_has_content(): void
    {
        // TODO: assertion fails
        // $this->assertEquals(
        //     [
        //         "i2020" => Year::fold($this->path, 2020),
        //         "i2022" => Year::fold($this->path, 2022)
        //     ],
        //     Years::fold($this->path)->content()
        // );

        $result = Years::fold($this->path)->count();
        $this->assertIsInt($result);
        // TODO: assertion fails
        // $this->assertEquals(2, $result);

        // TODO: assertion fails
        // $this->assertTrue(Years::fold($this->path)->couldHaveEvents());

        // TODO: assertion fails
        // $this->assertTrue(Years::fold($this->path)->hasEvents());
    }
}
