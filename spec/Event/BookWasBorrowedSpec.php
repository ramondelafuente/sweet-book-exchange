<?php

namespace Spec\SWP\Exchange\Event;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SWP\Exchange\Book\BookId;
use SWP\Exchange\Book\ISBN;
use SWP\Exchange\Person\PersonId;

class BookWasBorrowedSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(PersonId::fromString('3'), BookId::fromString('2'), ISBN::fromString('978-3-16-148410-1'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SWP\Exchange\Event\BookWasBorrowed');
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

    function it_has_the_right_isbn()
    {
        $ISBN = $this->getIsbn();

        $ISBN->shouldReturnAnInstanceOf('SWP\Exchange\Book\ISBN');
        $ISBN->__toString()->shouldBeEqualTo('978-3-16-148410-1');
    }

}
