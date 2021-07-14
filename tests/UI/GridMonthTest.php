<?php

namespace Eightfold\Events\Tests\UI;

use PHPUnit\Framework\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\Shoop\Shoop;

use Eightfold\Events\Events;
use Eightfold\Events\Grid;
use Eightfold\Events\UI\GridForMonth;

class GridMonthTest extends TestCase
{
    private $path = "";

    public function setUp(): void
    {
        $this->path = Shoop::this(__DIR__)->divide("/")->dropLast()
            ->append(["test-events", "events"])->asString("/");
    }

// -> forMonth
    /**
     * @test
     */
    public function render_header()
    {
        AssertEquals::applyWith(
            '<h2>May 2020</h2>',
            "string",
            16.08,
            2211
        )->unfoldUsing(
            GridForMonth::fold($this->path->unfold(), 2020, 5)->header()
        );
    }

    /**
     * @test
     */
    public function render_previous_link()
    {
        AssertEquals::applyWith(
            '<span class="ef-grid-previous-month"></span>',
            "string",
            19.49, // 16.4, // 16.28, // 15.92, // 15.58, // 14.6,
            2585
        )->unfoldUsing(
            GridForMonth::fold($this->path->unfold(), 2020, 5)->previousLink()
        );

        AssertEquals::applyWith(
            '<span class="ef-grid-next-month"></span>',
            "string",
            7.64, // 7.03, // 6.56, // 5.13, // 2.52, // 1.99, // 1.82, // 1.51, // 1.48, // 1.47, // 1.12,
            1
        )->unfoldUsing(
            GridForMonth::fold($this->path->unfold(), 2023, 5)->nextLink()
        );

        AssertEquals::applyWith(
            '<a class="ef-grid-next-month" href="/events/2022/05" title="May 2022"><span>May 2022</span></a>',
            "string",
            44.48, // 39.18, // 28.19, // 16.65,
            458
        )->unfoldUsing(
            GridForMonth::fold($this->path->unfold(), 2020, 12)->nextLink()
        );

        AssertEquals::applyWith(
            '<button role="presentation" aria-disabled="true" disabled><abbr title="1st of February 2020">1</abbr></button>',
            "string",
            14.44, // 11.93, // 11.89, // 11.83, // 8.32, // 6.08, // 5.57, // 5.52, // 4.47, // 4.21, // 3.36, // 3.06,
            19 // 18
        )->unfoldUsing(
            GridForMonth::fold($this->path->unfold(), 2020, 2)->gridItem(1)
        );

        AssertEquals::applyWith(
            '<button id="toggle-20200522" class="calendar-date" onclick="EFEventsModals.init(this, 20200522)" aria-expanded="false"><abbr title="22nd of May 2020">22</abbr><span>Hello, Event!</span><span>Hello, Day?</span></button>',
            "string",
            40.92, // 38.53, // 35.55, // 35.28, // 35.22, // 35.09, // 15.72, // 15, // 13.53, // 12.68, // 9.63, // 8.03, // 7.79,
            3
        )->unfoldUsing(
            GridForMonth::fold($this->path->unfold(), 2020, 5)->gridItem(22)
        );
    }

    /**
     * @test
     * @group current
     */
    public function rendered_grid()
    {
        AssertEquals::applyWith(
            '<div class="ef-events-grid ef-events-grid-month" aria-live="assertive"><h2>May 2020</h2><span class="ef-grid-previous-month"></span><a class="ef-grid-next-month" href="/events/2020/12" title="December 2020"><span>December 2020</span></a><div id="ef-events-modals" onclick="EFEventsModals.closeAll()" aria-hidden="true"><div role="dialog" id="20200519"><h3>19th of May 2020</h3><h4>Hello, World!</h4><p>Hello, World!</p><button onclick="EFEventsModals.closeAll()"><span>close</span></button></div><div role="dialog" id="20200520"><h3>20th of May 2020</h3><h4>Hello, World!</h4><p>Hello, World!</p><button onclick="EFEventsModals.closeAll()"><span>close</span></button></div><div role="dialog" id="20200521"><h3>21st of May 2020</h3><h4>Hello, Event!</h4><p>Hello, Event!</p><button onclick="EFEventsModals.closeAll()"><span>close</span></button></div><div role="dialog" id="20200522"><h3>22nd of May 2020</h3><h4>Hello, Event!</h4><p>Hello, Event!</p><h4>Hello, Day?</h4><p>Something</p><button onclick="EFEventsModals.closeAll()"><span>close</span></button></div></div><abbr class="ef-weekday-heading" title="Monday">Mon</abbr><abbr class="ef-weekday-heading" title="Tuesday">Tue</abbr><abbr class="ef-weekday-heading" title="Wednesday">Wed</abbr><abbr class="ef-weekday-heading" title="Thursday">Thu</abbr><abbr class="ef-weekday-heading" title="Friday">Fri</abbr><abbr class="ef-weekday-heading" title="Saturday">Sat</abbr><abbr class="ef-weekday-heading" title="Sunday">Sun</abbr><button role="presentation" aria-disabled="true" disabled></button><button role="presentation" aria-disabled="true" disabled></button><button role="presentation" aria-disabled="true" disabled></button><button role="presentation" aria-disabled="true" disabled></button><button role="presentation" aria-disabled="true" disabled><abbr title="1st of May 2020">1</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="2nd of May 2020">2</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="3rd of May 2020">3</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="4th of May 2020">4</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="5th of May 2020">5</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="6th of May 2020">6</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="7th of May 2020">7</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="8th of May 2020">8</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="9th of May 2020">9</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="10th of May 2020">10</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="11th of May 2020">11</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="12th of May 2020">12</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="13th of May 2020">13</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="14th of May 2020">14</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="15th of May 2020">15</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="16th of May 2020">16</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="17th of May 2020">17</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="18th of May 2020">18</abbr></button><button id="toggle-20200519" class="calendar-date" onclick="EFEventsModals.init(this, 20200519)" aria-expanded="false"><abbr title="19th of May 2020">19</abbr><span>Hello, World!</span></button><button id="toggle-20200520" class="calendar-date" onclick="EFEventsModals.init(this, 20200520)" aria-expanded="false"><abbr title="20th of May 2020">20</abbr><span>Hello, World!</span></button><button id="toggle-20200521" class="calendar-date" onclick="EFEventsModals.init(this, 20200521)" aria-expanded="false"><abbr title="21st of May 2020">21</abbr><span>Hello, Event!</span></button><button id="toggle-20200522" class="calendar-date" onclick="EFEventsModals.init(this, 20200522)" aria-expanded="false"><abbr title="22nd of May 2020">22</abbr><span>Hello, Event!</span><span>Hello, Day?</span></button><button role="presentation" aria-disabled="true" disabled><abbr title="23rd of May 2020">23</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="24th of May 2020">24</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="25th of May 2020">25</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="26th of May 2020">26</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="27th of May 2020">27</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="28th of May 2020">28</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="29th of May 2020">29</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="30th of May 2020">30</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="31st of May 2020">31</abbr></button></div>',
            "string",
            1835.7, // 1633.05, // 1335.19, // 496.1, // 485.47, // 484.4, // 481.08, // 458.7, // 436.29, // 365.46, // 312.88,
            4165
        )->unfoldUsing(
            Grid::forMonth($this->path->unfold(), 2020, 5)->unfold()
        );
    }
}
