<?php

namespace SWP\Exchange\Event;

use SWP\Exchange\Book\BookId;
use SWP\Exchange\Book\ISBN;
use SWP\Exchange\Person\PersonId;

final class BookWasAdded
{
    /** @var PersonId */
    private $ownerId;

    /** @var  ISBN */
    private $isbn;

    /** @var BookId */
    private $bookId;

    public function __construct(PersonId $ownerId, ISBN $isbn, BookId $bookId)
    {
        $this->ownerId  = $ownerId;
        $this->isbn     = $isbn;
        $this->bookId   = $bookId;
    }

    /**
     * @return BookId
     */
    public function getBookId()
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

    /**
     * @return PersonId
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

}
