<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__.'/../vendor/autoload.php';

use Carbon\Carbon;

use Eightfold\Shoop\Shoop;
use Eightfold\Markup\UIKit;

use Eightfold\Events\Grid;
use Eightfold\Events\Events;

$uri = $_SERVER['REQUEST_URI']; // URI request

$root = "things"; // URI roots

$dataPath = __DIR__ ."/data"; // Where the events live

$grid = Shoop::this($uri)->divide("/", false);
if ($grid->efIsEmpty()) {
    $grid = UIKit::p("404 equivalent: not child of '". $root ."'");

} elseif ($grid->length()->is(1)->unfold()) {
    $year  = Carbon::now()->year;
    $month = Carbon::now()->month;
    $uri = Events::fold(__DIR__ ."/data")
        ->nearestMonthWithEvents($year, $month)->uri();
    $grid = UIKit::div(
        UIKit::p("No view for root alone - presumes user will be redirected to closest month with event."),
        UIKit::p("Redirect to: /". $root . $uri)
    );

} elseif ($grid->length()->is(2)->unfold()) {
    $year   = $grid->at(1)->efToInteger();
    $events = Events::fold(__DIR__ ."/data");

    $y = $events->year($year);
    if (! $y) {
        $y = $events->nearestYearWithEvents($year);
        $grid = UIKit::div(
            UIKit::p("No view for root alone - presumes user will be redirected to closest month with event."),
            UIKit::p("Redirect to: ". $y->uri())
        );

    } else {
        $grid = Grid::forYear($dataPath, $y->year())->uriPrefix("/". $root);

    }

} elseif ($grid->length()->is(3)->unfold()) {
    $year   = $grid->at(1)->efToInteger();
    $month  = $grid->at(2)->efToInteger();
    $events = Events::fold(__DIR__ ."/data");

    $m = $events->month($year, $month);
    if (! $m) {
        $m = $events->nearestMonthWithEvents($year, $month);
        $grid = UIKit::div(
            UIKit::p("No view for root alone - presumes user will be redirected to closest month with event."),
            UIKit::p("Redirect to: ". $m->uri())
        );

    } else {
        $grid = Grid::forMonth($dataPath, $year, $m->month())->uriPrefix("/". $root);

    }

} else {
    die("URL too long");

}

$view = UIKit::webView(
            "8fold Events Example",
            $grid->unfold()
        )->meta(
            UIKit::link()->attr("rel stylesheet", "href /ef-events.css"),
            UIKit::script()->attr("src /ef-events.min.js")
        );

print $view;
