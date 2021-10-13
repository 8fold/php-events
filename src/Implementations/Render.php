<?php

declare(strict_types=1);

namespace Eightfold\Events\Implementations;

// use Carbon\Carbon;

// use Eightfold\Events\Events;
// use Eightfold\Events\Grid;
use Eightfold\HTMLBuilder\Element as HtmlElement;

use Eightfold\Events\Data\Year;
use Eightfold\Events\Data\Month;

trait Render
{
    private $uriPrefix = '/events';

    public function navLink($uriObject, string $title, string $class): HtmlElement
    {
        if (! $uriObject) {
            return HtmlElement::span()->props("class {$class}");
        }

        return HtmlElement::a(
            HtmlElement::span($title)
        )->props(
            "class {$class}",
            "title {$title}",
            'href ' . $this->uriPrefix() . $uriObject->uri()
        );
    }

    private function uriPrefix(): string
    {
        return $this->uriPrefix;
    }
}
