<?php

namespace SWP\Exchange\Core;

use Rhumsaa\Uuid\Uuid;

class IdentifierGenerator
{
    /**
     * @return string
     */
    public static function generate()
    {
        return Uuid::uuid4()->toString();
    }
}
