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

    function it_is_initializable()
    {
        $this->shouldHaveType('SWP\Exchange\Book\BookId');
    }

    function it_exposes_the_id()
    {
        $this->__toString()->shouldReturn('book-1');
    }

    function it_generates_an_id()
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
