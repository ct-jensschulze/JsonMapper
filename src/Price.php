<?php
/**
 * @author @ct-jensschulze <jens.schulze@commercetools.de>
 */

namespace Commercetools\Commons;


class Price extends JsonObject
{
    public function fieldTypeDefinition()
    {
        return [
            'value' => '\Commercetools\Commons\Money'
        ];
    }
}