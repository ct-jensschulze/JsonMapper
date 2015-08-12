<?php
/**
 * @author @ct-jensschulze <jens.schulze@commercetools.de>
 */

namespace Commercetools\Commons;


use Commercetools\Commons\Json\Node;

interface JsonObjectInterface extends \JsonSerializable
{

    public static function ofNode(Node $data, ContextInterface $context = null);

    public function toArray();
}
