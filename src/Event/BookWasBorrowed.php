<?php

namespace SWP\Exchange\Event;

use SWP\Exchange\Book\BookId;
use SWP\Exchange\Person\PersonId;

class BookWasBorrowed
{
    /** @var PersonId  */
    private $borrowerId;

    /** @var BookId  */
    private $bookId;

    /**
     * @param PersonId $borrowerId
     * @param BookId $bookId
     */
    public function __construct(PersonId $borrowerId, BookId $bookId)
    {
        $this->borrowerId = $borrowerId;
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
}
