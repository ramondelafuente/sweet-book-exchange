<?php

namespace SWP\Exchange\Event;

use SimpleES\EventSourcing\Identifier\Identifies;
use SWP\Exchange\Book\BookId;
use SWP\Exchange\Book\ISBN;
use SWP\Exchange\Core\Event;
use SWP\Exchange\Person\PersonId;

class BookWasBorrowed implements Event
{
    /** @var PersonId  */
    private $borrowerId;

    /** @var  ISBN */
    private $isbn;

    /** @var BookId  */
    private $bookId;

    /**
     * @param PersonId $borrowerId
     * @param BookId $bookId
     */
    public function __construct(PersonId $borrowerId, BookId $bookId, ISBN $isbn)
    {
        $this->borrowerId = $borrowerId;
        $this->isbn     = $isbn;
        $this->bookId = $bookId;
    }

    /**
     * @return PersonId
     */
    public function getBorrowerId()
    {
        return $this->borrowerId;
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
