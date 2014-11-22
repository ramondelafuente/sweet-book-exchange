<?php

namespace Spec\SWP\Exchange\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SWP\Exchange\Person\PersonId;

class AddBookSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('0123456543210', 'person-1');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SWP\Exchange\Command\AddBook');
        $this->shouldImplement('SWP\Exchange\Core\Command');
    }

    function it_exposes_the_isbn_number()
    {
        $this->getISBN()->shouldReturn('0123456543210');
    }

    function it_exposes_the_owner_id()
    {
        $this->getOwnerId()->shouldReturn('person-1');
    }
}
