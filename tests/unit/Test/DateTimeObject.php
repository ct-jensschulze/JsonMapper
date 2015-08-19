<?php
/**
 * @author @ct-jensschulze <jens.schulze@commercetools.de>
 */

namespace Commercetools\Commons\Test;


use Commercetools\Commons\JsonObject;

class DateTimeObject extends JsonObject
{
    public function fieldDefinitions()
    {
        return [
            'value' => [static::TYPE => '\DateTime']
        ];
    }
}
