<?php

namespace Eightfold\Events\Tests\UI;

use Eightfold\Events\Grid;

use Eightfold\FileSystem\Item;

beforeEach(function() {
    $this->path = Item::create(__DIR__)
        ->up()->append('test-events', 'events')->thePath();

    $this->grid = '<div class="ef-events-grid ef-events-grid-year"><h2>2020</h2><span class="ef-grid-previous-year"></span><a class="ef-grid-next-year" href="/events/2022" title="2022"><span>2022</span></a><button role="presentation" aria-disabled="true" disabled><abbr title="January 2020">Jan</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="February 2020">Feb</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="March 2020">Mar</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="April 2020">Apr</abbr></button><a href="/events/2020/05"><abbr title="May 2020">May</abbr><span>5</span></a><button role="presentation" aria-disabled="true" disabled><abbr title="June 2020">Jun</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="July 2020">Jul</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="August 2020">Aug</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="September 2020">Sep</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="October 2020">Oct</abbr></button><button role="presentation" aria-disabled="true" disabled><abbr title="November 2020">Nov</abbr></button><a href="/events/2020/12"><abbr title="December 2020">Dec</abbr><span>3</span></a></div>';
});

test('Year grid is speedy', function() {
    $start = hrtime(true);

    $result = Grid::forYear($this->path, 2020)->unfold();

    $end = hrtime(true);

    expect(
        $result
    )->toBe(
        $this->grid
    );

    $elapsed = $end - $start;
    $ms      = $elapsed/1e+6;

    expect($ms)->toBeLessThan(6); // previous 1835.7ms
})->group('ui', 'year');

test('Year grid is small', function() {
    $start = memory_get_usage();

    $result = Grid::forYear($this->path, 2020, 5)->unfold();

    $end = memory_get_usage();

    expect(
        $result
    )->toBe(
        $this->grid
    );

    $used = $end - $start;
    $kb   = round($used/1024.2);

    expect($kb)->toBeLessThan(3); // previous 4165kb
})->group('ui', 'year');
