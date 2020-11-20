<?php

namespace Eightfold\Events\Data\Traits;

use Eightfold\Shoop\Shoop;

trait RootImp
{
    protected $root;

    public function root(): string
    {
        return $this->root;
    }
}
