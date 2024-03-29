<?php

declare(strict_types=1);

namespace Eightfold\Events\Tests\UI;

use PHPUnit\Framework\TestCase;

use SplFileInfo;

use Eightfold\Events\Grid;

class GridMonthPerformanceTest extends TestCase
{
    private string $path = '';

    private string $grid = '';

    public function setUp(): void
    {
        $this->path = (new SplFileInfo(__DIR__ . '/../test-events/events'))
            ->getRealPath();

        $this->grid = '<div class="ef-events-grid ef-events-grid-month" aria-live="assertive"><h2>May 2020</h2><span class="ef-grid-previous-month"></span><a class="ef-grid-next-month" href="/events/2020/12" title="December 2020"><span>December 2020</span></a><div id="ef-events-modals" aria-hidden="true" onclick="EFEventsModals.closeAll()"><div role="dialog" id="20200501"><h3>1st of May 2020</h3><h4>Hello, World!</h4><p>Hello, World!</p><button onclick="EFEventsModals.closeAll()"><span>close</span></button></div><div role="dialog" id="20200519"><h3>19th of May 2020</h3><h4>Hello, World!</h4><p>Hello, World!</p><button onclick="EFEventsModals.closeAll()"><span>close</span></button></div><div role="dialog" id="20200520"><h3>20th of May 2020</h3><h4>Hello, World!</h4><p>Hello, World!</p><button onclick="EFEventsModals.closeAll()"><span>close</span></button></div><div role="dialog" id="20200521"><h3>21st of May 2020</h3><h4>Hello, Event!</h4><p>Hello, Event!</p><button onclick="EFEventsModals.closeAll()"><span>close</span></button></div><div role="dialog" id="20200522"><h3>22nd of May 2020</h3><h4>Hello, Event!</h4><p>Hello, Event!</p><h4>Hello, Day?</h4><p>Something</p><button onclick="EFEventsModals.closeAll()"><span>close</span></button></div></div><abbr class="ef-weekday-heading" title="Monday">Mon</abbr><abbr class="ef-weekday-heading" title="Tuesday">Tue</abbr><abbr class="ef-weekday-heading" title="Wednesday">Wed</abbr><abbr class="ef-weekday-heading" title="Thursday">Thu</abbr><abbr class="ef-weekday-heading" title="Friday">Fri</abbr><abbr class="ef-weekday-heading" title="Saturday">Sat</abbr><abbr class="ef-weekday-heading" title="Sunday">Sun</abbr><button role="presentation" aria-disabled="true" disabled></button><button role="presentation" aria-disabled="true" disabled></button><button role="presentation" aria-disabled="true" disabled></button><button role="presentation" aria-disabled="true" disabled></button><button id="toggle-20200501" class="calendar-date" aria-expanded="false" onclick="EFEventsModals.init(this, 20200501)"><abbr title="1st of May 2020">1</abbr><span>Hello, World!</span></button><button role="presentation" aria-disabled="true" disabled><abbr title="2nd of May 2020">2</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="3rd of May 2020">3</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="4th of May 2020">4</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="5th of May 2020">5</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="6th of May 2020">6</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="7th of May 2020">7</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="8th of May 2020">8</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="9th of May 2020">9</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="10th of May 2020">10</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="11th of May 2020">11</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="12th of May 2020">12</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="13th of May 2020">13</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="14th of May 2020">14</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="15th of May 2020">15</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="16th of May 2020">16</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="17th of May 2020">17</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="18th of May 2020">18</abbr></button><button id="toggle-20200519" class="calendar-date" aria-expanded="false" onclick="EFEventsModals.init(this, 20200519)"><abbr title="19th of May 2020">19</abbr><span>Hello, World!</span></button><button id="toggle-20200520" class="calendar-date" aria-expanded="false" onclick="EFEventsModals.init(this, 20200520)"><abbr title="20th of May 2020">20</abbr><span>Hello, World!</span></button><button id="toggle-20200521" class="calendar-date" aria-expanded="false" onclick="EFEventsModals.init(this, 20200521)"><abbr title="21st of May 2020">21</abbr><span>Hello, Event!</span></button><button id="toggle-20200522" class="calendar-date" aria-expanded="false" onclick="EFEventsModals.init(this, 20200522)"><abbr title="22nd of May 2020">22</abbr><span>Hello, Event!</span><span>Hello, Day?</span></button><button role="presentation" aria-disabled="true" disabled><abbr title="23rd of May 2020">23</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="24th of May 2020">24</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="25th of May 2020">25</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="26th of May 2020">26</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="27th of May 2020">27</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="28th of May 2020">28</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="29th of May 2020">29</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="30th of May 2020">30</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="31st of May 2020">31</abbr></button></div>';
    }

    /**
     * @test
     *
     * @group ui
     * @group month
     */
    public function month_grid_is_speedy(): void
    {
        $start = hrtime(true);

        $result = Grid::forMonth($this->path, 2020, 5)->unfold();

        $end = hrtime(true);

        $this->assertEquals($this->grid, $result);

        $elapsed = $end - $start;
        $ms      = $elapsed/1e+6;

        $this->assertLessThan(44, $ms); // pre-commonmark 13ms previous 1835.7ms
    }

    /**
     * @test
     *
     * @group ui
     * @group month
     */
    public function month_grid_is_small(): void
    {
        $start = memory_get_usage();

        $result = Grid::forMonth($this->path, 2020, 5)->unfold();

        $end = memory_get_usage();

        $this->assertEquals($this->grid, $result);

        $used = $end - $start;
        $kb   = round($used/1024.2);

        $this->assertLessThan(282, $kb); // pre-commonmark 9kb previous 4165kb
    }
}
