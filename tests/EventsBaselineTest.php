<?php

namespace Eightfold\Events\Tests;

use Eightfold\Events\Events;

use Eightfold\FileSystem\Item;

use Eightfold\Events\Data\Year;
use Eightfold\Events\Data\Years;

beforeEach(function() {
    $this->path = Item::create(__DIR__)
        ->append('test-events', 'events')->thePath();
});

test('Event seeking', function() {
    expect(
        Events::fold($this->path)->nextYearWithEvents(2020)->year()
    )->toBe(2022);

    expect(
        Events::fold($this->path)->previousYearWithEvents(2023)->year()
    )->toBe(2022);

    expect(
        Events::fold($this->path)->nextMonthWithEvents(1990, 4)->month()
    )->toBe(5);

    expect(
        Events::fold($this->path)->nextMonthWithEvents(2020, 4)->month()
    )->toBe(5);

    expect(
        Events::fold($this->path)->nextMonthWithEvents(2020, 12)->month()
    )->toBe(5);

    expect(
        Events::fold($this->path)->previousMonthWithEvents(2020, 7)->month()
    )->toBe(5);

    expect(
        Events::fold($this->path)->previousMonthWithEvents(2023, 1)->month()
    )->toBe(12);

    expect(
        Events::fold($this->path)->previousMonthWithEvents(2023, 10)->year()
    )->toBe(2022);
})->group('events', 'years', 'data');

test('Events has content', function() {
    $this->assertEquals(
        Events::fold($this->path)->years()->content(),
        [
            "i2020" => Year::fold($this->path, 2020),
            "i2022" => Year::fold($this->path, 2022)
        ]
    );
})->group('events', 'years', 'data');
