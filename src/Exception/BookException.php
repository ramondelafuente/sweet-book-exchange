<?php

namespace SWP\Exchange\Exception;

use SWP\Exchange\Book\Book;

class BookException extends \LogicException
{
    static protected $template = 'Invalid action %s on book with ID %s';

    public static function make(Book $book, $manipulation = '')
    {
        return new static(sprintf(static::$template, $manipulation, $book->getAggregateRootId()));
    }
}
