<?php

namespace Eightfold\Events\Data;

use Eightfold\Foldable\Fold;
use Eightfold\Foldable\Foldable;

use Eightfold\Events\Data\Traits\RootImp;

abstract class DataAbstract extends Fold
{
    use RootImp;

    protected $content = [];

    static public function fold(...$args): Foldable
    {
        return new static(...$args);
    }

    public function unfold()
    {
        return $this->path();
    }

    abstract public function path(): string;

    abstract public function content();

    abstract public function count(): int;

    abstract public function couldHaveEvents(): bool;

    abstract public function hasEvents(): bool;
}
