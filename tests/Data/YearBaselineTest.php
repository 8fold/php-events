<?php

declare(strict_types=1);

namespace Eightfold\Events\Tests\Data;

use PHPUnit\Framework\TestCase;

use Eightfold\Events\Data\Year;

use Eightfold\FileSystem\Item;
use Eightfold\Events\Data\Event;
use Eightfold\Events\Data\Month;

class YearBaselineTest extends TestCase
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
     * @group year
     */
    public function year_can_answer_before_or_after(): void
    {
        $this->assertFalse(Year::fold($this->path, 2020)->isAfter(2020));

        $this->assertTrue(Year::fold($this->path, 2020)->isAfter(2019));

        $this->assertFalse(Year::fold($this->path, 2020)->isAfter(2021));

        $this->assertFalse(Year::fold($this->path, 2020)->isBefore(2020));

        $this->assertFalse(Year::fold($this->path, 2020)->isBefore(2019));

        $this->assertTrue(Year::fold($this->path, 2020)->isBefore(2021));
    }

    /**
     * @test
     *
     * @group data
     * @group year
     */
    public function year_has_details(): void
    {
        $year = Year::fold($this->path, 2020);

        $result = $year->yearString();
        $this->assertIsString($result);
        $this->assertEquals('2020', $result);

        $result = $year->year();
        $this->assertIsInt($result);
        $this->assertEquals(2020, $result);
    }

    /**
     * @test
     *
     * @group data
     * @group year
     */
    public function year_has_content(): void
    {
        $this->assertEquals(
            Year::fold($this->path, 2020)->content(),
            [
                "i05" => Month::fold(
                    $this->path,
                    2020,
                    5,
                    Item::create($this->path . '/2020/05')
                ),
                "i12" => Month::fold(
                    $this->path,
                    2020,
                    12,
                    Item::create($this->path . '/2020/12')
                )
            ]
        );

        $result = Year::fold($this->path, 2020)->count();
        $this->assertIsInt($result);
        $this->assertEquals(2, $result);

        $this->assertTrue(Year::fold($this->path, 2020)->couldHaveEvents());

        $this->assertTrue(Year::fold($this->path, 2020)->hasEvents());

        $this->assertFalse(Year::fold($this->path, 2021)->hasEvents());
    }
}
