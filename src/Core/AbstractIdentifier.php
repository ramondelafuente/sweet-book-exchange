<?php

namespace SWP\Exchange\Core;

abstract class AbstractIdentifier implements Identifier
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $string
     * @return Identifier
     */
    public static function fromString($string)
    {
        return new static($string);
    }

    /**
     * @return Identifier
     */
    public static function generate()
    {
        return new static(IdentifierGenerator::generate());
    }

    /**
     * @param Identifier $other
     * @return bool
     */
    public function equals(Identifier $other)
    {
        return ($other instanceof static && $other->id === $this->id);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    private function __construct($id)
    {
        $this->id = (string)$id;
    }
}
