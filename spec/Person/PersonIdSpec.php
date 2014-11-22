<?php

namespace Spec\SWP\Exchange\Person;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SWP\Exchange\Person\PersonId;

class PersonIdSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedThrough('fromString', ['person-1']);
    }

    function it_is_created_from_a_string()
    {
        $this->shouldHaveType('SWP\Exchange\Person\PersonId');
        $this->shouldImplement('SWP\Exchange\Core\Identifier');
    }

    function it_exposes_the_id_it_was_created_with()
    {
        $this->__toString()->shouldReturn('person-1');
    }

    function it_is_generated()
    {
        $this->beConstructedThrough('generate', []);

        $this->shouldHaveType('SWP\Exchange\Person\PersonId');
        $this->shouldImplement('SWP\Exchange\Core\Identifier');
    }

    function it_exposes_the_generated_id()
    {
        $this->beConstructedThrough('generate', []);

        $this->__toString()->shouldMatch('/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/');
    }

    function it_equals_another_personid()
    {
        $other = PersonId::fromString('person-1');

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
