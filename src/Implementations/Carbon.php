<?php

declare(strict_types=1);

namespace Eightfold\Events\Implementations;

use Carbon\Carbon as CarbonInstance;

trait Carbon
{
    /**
     * @var CarbonInstance
     */
    private $carbon;

    abstract public function carbon(): CarbonInstance;
}
