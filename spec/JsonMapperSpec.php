<?php

namespace spec\Commercetools\Commons;

use Commercetools\Commons\Json\Node;
use Commercetools\Commons\Test\Money;
use Commercetools\Commons\Test\Price;
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

    function it_test_currency()
    {
        $mapper = $this;
        $node = Node::ofData(json_decode('[{"value": {"centAmount": 100, "currency": "EUR"}}]'));
        $d = $mapper->map($node, '\Commercetools\Commons\Test\PriceCollection');
        $d->get(0)->getValue()->getCentAmount()->shouldBe(100);

        $d->set(null, Price::of()->setValue(Money::of()->setCentAmount(1000)->setCurrencyCode('USD')));
        $d->set(null, Price::of()->setValue(Money::of()->setCentAmount(5000)->setCurrencyCode('EUR')));

        $d->get(1)->getValue()->getCentAmount()->shouldBe(1000);
        $d->get(2)->getValue()->getCentAmount()->shouldBe(5000);
    }

    function it_decodes_datetime()
    {
        $mapper = $this;
        $node = Node::ofData(json_decode('{"value": "2015-01-15"}'));
        $d = $mapper->map($node, '\Commercetools\Commons\Test\DateTimeObject');

        $d->getValue()->shouldHaveType('\DateTime');
    }
}
