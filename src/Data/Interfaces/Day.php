<?php

namespace Eightfold\Events\Data\Interfaces;

use Eightfold\Events\Data\Interfaces\Month;

interface Day extends Month
{
    public function date(bool $asString = true);
}
