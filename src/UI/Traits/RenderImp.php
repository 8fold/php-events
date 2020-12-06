<?php

namespace Eightfold\Events\UI\Traits;

// use Carbon\Carbon;

// use Eightfold\Events\Events;
// use Eightfold\Events\Grid;
use Eightfold\Markup\UIKit;

use Eightfold\Events\Data\Year;
use Eightfold\Events\Data\Month;

trait RenderImp
{
    public function navLink($uriObject, string $title, string $class)
    {
        if (! $uriObject) {
            return UIKit::span()->attr("class {$class}");
        }

        return UIKit::a(
            UIKit::span($title)
        )->attr(
            "class {$class}",
            "title {$title}",
            "href ". $this->prefix() . $uriObject->uri()
        );
    }
}
