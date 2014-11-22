<?php

namespace Spec\SWP\Exchange\Event;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SWP\Exchange\Book\BookId;
use SWP\Exchange\Person\PersonId;

class BookWasCheckedOutSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(PersonId::fromString('3'), BookId::fromString('2'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SWP\Exchange\Event\BookWasCheckedOut');
    }

    function it_has_the_right_borrowerid()
    {
        $ownerId = $this->getBorrowerId();

        $ownerId->shouldReturnAnInstanceOf('SWP\Exchange\Person\PersonId');
        $ownerId->__toString()->shouldBeEqualTo('3');
    }

    function it_has_the_right_bookid()
    {
        $bookId = $this->getBookId();

        $bookId->shouldReturnAnInstanceOf('SWP\Exchange\Book\BookId');
        $bookId->__toString()->shouldBeEqualTo('2');
    }
}
