<?php

namespace Spec\SWP\Exchange\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReturnBookSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('book-1');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SWP\Exchange\Command\ReturnBook');
        $this->shouldImplement('SWP\Exchange\Core\Command');
    }

    function it_exposes_the_book_id()
    {
        $this->getBookId()->shouldReturn('book-1');
    }
}
