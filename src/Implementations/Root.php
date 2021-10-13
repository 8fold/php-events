<?php

declare(strict_types=1);

namespace Eightfold\Events\Implementations;

trait Root
{
    private $root;

    private function root(): string
    {
        return $this->root;
    }
}
