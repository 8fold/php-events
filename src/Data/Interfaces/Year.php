<?php

namespace Eightfold\Events\Data\Interfaces;

use Eightfold\Events\Data\Interfaces\Path;

interface Year extends Path
{
    public function year();

    public function monthString(int $month = 0);
}
