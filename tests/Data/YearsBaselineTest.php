<?php

use Eightfold\Events\Data\Years;

use Eightfold\FileSystem\Item;

use Eightfold\Events\Data\Year;

beforeEach(function() {
    $this->path = Item::create(__DIR__)
        ->up()->append('test-events', 'events')->thePath();
});

test('Years can get single year', function() {
    $year = Years::fold($this->path)->year(2020);

    expect(
        $year
    )->toBeInstanceOf(Year::class);

    $this->assertEquals(
        $year,
        Year::fold($this->path, 2020)
    );

    expect(
        Years::fold($this->path)->year(2021)
    )->toBeFalse();
})->group('data', 'years', 'focus');

test('Years has content', function() {
    $this->assertEquals(
        Years::fold($this->path)->content(),
        [
            "i2020" => Year::fold($this->path, 2020),
            "i2022" => Year::fold($this->path, 2022)
        ]
    );

    expect(
        Years::fold($this->path)->count()
    )->toBeInt()->toBe(2);

    expect(
        Years::fold($this->path)->couldHaveEvents()
    )->toBeTrue();

    expect(
        Years::fold($this->path)->hasEvents()
    )->toBeTrue();
})->group('data', 'years');
