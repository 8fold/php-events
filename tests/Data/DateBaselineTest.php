<?php

use Eightfold\Events\Data\Date;

use Eightfold\FileSystem\Item;
use Eightfold\Events\Data\Event;

beforeEach(function() {
    $this->path = Item::create(__DIR__)
        ->up()->append('test-events', 'events')->thePath();
});

test('Date has details', function() {
    $date = Date::fold($this->path, 1999, 1, 10);

    // 3.14ms 29kb
    expect($date->year())->toBeString()->toBe('1999');

    expect($date->year(false))->toBeInt()->toBe(1999);

    // 2.51ms 33kb
    expect($date->month())->toBeString()->toBe('01');

    expect($date->month(false))->toBeInt()->toBe(1);

    // 0.03ms 1kb
    expect($date->date())->toBeString()->toBe('10');

    expect($date->date(false))->toBeInt()->toBe(10);
});

test('Date has content', function() {
    // 15.43ms 521kb
    $this->assertEquals(
        Date::fold($this->path, 2020, 5, 21)->content(),
        [
            $this->path . '/2020/05/21.event' =>
                Event::fold(
                    $this->path,
                    2020,
                    5,
                    21,
                    1,
                    Item::create($this->path . '/2020/05/21.event')
                )
        ]);

    // 11.41ms 327kb
    $this->assertEquals(
        Date::fold($this->path, 2020, 5, 22)->content(),
        [
            $this->path . '/2020/05/22_1.event' =>
                Event::fold(
                    $this->path,
                    2020,
                    5,
                    22,
                    1,
                    Item::create($this->path . '/2020/05/22_1.event')
                ),
            $this->path . '/2020/05/22_2.event' =>
                Event::fold(
                    $this->path,
                    2020,
                    5,
                    22,
                    2,
                    Item::create($this->path . '/2020/05/22_2.event')
                )
        ]);

    expect(
        Date::fold($this->path, 2020, 5, 22)->count()
    )->toBeInt()->toBe(2);

    // 3.89ms 1kb
    expect(
        Date::fold($this->path, 2020, 5, 23)->hasEvents()
    )->toBeFalse();
});
