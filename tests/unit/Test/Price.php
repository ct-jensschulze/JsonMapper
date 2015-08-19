<?php
/**
 * @author @ct-jensschulze <jens.schulze@commercetools.de>
 */

namespace Commercetools\Commons\Test;


use Commercetools\Commons\JsonObject;

class Price extends JsonObject
{
    public function fieldDefinitions()
    {
        return [
            'value' => [static::TYPE => '\Commercetools\Commons\Test\Money']
        ];
    }
}
