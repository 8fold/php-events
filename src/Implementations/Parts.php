<?php

declare(strict_types=1);

namespace Eightfold\Events\Implementations;

trait Parts
{
    /**
     * @var array<int>
     */
    private array $parts = [];

    /**
     * @return array<int>
     */
    private function parts(): array
    {
        return $this->parts;
    }
}
