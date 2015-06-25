<?php

namespace SWP\Exchange\Event;

use SimpleES\EventSourcing\Identifier\Identifies;
use SWP\Exchange\Book\BookId;
use SWP\Exchange\Book\ISBN;
use SWP\Exchange\Core\Event;

class BookWasDiscarded implements Event
{
    /** @var BookId */
    private $bookId;

    /** @var  ISBN */
    private $isbn;

    /**
     * @param BookId $bookId
     */
    public function __construct(BookId $bookId, ISBN $isbn)
    {
        $this->bookId = $bookId;
        $this->isbn     = $isbn;
    }

    /**
     * @return BookId
     */
    public function getBookId()
    {
        return $this->bookId;
    }

    /**
     * @return Identifies
     */
    public function aggregateId()
    {
        return $this->bookId;
    }

    /**
     * @return ISBN
     */
    public function getIsbn()
    {
        return $this->isbn;
    }
}
