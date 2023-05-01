<?php
declare(strict_types = 1);
/**
 * /tests/Integration/DTO/UserGroup/UserGroupUpdateTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\DTO\UserGroup;

use App\Tests\Integration\TestCase\DtoTestCase;
use Platform\DTO\UserGroup\UserGroupUpdate;

/**
 * Class UserGroupUpdateTest
 *
 * @package App\Tests\Integration\DTO\UserGroup
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class UserGroupUpdateTest extends DtoTestCase
{
    /**
     * @psalm-var class-string
     * @phpstan-var class-string<UserGroupUpdate>
     */
    protected static string $dtoClass = UserGroupUpdate::class;
}
