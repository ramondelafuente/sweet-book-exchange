<?php

namespace Spec\SWP\Exchange\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BorrowBookSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('book-1', 'person-2');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SWP\Exchange\Command\BorrowBook');
        $this->shouldImplement('SWP\Exchange\Core\Command');
    }

    function it_exposes_the_book_id()
    {
        $this->getBookId()->shouldReturn('book-1');
    }

    function it_exposes_the_borrower_id()
    {
        $this->getBorrowerId()->shouldReturn('person-2');
    }
}
