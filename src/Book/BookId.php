<?php

namespace SWP\Exchange\Book;

use Rhumsaa\Uuid\Uuid;

class BookId
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $string
     * @return BookId
     */
    public static function fromString($string)
    {
        return new BookId($string);
    }

    /**
     * @return BookId
     */
    public static function generate()
    {
        return new BookId(
            Uuid::uuid4()->toString()
        );
    }

    /**
     * @param object $other
     * @return bool
     */
    public function equals($other)
    {
        return ($other instanceof self && $other->id === $this->id);
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
