<?php

declare(strict_types=1);

namespace Eightfold\Events\Implementations;

use Eightfold\Events\Events as EventsCollection;

trait Events
{
    /**
     * @var EventsCollection
     */
    private $events;

    public function events(): EventsCollection
    {
        if ($this->events === null) {
            $this->events = EventsCollection::fold($this->root());
        }
        return $this->events;
    }
}
