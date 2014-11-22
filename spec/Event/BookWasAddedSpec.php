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

    function it_has_the_right_data()
    {
        $this->getOwnerId()->shouldReturnAnInstanceOf('SWP\Exchange\Person\PersonId');
        $this->getIsbn()->shouldReturnAnInstanceOf('SWP\Exchange\Book\ISBN');
        $this->getBookId()->shouldReturnAnInstanceOf('SWP\Exchange\Book\BookId');
    }
}
