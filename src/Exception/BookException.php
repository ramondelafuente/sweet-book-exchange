<?php

namespace SWP\Exchange\Exception;

use SWP\Exchange\Book\Book;

class BookException extends \LogicException
{

    public static function make(Book $book, $manipulation)
    {
        return new static(sprintf(static::$template, $manipulation, $book->getAggregateRootId()));
    }
}
