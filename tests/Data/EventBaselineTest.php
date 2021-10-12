<?php

use Eightfold\Events\Data\Event;

use Eightfold\FileSystem\Item;

beforeEach(function() {
    $this->path = Item::create(__DIR__)
        ->up()->append('test-events', 'events')->thePath();
});

test('Event has event details', function() {
    $event = Event::fold($this->path, 2020, 5, 22, 2);

    // 0.59ms 27kb
    expect($event->year())->toBeString()->toBe('2020');

    expect($event->year(false))->toBeInt()->toBe(2020);

    // 0.51ms 30kb
    expect($event->month())->toBeString()->toBe('05');

    expect($event->month(false))->toBeInt()->toBe(5);

    // 0.008ms 1kb
    expect($event->date())->toBeString()->toBe('22');

    expect($event->date(false))->toBeInt()->toBe(22);
})->group('data', 'event');

test('Event has content', function() {
    // 9.66ms 319kb
    expect(
        Event::fold($this->path, 2020, 5, 20, 1)->content()
    )->toBeString()->toBe(
        'Hello, World!'
    );

    expect(
        Event::fold($this->path, 2020, 5, 22, 2)->content()
    )->toBeString()->toBe(<<<md
        Hello, Day?

        Something

        md
    );

    expect(
        Event::fold($this->path, 2020, 5, 23, 2)->content()
    )->toBeString()->toBeEmpty();
})->group('data', 'event');

test('Event can be separated by title and body', function() {
    expect(
        Event::fold($this->path, 2020, 5, 22, 2)->title()
    )->toBeString()->toBe('Hello, Day?');

    expect(
        Event::fold($this->path, 2020, 5, 22, 2)->body()
    )->toBeString()->toBe('Something');
})->group('data', 'event');

test('Event can check for events', function() {
    // 2.72ms 92kb
    expect(
        Event::fold($this->path, 2020, 5, 20, 1)->hasEvents()
    )->toBeTrue();

    expect(
        Event::fold($this->path, 2020, 5, 22, 2)->hasEvents()
    )->toBeTrue();

    expect(
        Event::fold($this->path, 2020, 5, 23, 2)->hasEvents()
    )->toBeFalse();
})->group('data', 'event');
