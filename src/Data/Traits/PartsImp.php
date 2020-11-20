<?php

namespace Eightfold\Events\Data\Traits;

use Eightfold\Shoop\Shoop;

trait PartsImp
{
    private $parts = [];

    public function parts(bool $asString = true): array
    {
        return Shoop::this($this->parts)->each(function($v) use ($asString) {
            if ($asString) {
                return strval($v);
            }
            return $v;
        })->unfold();
    }
}
