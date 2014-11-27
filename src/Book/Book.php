<?php

namespace SWP\Exchange\Book;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
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
        if (!$this->owner->equals($this->keeper) || $this->discarded) {
            return;
        }
        $this->apply(new BookWasDiscarded($this->aggregateRootId));
    }

    public function borrow(PersonId $borrower)
    {
        if (!$this->owner->equals($this->keeper) || $this->discarded) {
            return;
        }
        $this->apply(new BookWasBorrowed($borrower, $this->aggregateRootId));
    }

    public function giveBack()
    {
        if ($this->owner->equals($this->keeper) || $this->discarded) {
            return;
        }
        $this->apply(new BookWasReturned($this->aggregateRootId));
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
    public function applyBookWasReturned(BookWasReturned $event)
    {
        $this->keeper = $this->owner;
    }

}
