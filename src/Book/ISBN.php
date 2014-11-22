<?php

namespace SWP\Exchange\Book;

class ISBN
{
    /**
     * @var string
     */
    private $isbn;

    /**
     * @param string $string
     * @return ISBN
     */
    public static function fromString($string)
    {
        return new ISBN($string);
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->isbn;
    }

    /**
     * @param string $isbn
     */
    private function __construct($isbn)
    {
        $this->guardValidISBN($isbn);

        $this->isbn = (string)$isbn;
    }

    private function guardValidISBN($isbn)
    {

    }
}
