<?php

declare(strict_types=1);

namespace Eightfold\Events\Implementations;

use SplFileInfo;

trait Item
{
    private SplFileInfo|false $item = false;

    abstract public function item(): SplFileInfo|false;
}
