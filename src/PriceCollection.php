<?php
/**
 * @author @ct-jensschulze <jens.schulze@commercetools.de>
 */

namespace Commercetools\Commons;

class PriceCollection extends Collection
{
    protected function fieldType($field)
    {
        return '\Commercetools\Commons\Price';
    }
}
