<?php

namespace Eightfold\Events\Data\Traits;

use Eightfold\Shoop\{Shoop, ESString};

trait PathImp
{
    private $path = "";

    private $events; // ESArray

    public function path(): ESString
    {
        return Shoop::string($this->path);
    }

    public function couldHaveEvents(): bool
    {
        return $this->dataPaths()->count()->isNotUnfolded(0);
    }
}
