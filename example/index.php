<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use Carbon\Carbon;

use Eightfold\HTMLBuilder\Element;
use Eightfold\HTMLBuilder\Document;

use Eightfold\Events\Grid;
use Eightfold\Events\Events;

$uri = $_SERVER['REQUEST_URI']; // URI request

$root = 'events'; // URI roots

$dataPath = __DIR__ .'/data'; // Where the events live

$grid = array_filter(explode('/', $uri));
if (count($grid) === 0) {
    $grid = Element::p('404 equivalent: not child of "' . $root . '"');

} elseif (count($grid) === 1) {
    $year  = Carbon::now()->year;
    $month = Carbon::now()->month;
    $monthTarget = Events::fold($dataPath)
        ->nextMonthWithEvents($year, $month);
    if ($month < 10) {
        $month = '0' . $month;
    }
    if (! $monthTarget) {
        $grid = Element::div(
            Element::p('No view for root alone - presumes user will be redirected to closest month with event.'),
            Element::p('Redirect to: /' . $root . '/' . $year . '/' . $month)
        );
    }

} elseif (count($grid) === 2) {
    $year   = intval($grid[2]);
    $events = Events::fold($dataPath);

    $y = $events->year($year);
    if (! $y) {
        $y = $events->nearestYearWithEvents($year);
        $grid = Element::div(
            Element::p('No view for root alone - presumes user will be redirected to closest month with event.'),
            Element::p('Redirect to: '. $y->uri())
        );

    } else {
        $grid = Grid::forYear($dataPath, $y->year())->uriPrefix('/'. $root);

    }

} elseif (count($grid) === 3) {
    $year   = intval($grid[2]);
    $month  = intval($grid[3]);
    $events = Events::fold($dataPath);

    $m = $events->month($year, $month);
    if (! $m) {
        // No month - display empty grid
        $grid = Grid::forMonth($dataPath, $year, $month)->unfold();

    } else {
        $grid = Grid::forMonth($dataPath, $year, $m->month())->unfold();

    }

} else {
    die('URL too long');

}

print Document::create(
            '8fold Events Example'
        )->head(
            Element::link()->props('rel stylesheet', 'href /ef-events.css'),
            Element::script()->props('src /ef-events.min.js')
        )->body(
            $grid
        );
