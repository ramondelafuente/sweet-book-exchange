<?php

namespace SWP\Exchange\Core;

use JMS\Serializer\Annotation as JMS;

use SimpleES\EventSourcing\Identifier\Identifies;

abstract class AbstractIdentifier implements Identifier
{
    /**
     * @JMS\Type("string")
     * @var string
     */
    private $id;

    /**
     * @return Identifier
     */
    public static function generate()
    {
        return new static(IdentifierGenerator::generate());
    }

    /**
     * @param string $string
     * @return static
     */
    public static function fromString($string)
    {
        return new static($string);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id;
    }

    /**
     * @param Identifies $other
     * @return bool
     */
    public function equals(Identifies $other)
    {
        return ($other instanceof static && $other->id === $this->id);
    }

    /**
     * @param string $id
     */
    private function __construct($id)
    {
        $this->id = (string) $id;
    }

}
