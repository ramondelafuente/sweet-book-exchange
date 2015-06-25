<?php

namespace Spec\SWP\Exchange\Event;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SWP\Exchange\Book\BookId;
use SWP\Exchange\Book\ISBN;

class BookWasDiscardedSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(BookId::fromString('2'), ISBN::fromString('978-3-16-148410-1'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SWP\Exchange\Event\BookWasDiscarded');
    }

    function it_has_the_right_bookid()
    {
        $bookId = $this->getBookId();

        $bookId->shouldReturnAnInstanceOf('SWP\Exchange\Book\BookId');
        $bookId->__toString()->shouldBeEqualTo('2');
    }

    function it_has_the_right_isbn()
    {
        $ISBN = $this->getIsbn();

        $ISBN->shouldReturnAnInstanceOf('SWP\Exchange\Book\ISBN');
        $ISBN->__toString()->shouldBeEqualTo('978-3-16-148410-1');
    }


}
