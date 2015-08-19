<?php
/**
 * @author @ct-jensschulze <jens.schulze@commercetools.de>
 */

namespace Commercetools\Commons\Test;


use Commercetools\Commons\JsonObject;

class Money extends JsonObject
{
    public function fieldDefinitions()
    {
        return [
            'centAmount' => [static::TYPE => 'int'],
            'currencyCode' => [static::TYPE => 'string']
        ];
    }
}
