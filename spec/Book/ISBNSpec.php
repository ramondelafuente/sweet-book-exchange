<?php

namespace Spec\SWP\Exchange\Book;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ISBNSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedThrough('fromString', ['0123456543210']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('SWP\Exchange\Book\ISBN');
    }

    function it_exposes_the_isbn_number()
    {
        $this->__toString()->shouldReturn('0123456543210');
    }

//    function it_guards_against_invalid_isbn_numbers()
//    {
//    }
}
