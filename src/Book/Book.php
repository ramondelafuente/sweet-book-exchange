<?php

namespace SWP\Exchange\Book;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use SWP\Exchange\Exception\BookException;
use SWP\Exchange\Exception\DiscardedBookManipulation;
use SWP\Exchange\Exception\BorrowedBookManipulation;
use SWP\Exchange\Event\BookWasAdded;
use SWP\Exchange\Event\BookWasBorrowed;
use SWP\Exchange\Event\BookWasDiscarded;
use SWP\Exchange\Event\BookWasReturned;
use SWP\Exchange\Person\PersonId;

class Book extends EventSourcedAggregateRoot
{

    /** @var BookId */
    private $aggregateRootId;

    /** @var PersonId */
    private $owner;

    /** @var PersonId */
    private $keeper;

    /** @var bool */
    private $discarded = false;

    private function __construct()
    {
    }

    /**
     * @param BookId $bookId
     * @param ISBN $isbn
     * @param PersonId $ownerId
     * @return Book
     */
    public static function add(BookId $bookId, ISBN $isbn, PersonId $ownerId)
    {
        $book = new self();
        $book->apply(new BookWasAdded($ownerId, $isbn, $bookId));

        return $book;
    }

    public function discard()
    {
        $this->guardItIsNotDiscarded(__FUNCTION__);
        $this->guardItIsNotBorrowed(__FUNCTION__);

        $this->apply(new BookWasDiscarded($this->aggregateRootId));
    }

    /**
     * @param PersonId $borrower
     */
    public function borrow(PersonId $borrower)
    {
        $this->guardItIsNotDiscarded(__FUNCTION__);
        $this->guardItIsNotBeingBorrowedByTheOwner($borrower);

        // The borrower already has the book.
        if ($this->keeper->equals($borrower)) {
            return;
        }

        $this->guardItIsNotBorrowed(__FUNCTION__);

        $this->apply(new BookWasBorrowed($borrower, $this->aggregateRootId));
    }

    public function giveBack()
    {
        $this->guardItIsNotDiscarded(__FUNCTION__);

        // The owner already has the book.
        if (!$this->isItBorrowed()) {
            return;
        }

        $this->apply(new BookWasReturned($this->aggregateRootId));
    }

    /**
     * @return bool
     */
    public function isItBorrowed()
    {
        return !$this->owner->equals($this->keeper);
    }

    /**
     * @return BookId
     */
    public function getAggregateRootId()
    {
        return $this->aggregateRootId;
    }

    /**
     * @param BookWasAdded $event
     */
    public function applyBookWasAdded(BookWasAdded $event)
    {
        $this->aggregateRootId = $event->getBookId();
        $this->owner           = $event->getOwnerId();
        $this->keeper          = $event->getOwnerId();
    }

    /**
     * @param BookWasDiscarded $event
     */
    public function applyBookWasDiscarded(BookWasDiscarded $event)
    {
        $this->discarded = true;
    }

    /**
     * @param BookWasBorrowed $event
     */
    public function applyBookWasBorrowed(BookWasBorrowed $event)
    {
        $this->keeper = $event->getBorrowerId();
    }

    /**
     * @param BookWasReturned $event
     */
    public function applyBookWasGivenBack(BookWasReturned $event)
    {
        $this->keeper = $this->owner;
    }

    /**
     * @throws DiscardedBookManipulation
     */
    protected function guardItIsNotDiscarded($manipulation)
    {
        if ($this->discarded) {
            throw DiscardedBookManipulation::make($this, $manipulation);
        }
    }

    /**
     * @throws BorrowedBookManipulation
     */
    protected function guardItIsNotBorrowed($manipulation)
    {
        if ($this->isItBorrowed()) {
            throw BorrowedBookManipulation::make($this, $manipulation);
        }
    }

    /**
     * @param PersonId $borrower
     *
     * @throws BookException
     */
    protected function guardItIsNotBeingBorrowedByTheOwner(PersonId $borrower)
    {
        if ($this->owner->equals($borrower)) {
            throw BookException::make($this, 'borrow by the owner');
        }
    }

}
