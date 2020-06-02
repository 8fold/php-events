<?php

namespace Eightfold\Events\UI\Interfaces;

use Carbon\Carbon;

interface Numbers
{
    public function carbon(): Carbon;

    public function totalGridItems(): int;
}
