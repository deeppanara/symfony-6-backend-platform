<?php
declare(strict_types = 1);
/**
 * /tests/Unit/Validator/Constraints/LanguageTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Unit\Validator\Constraints;

use PHPUnit\Framework\Attributes\TestDox;
use Platform\Validator\Constraints\Language;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class LanguageTest
 *
 * @package App\Tests\Unit\Validator\Constraints
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class LanguageTest extends KernelTestCase
{
    #[TestDox('Test that `getTargets` method returns expected')]
    public function testThatGetTargetsReturnsExpected(): void
    {
        self::assertSame('property', (new Language())->getTargets());
    }
}
