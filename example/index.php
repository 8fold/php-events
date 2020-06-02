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

$grid = Shoop::string($uri)->divide("/", false)->isEmpty(function($result, $parts) use ($root, $dataPath) {
    $parts = $parts->reindex();
    if ($result) {
        return UIKit::p("404 equivalent: not child of '". $root ."'");
    }
    return $parts->count()->is(1, function($result, $count) use ($root, $dataPath, $parts) {
        if ($result) {
            $year  = Carbon::now()->year;
            $month = Carbon::now()->month;
            $uri = Events::init(__DIR__ ."/data")
                ->nearestMonthWithEvents($year, $month)->uri();
            return UIKit::div(
                UIKit::p("No view for root alone - presumes user will be redirected to closest month with event."),
                UIKit::p("Redirect to: /". $root . $uri)
            );
        }
        return $count->is(2, function($result, $count) use ($root, $dataPath, $parts) {
            if ($result) {
                $year = $parts->last()->int;
                return Grid::forYear($dataPath ."/{$year}")->uriPrefix("/{$root}");

            }
            return $count->is(3, function($result, $count) use ($root, $dataPath, $parts) {
                if ($result) {
                    $year = $parts->get(1)->int;
                    $month = $parts->get(2)->int;
                    return Grid::forMonth("{$dataPath}/{$year}/{$month}")->uriPrefix("/{$root}");
                }
            });
        });
    });
});

$view = UIKit::webView(
            "8fold Events Example", 
            $grid->unfold(), 
            UIKit::button("Test page with button on it.")
        )->meta(
            UIKit::link()->attr("rel stylesheet", "href /grids.css"),
            UIKit::script()->attr("src /ef-events.js")
        );

print $view;
