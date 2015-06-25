<?php

namespace SWP\Exchange\Core;

use Rhumsaa\Uuid\Uuid;
use SimpleES\EventSourcing\Identifier\GeneratesIdentifiers;

class IdentifierGenerator implements GeneratesIdentifiers
{
    /**
     * @return string
     */
    public static function generate()
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * @return string
     */
    public function generateIdentifier()
    {
        return static::generate();
    }
}
