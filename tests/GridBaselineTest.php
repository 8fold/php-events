<?php

namespace Eightfold\Events\Tests;

use Eightfold\Events\Grid;

use Eightfold\FileSystem\Item;

// use Eightfold\Events\UI\GridForYear;
// use Eightfold\Events\UI\GridForMonth;

beforeEach(function() {
    $this->path = Item::create(__DIR__)
        ->append('test-events', 'events')->thePath();
});
test('Grid render month', function() {
    expect(
        Grid::forMonth($this->path, 2020, 5)->header()->build()
    )->toBe(
        '<h2>May 2020</h2>'
    );

    expect(
        Grid::forMonth($this->path, 2020, 5)->previousLink()->build()
    )->toBe(
        '<span class="ef-grid-previous-month"></span>'
    );
})->group('ui', 'grid', 'month');

test('Grid render year', function() {
    expect(
        Grid::forYear($this->path, 2020)->header()->build()
    )->toBe(
        '<h2>2020</h2>'
    );
})->group('ui', 'grid', 'year');

test('Grid total grid items', function() {
    expect(
        Grid::forYear($this->path, 2020)->totalGridItems()
    )->toBe(
        12
    );

    expect(
        Grid::forMonth($this->path, 2020, 5)->totalStartGridBlanks()
    )->toBe(
        4
    );

    expect(
        Grid::forMonth($this->path, 2020, 5)->daysInMonth()
    )->toBe(
        31
    );

    expect(
        Grid::forMonth($this->path, 2020, 5)->totalEndGridBlanks()
    )->toBe(
        0
    );

    expect(
        Grid::forMonth($this->path, 2020, 4)->totalStartGridBlanks()
    )->toBe(
        2
    );

    expect(
        Grid::forMonth($this->path, 2020, 4)->daysInMonth()
    )->toBe(
        30
    );

    expect(
        Grid::forMonth($this->path, 2020, 4)->totalEndGridBlanks()
    )->toBe(
        3
    );
})->group('ui', 'grid');
