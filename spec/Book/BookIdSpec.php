<?php

namespace Spec\SWP\Exchange\Book;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SWP\Exchange\Book\BookId;

class BookIdSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedThrough('fromString', ['book-1']);
    }

    function it_is_created_from_a_string()
    {
        $this->shouldHaveType('SWP\Exchange\Book\BookId');
        $this->shouldImplement('SWP\Exchange\Core\Identifier');
    }

    function it_exposes_the_id_it_was_created_with()
    {
        $this->__toString()->shouldReturn('book-1');
    }

    function it_is_generated()
    {
        $this->beConstructedThrough('generate', []);

        $this->shouldHaveType('SWP\Exchange\Book\BookId');
        $this->shouldImplement('SWP\Exchange\Core\Identifier');
    }

    function it_exposes_the_generated_id()
    {
        $this->beConstructedThrough('generate', []);

        $this->__toString()->shouldMatch('/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/');
    }

    function it_equals_another_bookid()
    {
        $other = BookId::fromString('book-1');

        $this->equals($other)->shouldBe(true);
    }

    public function getMatchers()
    {
        return [
            'match' => function ($subject, $pattern) {
                return (bool)preg_match($pattern, $subject);
            }
        ];
    }
}
