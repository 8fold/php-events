<?php

use Eightfold\Events\Data\Month;

use Eightfold\FileSystem\Item;
use Eightfold\Events\Data\Event;

beforeEach(function() {
    $this->path = Item::create(__DIR__)
        ->up()->append('test-events', 'events')->thePath();
});

test('Month has details', function() {
    $month = Month::fold($this->path, 1999, 1);

    // 3.14ms 29kb
    expect($month->year())->toBeString()->toBe('1999');

    expect($month->year(false))->toBeInt()->toBe(1999);

    // 2.51ms 33kb
    expect($month->month())->toBeString()->toBe('01');

    expect($month->month(false))->toBeInt()->toBe(1);

    expect(
        Month::fold($this->path, 2020, 5)->daysInMonth()
    )->toBeInt()->toBe(31);
})->group('data', 'month');

test('Month has content', function() {
    // 10.6ms 396kb
    expect(
        Month::fold($this->path, 2020, 5)->count()
    )->toBeInt()->toBe(4);

    // 13.75ms 415kb
    expect(
        Month::fold($this->path, 2020, 5)->hasEvents()
    )->toBeTrue();

    // 1.36ms 1kb
    expect(
        Month::fold($this->path, 2020, 6)->hasEvents()
    )->toBeFalse();

    // 1.26ms 1
    expect(
        Month::fold($this->path, 2020, 10)->couldHaveEvents()
    )->toBeFalse();
})->group('data', 'month');
