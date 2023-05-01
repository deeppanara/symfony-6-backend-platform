<?php
declare(strict_types = 1);
/**
 * /tests/Integration/Entity/HealthzTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Entity;

use App\Tests\Integration\TestCase\EntityTestCase;
use Platform\Entity\Healthz;

/**
 * Class HealthzTest
 *
 * @package App\Tests\Integration\Entity
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 *
 * @method Healthz getEntity()
 */
class HealthzTest extends EntityTestCase
{
    /**
     * @var class-string
     */
    protected static string $entityName = Healthz::class;
}
