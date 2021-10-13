<?php

declare(strict_types=1);

namespace Eightfold\Events\Implementations;

use Eightfold\FileSystem\Item as FileSystemItem;

trait Item
{
    /**
     * @var FileSystemItem
     */
    private $item;

    abstract public function item(): FileSystemItem;
}
