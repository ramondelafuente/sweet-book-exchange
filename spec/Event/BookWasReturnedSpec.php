<?php

namespace Spec\SWP\Exchange\Event;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SWP\Exchange\Book\BookId;

class BookWasReturnedSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(BookId::fromString('2'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SWP\Exchange\Event\BookWasReturned');
    }

    function it_has_the_right_bookid()
    {
        $bookId = $this->getBookId();

        $bookId->shouldReturnAnInstanceOf('SWP\Exchange\Book\BookId');
        $bookId->__toString()->shouldBeEqualTo('2');
    }

}
