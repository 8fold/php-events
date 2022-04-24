<?php

namespace Eightfold\Events\Tests\UI;

use Eightfold\Events\UI\GridForYear;

use Eightfold\FileSystem\Item;

// beforeEach(function() {
//     $this->path = Item::create(__DIR__)
//         ->up()->append('test-events', 'events')->thePath();
// });
//
// test('Year grid has next and previous links', function() {
//     expect(
//         GridForYear::fold($this->path, 2020)->previousLink()->build()
//     )->toBe(
//         '<span class="ef-grid-previous-year"></span>'
//     );
//
//     expect(
//         GridForYear::fold($this->path, 2021)->previousLink()->build()
//     )->toBe(
//         '<a class="ef-grid-previous-year" href="/events/2020" title="2020"><span>2020</span></a>'
//     );
//
//     expect(
//         GridForYear::fold($this->path, 2021)->nextLink()->build()
//     )->toBe(
//         '<a class="ef-grid-next-year" href="/events/2022" title="2022"><span>2022</span></a>'
//     );
//
//     expect(
//         '<a href="/events/2020/05"><abbr title="May 2020">May</abbr><span>5</span></a>'
//     )->toBe(
//         GridForYear::fold($this->path, 2020)->gridItem(5)->build()
//     );
// })->group('ui', 'year');
//
// test('Year grid has expected title', function() {
//     expect(
//         GridForYear::fold($this->path, 2020)->header()->build()
//     )->toBe(
//         '<h2>2020</h2>'
//     );
// })->group('ui', 'year');
