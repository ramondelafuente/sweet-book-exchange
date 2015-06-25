<?php

namespace SWP\Exchange\Core;

use SimpleBus\Message\Type\Event as SimplebusEvent;
use SimpleES\EventSourcing\Event\DomainEvent;

interface Event Extends DomainEvent, SimplebusEvent
{

}
