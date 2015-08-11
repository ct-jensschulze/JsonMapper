<?php

namespace spec\Commercetools\Commons\Json;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NodeCollectionSpec extends ObjectBehavior
{
    function getExampleObject()
    {
        return $this->getJsonObject(json_encode([['name' => ['en' => 'John Doe']], ['name' => ['en' => 'Jane Doe']]]));
    }

    function getJsonObject($string)
    {
        return json_decode($string);
    }

    function let()
    {
        $this->beConstructedThrough('ofData', [$this->getExampleObject()]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Commercetools\Commons\Json\NodeCollection');
        $this->shouldHaveType('Commercetools\Commons\Json\Node');
        $this->shouldImplement('\Iterator');
        $this->shouldImplement('\Countable');
        $this->shouldImplement('\ArrayAccess');
    }

    function it_returns_null_for_nonexistant_value()
    {
        $this->beConstructedThrough('of');
        $this->get(0)->shouldBeNull();
    }

    function it_should_iterate_empty()
    {
        $this->beConstructedThrough('ofData', [$this->getJsonObject('{"values": []}')]);
        $this->getValues()->shouldHaveCount(0);
    }

    function it_has_parent()
    {
        $this->get(0)->getName();
    }

    function it_maps_array_recursive()
    {
        $this->get(0)->shouldHaveType('Commercetools\Commons\Json\Node');
    }

    function it_returns_child_value()
    {
        $this->get(0)->get('name')->get('en')->shouldReturn('John Doe');
    }

    function it_has_magic_getters()
    {
        $this->get0()->getName()->getEn()->shouldReturn('John Doe');
    }

    function it_gets_first_element()
    {
        $this->getFirst()->getName()->getEn()->shouldReturn('John Doe');
    }

    function it_returns_offset_false_if_empty()
    {
        $this->offsetExists(3)->shouldBe(false);
    }

    function it_returns_true_for_existing_offset()
    {
        $this->offsetExists(0)->shouldBe(true);
    }

    function it_returns_value_like_array()
    {
        $this[0]->getName()->getEn()->shouldReturn('John Doe');
    }

    function it_unsets_data()
    {
        unset($this[0]);
        $this->shouldHaveCount(1);
        $this->get(1)->getName()->getEn()->shouldReturn('Jane Doe');
    }
}
