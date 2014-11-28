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
            'SWP.Exchange.Event.BookWasAdded',
        ];

        $this->shouldHaveType('SWP\Exchange\Book\Book');
        $this->getAggregateRootId()->shouldBeLike(BookId::fromString('2'));

        $events = $this->getUncommittedEvents();
        $events->getIterator()->shouldHaveCount(count($eventTypeList));
        $events->getIterator()->shouldContainEventTypes($eventTypeList);
    }

    function it_is_discarded()
    {
        $this->getUncommittedEvents();

        $eventTypeList = [
            'SWP.Exchange.Event.BookWasDiscarded',
        ];

        $this->discard();

        $events = $this->getUncommittedEvents();
        $events->getIterator()->shouldHaveCount(count($eventTypeList));
        $events->getIterator()->shouldContainEventTypes($eventTypeList);
    }

    function it_is_borrowed()
    {
        $this->getUncommittedEvents();

        $eventTypeList = [
            'SWP.Exchange.Event.BookWasBorrowed',
        ];

        $borrowerId = PersonId::fromString('10');
        $this->borrow($borrowerId);

        $this->isItBorrowed()->shouldReturn(true);

        $events = $this->getUncommittedEvents();
        $events->getIterator()->shouldHaveCount(count($eventTypeList));
        $events->getIterator()->shouldContainEventTypes($eventTypeList);
    }

    function it_is_given_back()
    {
        $this->getUncommittedEvents();

        $eventTypeList = [
            'SWP.Exchange.Event.BookWasBorrowed',
            'SWP.Exchange.Event.BookWasReturned',
        ];

        $borrowerId = PersonId::fromString('10');
        $this->borrow($borrowerId);
        $this->giveback();

        $events = $this->getUncommittedEvents();
        $events->getIterator()->shouldHaveCount(count($eventTypeList));
        $events->getIterator()->shouldContainEventTypes($eventTypeList);
    }


    function it_is_not_borrowed_if_the_keeper_is_not_the_owner()
    {
        $borrowerId = PersonId::fromString('3');
        $this->borrow($borrowerId);
        $this->getUncommittedEvents();

        $eventTypeList = [];

        $borrowerId = PersonId::fromString('4');
        $this->borrow($borrowerId);

        $events = $this->getUncommittedEvents();
        $events->getIterator()->shouldHaveCount(count($eventTypeList));
    }

    function it_is_not_returned_if_the_keeper_is_the_owner()
    {
        $this->getUncommittedEvents();

        $eventTypeList = [];

        $this->giveBack();

        $events = $this->getUncommittedEvents();
        $events->getIterator()->shouldHaveCount(count($eventTypeList));
    }

    function it_does_nothing_if_the_book_was_discarded()
    {
        $this->getUncommittedEvents();
        $this->discard();

        $eventTypeList = [
            'SWP.Exchange.Event.BookWasDiscarded',
        ];

        $borrowerId = PersonId::fromString('1');
        $this->shouldThrow('\SWP\Exchange\Exception\DiscardedBookManipulation')->during('borrow', array($borrowerId));
        $this->shouldThrow('\SWP\Exchange\Exception\DiscardedBookManipulation')->during('giveBack');
        $this->shouldThrow('\SWP\Exchange\Exception\DiscardedBookManipulation')->during('discard');

        $events = $this->getUncommittedEvents();
        $events->getIterator()->shouldHaveCount(count($eventTypeList));
    }

    public function getMatchers()
    {
        return [
            'containEventTypes' => function ($subject, $types) {
                foreach ($subject as $domainMessage) {
                    $key = array_search($domainMessage->getType(), $types);
                    if ($key !== false) {
                        unset($types[$key]);
                    }
                }
                return (count($types) === 0);
            },
        ];
    }
}
