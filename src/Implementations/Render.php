<?php

declare(strict_types=1);

namespace Eightfold\Events\Implementations;

use Eightfold\HTMLBuilder\Element as HtmlElement;

use Eightfold\Events\Data\Year;
use Eightfold\Events\Data\Month;

trait Render
{
    private string $uriPrefix = '/events';

    public function navLink(
        Year|Month|false $uriObject,
        string $title,
        string $class
    ): HtmlElement {
        if (is_object($uriObject)) {
            $uri = $this->uriPrefix() . $uriObject->uri();
            if (strlen($this->uriPrefix()) === 0) {
                $uri = $uriObject->uri();
            }

            return HtmlElement::a(
                HtmlElement::span($title)
            )->props(
                "class {$class}",
                "title {$title}",
                'href ' . $uri
            );
        }
        return HtmlElement::span()->props("class {$class}");
    }

    private function uriPrefix(): string
    {
        return $this->uriPrefix;
    }
}
