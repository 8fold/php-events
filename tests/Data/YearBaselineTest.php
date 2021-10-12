<?php

use Eightfold\Events\Data\Year;

use Eightfold\FileSystem\Item;
use Eightfold\Events\Data\Event;
use Eightfold\Events\Data\Month;

beforeEach(function() {
    $this->path = Item::create(__DIR__)
        ->up()->append('test-events', 'events')->thePath();
});

test('Year has details', function() {
    $year = Year::fold($this->path, 2020);

    // 3.14ms 29kb
    expect($year->year())->toBeString()->toBe('2020');

    expect($year->year(false))->toBeInt()->toBe(2020);
})->group('focus');

test('Year has content', function() {
    $this->assertEquals(
        Year::fold($this->path, 2020)->content(),
        [
            "i05" => Month::fold($this->path, 2020, 5),
            "i12" => Month::fold($this->path, 2020, 12)
        ]
    );

    expect(
        Year::fold($this->path, 2020)->count()
    )->toBeInt()->toBe(2);

    expect(
        Year::fold($this->path, 2020)->couldHaveEvents()
    )->toBeTrue();

    expect(
        Year::fold($this->path, 2020)->hasEvents()
    )->toBeTrue();

    expect(
        Year::fold($this->path, 2021)->hasEvents()
    )->toBeFalse();
});
