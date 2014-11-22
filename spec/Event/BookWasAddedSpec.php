<?php

namespace Spec\SWP\Exchange\Event;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SWP\Exchange\Book\BookId;
use SWP\Exchange\Book\ISBN;
use SWP\Exchange\Person\PersonId;

class BookWasAddedSpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith(PersonId::fromString('1'), ISBN::fromString('9789027439642'), BookId::fromString('2'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SWP\Exchange\Event\BookWasAdded');
    }

    function it_has_the_right_ownerid()
    {
        $ownerId = $this->getOwnerId();

        $ownerId->shouldReturnAnInstanceOf('SWP\Exchange\Person\PersonId');
        $ownerId->__toString()->shouldBeEqualTo('1');
    }

    function it_has_the_right_isbn()
    {
        $isbn = $this->getIsbn();

        $isbn->shouldReturnAnInstanceOf('SWP\Exchange\Book\ISBN');
        $isbn->__toString()->shouldBeEqualTo('9789027439642');
    }

    function it_has_the_right_bookid()
    {
        $bookId = $this->getBookId();

        $bookId->shouldReturnAnInstanceOf('SWP\Exchange\Book\BookId');
        $bookId->__toString()->shouldBeEqualTo('2');
    }
}
