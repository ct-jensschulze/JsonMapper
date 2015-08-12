<?php
/**
 * @author @ct-jensschulze <jens.schulze@commercetools.de>
 */

namespace Commercetools\Commons;


class Money extends JsonObject
{
    public function fieldTypeDefinition()
    {
        return [
            'centAmount' => [static::TYPE => 'int'],
            'currencyCode' => [static::TYPE => 'string']
        ];
    }
}
