<?php

namespace Spec\SWP\Exchange\Book;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SWP\Exchange\Book\Book;
use SWP\Exchange\Book\BookId;
use SWP\Exchange\Book\ISBN;
use SWP\Exchange\Event\BookWasAdded;
use SWP\Exchange\Exception\DiscardedBookManipulation;
use SWP\Exchange\Person\PersonId;

class BookSpec extends ObjectBehavior
{
    function let() {
        $bookId   = BookId::fromString('2');
        $isbn     = ISBN::fromString('9789027439642');
        $personId = PersonId::fromString('1');

        $this->beConstructedThrough('add', [$bookId, $isbn, $personId]);
    }

    function it_is_added()
    {
        $eventTypeList = [
            'SWP\Exchange\Event\BookWasAdded',
        ];

        $this->shouldHaveType('SWP\Exchange\Book\Book');
        $this->aggregateId()->shouldBeLike(BookId::fromString('2'));

        $events = $this->recordedEvents();
        $events->shouldHaveCount(count($eventTypeList));
        $events->shouldContainEventTypes($eventTypeList);
    }

    function it_is_discarded()
    {
        $this->clearRecordedEvents();

        $eventTypeList = [
            'SWP\Exchange\Event\BookWasDiscarded',
        ];

        $this->discard();

        $events = $this->recordedEvents();
        $events->shouldHaveCount(count($eventTypeList));
        $events->shouldContainEventTypes($eventTypeList);
    }

    function it_is_borrowed()
    {
        $this->clearRecordedEvents();

        $eventTypeList = [
            'SWP\Exchange\Event\BookWasBorrowed',
        ];

        $borrowerId = PersonId::fromString('10');
        $this->borrow($borrowerId);

        $this->isItBorrowed()->shouldReturn(true);

        $events = $this->recordedEvents();
        $events->shouldHaveCount(count($eventTypeList));
        $events->shouldContainEventTypes($eventTypeList);
    }

    function it_is_given_back()
    {
        $this->clearRecordedEvents();

        $eventTypeList = [
            'SWP\Exchange\Event\BookWasBorrowed',
            'SWP\Exchange\Event\BookWasReturned',
        ];

        $borrowerId = PersonId::fromString('10');
        $this->borrow($borrowerId);
        $this->giveback();

        $this->isItBorrowed()->shouldReturn(false);

        $events = $this->recordedEvents();
        $events->shouldHaveCount(count($eventTypeList));
        $events->shouldContainEventTypes($eventTypeList);
    }


    function it_is_not_borrowed_if_the_keeper_is_not_the_owner()
    {
        $borrowerId = PersonId::fromString('3');
        $this->borrow($borrowerId);
        $this->clearRecordedEvents();

        $borrowerId = PersonId::fromString('4');
        $this->shouldThrow('SWP\Exchange\Exception\BorrowedBookManipulation')->during('borrow', array($borrowerId));
    }

    function it_is_not_returned_if_the_keeper_is_the_owner()
    {
        $this->clearRecordedEvents();

        $this->shouldThrow('SWP\Exchange\Exception\BookException')->during('giveBack');
    }

    function it_does_nothing_if_the_book_was_discarded()
    {
        $this->clearRecordedEvents();
        $this->discard();

        $eventTypeList = [
            'SWP\Exchange\Event\BookWasDiscarded',
        ];

        $borrowerId = PersonId::fromString('1');
        $this->shouldThrow('SWP\Exchange\Exception\DiscardedBookManipulation')->during('borrow', array($borrowerId));
        $this->shouldThrow('SWP\Exchange\Exception\DiscardedBookManipulation')->during('giveBack');
        $this->shouldThrow('SWP\Exchange\Exception\DiscardedBookManipulation')->during('discard');

        $events = $this->recordedEvents();
        $events->shouldHaveCount(count($eventTypeList));
        $events->shouldContainEventTypes($eventTypeList);
    }

    public function getMatchers()
    {
        return [
            'containEventTypes' => function ($subject, $types) {

                foreach ($subject as $domainEvent) {
                    $key = array_search(get_class($domainEvent), $types);
                    if ($key !== false) {
                        unset($types[$key]);
                    }
                }
                return (count($types) === 0);
            },
        ];
    }
}
