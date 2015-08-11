<?php

namespace spec\Commercetools\Commons;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JsonMapperSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Commercetools\Commons\JsonMapper');
    }

    function it_maps_json_string()
    {
        $this->map('{"key":"value"}')->shouldHaveType('Commercetools\Commons\JsonObject');
        $this->map('[{"key":"value1"}, {"key":"value2"}]')
            ->shouldHaveType('Commercetools\Commons\JsonObject');
    }
}
