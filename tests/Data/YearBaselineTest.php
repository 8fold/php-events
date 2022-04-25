<?php

declare(strict_types=1);

namespace Eightfold\Events\Tests\Data;

use PHPUnit\Framework\TestCase;

use SplFileInfo;

use Eightfold\Events\Data\Year;
use Eightfold\Events\Data\Event;
use Eightfold\Events\Data\Month;

class YearBaselineTest extends TestCase
{
    private string $path = '';

    public function setUp(): void
    {
        $this->path = (new SplFileInfo(__DIR__ . '/../test-events/events'))
            ->getRealPath();
    }

    /**
     * @test
     *
     * @group data
     * @group year
     */
    public function year_can_answer_before_or_after(): void
    {
        $this->assertFalse((new Year($this->path, 2020))->isAfter(2020));

        $this->assertTrue((new Year($this->path, 2020))->isAfter(2019));

        $this->assertFalse((new Year($this->path, 2020))->isAfter(2021));

        $this->assertFalse((new Year($this->path, 2020))->isBefore(2020));

        $this->assertFalse((new Year($this->path, 2020))->isBefore(2019));

        $this->assertTrue((new Year($this->path, 2020))->isBefore(2021));
    }

    /**
     * @test
     *
     * @group data
     * @group year
     */
    public function year_has_details(): void
    {
        $year = new Year($this->path, 2020);

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
            (new Year($this->path, 2020))->content(),
            [
                "i05" => new Month(
                    $this->path,
                    2020,
                    5
                ),
                "i12" => new Month(
                    $this->path,
                    2020,
                    12
                )
            ]
        );

        $result = (new Year($this->path, 2020))->count();
        $this->assertIsInt($result);
        $this->assertEquals(2, $result);

        $this->assertTrue((new Year($this->path, 2020))->couldHaveEvents());

        $this->assertTrue((new Year($this->path, 2020))->hasEvents());

        $this->assertFalse((new Year($this->path, 2021))->hasEvents());
    }
}
