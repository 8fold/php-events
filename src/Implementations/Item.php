<?php

declare(strict_types=1);

namespace Eightfold\Events\Implementations;

use SplFileInfo;

trait Item
{
    /**
     * @var FileSystemItem
     */
    private $item;

    abstract public function item(): SplFileInfo;
}
