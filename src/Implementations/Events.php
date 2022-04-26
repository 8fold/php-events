<?php

declare(strict_types=1);

namespace Eightfold\Events\Implementations;

use Eightfold\Events\Events as EventsCollection;

trait Events
{
    /**
     * @var EventsCollection
     */
    private EventsCollection|null $events = null;

    public function events(): EventsCollection|null
    {
        if ($this->events === null) {
            $this->events = EventsCollection::fold($this->root());
        }
        return $this->events;
    }
}
