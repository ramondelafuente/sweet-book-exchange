<?php

namespace SWP\Exchange\Event;

use SWP\Exchange\Book\BookId;

class BookWasDiscarded
{
    /** @var BookId */
    private $bookId;

    /**
     * @param BookId $bookId
     */
    public function __construct(BookId $bookId)
    {
        $this->bookId = $bookId;
    }

    /**
     * @return BookId
     */
    public function getBookId()
    {
        return $this->bookId;
    }

}
