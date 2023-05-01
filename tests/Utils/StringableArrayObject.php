<?php
declare(strict_types = 1);
/**
 * /tests/Utils/StringableArrayObject.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Utils;

use ArrayObject;
use JsonException;
use Platform\Utils\JSON;
use Stringable;

/**
 * Class StringableArrayObject
 *
 * @psalm-suppress MissingTemplateParam
 *
 * @package App\Tests\Utils
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class StringableArrayObject extends ArrayObject implements Stringable
{
    /**
     * @throws JsonException
     */
    public function __toString(): string
    {
        $iterator = static fn (mixed $input): mixed => $input instanceof Stringable ? (string)$input : $input;

        return JSON::encode(array_map($iterator, $this->getArrayCopy()));
    }
}
