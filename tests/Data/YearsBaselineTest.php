<?php

declare(strict_types=1);

namespace Eightfold\Events\Tests\Data;

use PHPUnit\Framework\TestCase;

use SplFileInfo;

use Eightfold\Events\Data\Years;
use Eightfold\Events\Data\Year;

class YearsBaselineTest extends TestCase
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
     * @group years
     */
    public function years_can_get_single_year(): void
    {
        $year = (new Years($this->path))->year(2020);

        $this->assertInstanceOf(Year::class, $year);

        $this->assertEquals(
            $year,
            new Year($this->path, 2020)
        );

        $this->assertFalse((new Years($this->path))->year(2021));
    }

    /**
     * @test
     *
     * @group data
     * @group years
     */
    public function years_has_content(): void
    {
        $this->assertEquals(
            [
                "i2020" => new Year($this->path, 2020),
                "i2022" => new Year($this->path, 2022)
            ],
            (new Years($this->path))->content()
        );

        $result = (new Years($this->path))->count();
        $this->assertIsInt($result);
        $this->assertEquals(2, $result);

        $this->assertTrue((new Years($this->path))->couldHaveEvents());

        $this->assertTrue((new Years($this->path))->hasEvents());
    }
}
