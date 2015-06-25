<?php

namespace SWP\Exchange\Book;

use SimpleES\EventSourcing\Aggregate\EventTrackingCapabilities;
use SimpleES\EventSourcing\Identifier\Identifies;
use SimpleES\EventSourcing\Aggregate\TracksEvents;
use SimpleES\EventSourcing\Event\AggregateHistory;
use SWP\Exchange\Exception\BookException;
use SWP\Exchange\Exception\DiscardedBookManipulation;
use SWP\Exchange\Exception\BorrowedBookManipulation;
use SWP\Exchange\Event\BookWasAdded;
use SWP\Exchange\Event\BookWasBorrowed;
use SWP\Exchange\Event\BookWasDiscarded;
use SWP\Exchange\Event\BookWasReturned;
use SWP\Exchange\Person\PersonId;

final class Book implements TracksEvents
{
    use EventTrackingCapabilities;

    /** @var BookId */
    private $aggregateRootId;

    /** @var PersonId */
    private $owner;

    /** @var ISBN */
    private $isbn;

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
        $book->recordThat(new BookWasAdded($ownerId, $isbn, $bookId));

        return $book;
    }

    public function discard()
    {
        $this->guardItIsNotDiscarded(__FUNCTION__);
        $this->guardItIsNotBorrowed(__FUNCTION__);

        $this->recordThat(new BookWasDiscarded($this->aggregateRootId, $this->isbn));
    }

    /**
     * @param PersonId $borrower
     */
    public function borrow(PersonId $borrower)
    {
        $this->guardItIsNotDiscarded(__FUNCTION__);
        $this->guardItIsNotBeingBorrowedByTheOwner($borrower);
        $this->guardItIsNotBorrowed(__FUNCTION__);

        $this->recordThat(new BookWasBorrowed($borrower, $this->aggregateRootId, $this->isbn));
    }

    public function giveBack()
    {
        $this->guardItIsNotDiscarded(__FUNCTION__);
        $this->guardItIsBorrowed(__FUNCTION__);

        $this->recordThat(new BookWasReturned($this->aggregateRootId, $this->isbn));
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
    public function whenBookWasAdded(BookWasAdded $event)
    {
        $this->aggregateRootId = $event->getBookId();
        $this->owner           = $event->getOwnerId();
        $this->keeper          = $event->getOwnerId();
        $this->isbn            = $event->getIsbn();
    }

    /**
     * @param BookWasDiscarded $event
     */
    public function whenBookWasDiscarded(BookWasDiscarded $event)
    {
        $this->discarded = true;
    }

    /**
     * @param BookWasBorrowed $event
     */
    public function whenBookWasBorrowed(BookWasBorrowed $event)
    {
        $this->keeper = $event->getBorrowerId();
    }

    /**
     * @param BookWasReturned $event
     */
    public function whenBookWasReturned(BookWasReturned $event)
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
     * @throws BookException
     */
    protected function guardItIsBorrowed($manipulation)
    {
        if (!$this->isItBorrowed()) {
            throw BookException::make($this, $manipulation . ' (not borrowed?)');
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

    /**
     * @param AggregateHistory $aggregateHistory
     * @return TracksEvents
     */
    public static function fromHistory(AggregateHistory $aggregateHistory)
    {
        $book = new Book();
        $book->replayHistory($aggregateHistory);

        return $book;
    }

    /**
     * @return Identifies
     */
    public function aggregateId()
    {
        return $this->aggregateRootId;
    }

}
