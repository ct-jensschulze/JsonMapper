<?php

namespace spec\Commercetools\Commons\Json;

use Commercetools\Commons\Json\Node;
use Commercetools\Commons\JsonMapper;
use Commercetools\Commons\Money;
use Commercetools\Commons\Price;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NodeSpec extends ObjectBehavior
{
    function getExampleObject()
    {
        return $this->getJsonObject(json_encode(['name' => ['en' => 'John Doe'], 'values' => []]));
    }

    function getExampleCollection()
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
        $this->shouldHaveType('Commercetools\Commons\Json\Node');
    }

    function it_returns_null_for_nonexistant_value()
    {
        $this->beConstructedThrough('of');
        $this->get('name')->shouldBeNull();
    }

    function it_maps_array_recursive()
    {
        $this->get('name')->shouldHaveType('Commercetools\Commons\Json\Node');
    }

    function it_returns_child_value()
    {
        $this->get('name')->get('en')->shouldReturn('John Doe');
    }

    function it_has_magic_getters()
    {
        $this->getName()->getEn()->shouldReturn('John Doe');
    }
}
