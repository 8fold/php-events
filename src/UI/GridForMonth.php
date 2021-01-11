<?php
declare(strict_types=1);

namespace LiberatedElephant\Site\Controllers;

use LiberatedElephant\Site\Controllers\AbstractController;

use Carbon\Carbon;

use Eightfold\Events\Events;
use Eightfold\Events\Grid;

use Eightfold\ShoopShelf\Shoop;

use Eightfold\LaravelMarkup\UIKit;

use LiberatedElephant\Site\Store;

use LiberatedElephant\Site\PageComponents\PageTitle;
use LiberatedElephant\Site\PageComponents\Meta;
use LiberatedElephant\Site\PageComponents\Header;

class EventsController extends AbstractController
{
    public function __invoke(...$extras)
    {
        $store = Store::fold(static::localRoot(), "events");
        if (Shoop::this($extras)->efIsEmpty()) {
            $year  = intval(Carbon::now()->year);
            $month = intval(Carbon::now()->month);

            $monthEvents = Events::fold(
                $store->unfold()
            )->month($year, $month);

// TODO: Test
            if ($monthEvents) {
                return redirect("/events/". $year ."/". $month);

            } else {
                $monthEvents = Events::fold(
                    $store->unfold()
                )->nextMonthWithEvents($year, $month);
                $year  = $monthEvents->year();
                $month = $monthEvents->month();
                return redirect("/events/". $year ."/". $month);

            }

        } elseif (Shoop::this($extras)->first()->divide("/")->length()->is(2)->unfold()) {
            $year  = intval(Shoop::this($extras)->first()->divide("/")->first()->unfold());
            $month = intval(Shoop::this($extras)->first()->divide("/")->last()->unfold());

            $monthEvents = Events::fold(
                $store->unfold()
            )->month($year, $month);

// TODO: Test
            if (! $monthEvents) {
                $monthEvents = Events::fold(
                    $store->unfold()
                )->nextMonthWithEvents($year, $month);
                $year  = $monthEvents->year();
                $month = $monthEvents->month();
                return redirect("/events/". $year ."/". $month);

            }

            return UIKit::webView(
                PageTitle::fold($store)->unfold(),
                UIKit::anchor("Skip to main content", "#main")
                    ->attr("class sr-only"),
                UIKit::div(
                    Header::fold($store),
                    UIKit::main(
                        Grid::forMonth($store->unfold(), $year, $month)->unfold()
                    )->attr("id main"),
                    UIKit::footer(
                        UIKit::p($this->copyright("Liberated Elephant, LLC", "2006"))
                    )
                )->attr(
                    $this->mainClass()
                )
            )->meta(
                Meta::fold($store)->unfold()
            );

        } elseif (Shoop::this($extras)->first()->divide("/")->length()->is(1)->unfold()) {
            $year  = intval(Shoop::this($extras)->first()->divide("/")->first()->unfold());

            $yearEvents = Events::fold($store->unfold())->year($year);
            if (! $yearEvents) {
                dd("bounce to nearest month with events");
            }

            return UIKit::webView(
                PageTitle::fold($store)->unfold(),
                UIKit::anchor("Skip to main content", "#main")
                    ->attr("class sr-only"),
                UIKit::div(
                    Header::fold($store),
                    UIKit::main(
                        Grid::forYear($store->unfold(), $year)->unfold()
                    )->attr("id main"),
                    UIKit::footer(
                        UIKit::p($this->copyright("Liberated Elephant, LLC", "2006"))
                    )
                )->attr(
                    $this->mainClass()
                )
            )->meta(
                Meta::fold($store)->unfold()
            );

        }
        abort(404);
    }
}
