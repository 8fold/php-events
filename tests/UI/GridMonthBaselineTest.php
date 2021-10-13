<?php

namespace Eightfold\Events\Tests\UI;

use Eightfold\Events\UI\GridForMonth;

use Eightfold\FileSystem\Item;

beforeEach(function() {
    $this->path = Item::create(__DIR__)
        ->up()->append('test-events', 'events')->thePath();
});

test('Month can go to next or previous year for next or previous link, respectively', function() {
    expect(
        GridForMonth::fold($this->path, 2020, 12)->nextLink()->build()
    )->toBe(
        '<a class="ef-grid-next-month" href="/events/2022/05" title="May 2022"><span>May 2022</span></a>'
    );

    expect(
        GridForMonth::fold($this->path, 2022, 5)->previousLink()->build()
    )->toBe(
        '<a class="ef-grid-previous-month" href="/events/2020/12" title="December 2020"><span>December 2020</span></a>'
    );
})->group('ui', 'month');

test('Month grid has next and previous links', function() {
    expect(
        GridForMonth::fold($this->path, 2020, 5)->previousLink()->build()
    )->toBe(
        '<span class="ef-grid-previous-month"></span>'
    );

    expect(
        GridForMonth::fold($this->path, 2023, 5)->nextLink()->build()
    )->toBe(
        '<span class="ef-grid-next-month"></span>'
    );

    expect(
        GridForMonth::fold($this->path, 2020, 12)->nextLink()->build()
    )->toBe(
        '<a class="ef-grid-next-month" href="/events/2022/05" title="May 2022"><span>May 2022</span></a>'
    );

    expect(
        GridForMonth::fold($this->path, 2020, 2)->gridItem(1)->build()
    )->toBe(
        '<button role="presentation" aria-disabled="true" disabled><abbr title="1st of February 2020">1</abbr></button>'
    );

    expect(
        GridForMonth::fold($this->path, 2020, 5)->gridItem(22)->build()
    )->toBe(
        '<button id="toggle-20200522" class="calendar-date" aria-expanded="false" onclick="EFEventsModals.init(this, 20200522)"><abbr title="22nd of May 2020">22</abbr><span>Hello, Event!</span><span>Hello, Day?</span></button>'
    );
})->group('ui', 'month');

test('Month grid has expected title', function() {
    expect(
        GridForMonth::fold($this->path, 2020, 5)->header()->build()
    )->toBe(
        '<h2>May 2020</h2>'
    );
})->group('ui', 'month');
