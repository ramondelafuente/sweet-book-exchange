<?php

namespace SWP\Exchange\Core;

interface Identifier
{
    /**
     * @param string $string
     * @return Identifier
     */
    public static function fromString($string);

    /**
     * @return Identifier
     */
    public static function generate();

    /**
     * @param Identifier $other
     * @return bool
     */
    public function equals(Identifier $other);

    /**
     * @return string
     */
    public function __toString();
}
