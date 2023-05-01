<?php
declare(strict_types = 1);
/**
 * /tests/Integration/DTO/ApiKey/ApiKeyCreateTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\DTO\ApiKey;

use App\Tests\Integration\TestCase\DtoTestCase;
use Platform\DTO\ApiKey\ApiKeyCreate;

/**
 * Class ApiKeyCreateTest
 *
 * @package App\Tests\Integration\DTO\ApiKey
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class ApiKeyCreateTest extends DtoTestCase
{
    /**
     * @psalm-var class-string
     * @phpstan-var class-string<ApiKeyCreate>
     */
    protected static string $dtoClass = ApiKeyCreate::class;
}
