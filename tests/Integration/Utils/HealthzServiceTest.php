<?php
/*
 * *************************************************************************
 * Copyright (C) 2023, Inc - All Rights Reserved
 * This file is part of the Dom bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author   Deep Panara <panaradeep@gmail.com>
 * @date     01/05/23, 12:25 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /tests/Integration/Utils/HealthzServiceTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Utils;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use Platform\Repository\HealthzRepository;
use Platform\Utils\HealthzService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Throwable;

/**
 * Class HealthzServiceTest
 *
 * @package App\Tests\Integration\Utils
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class HealthzServiceTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    #[TestDox('Test that `HealthzService::check` method calls expected repository methods')]
    public function testThatCheckMethodCallsExpectedRepositoryMethods(): void
    {
        $repository = $this->getRepository();

        $repository
            ->expects(self::once())
            ->method('cleanup');

        $repository
            ->expects(self::once())
            ->method('create');

        $repository
            ->expects(self::once())
            ->method('read');

        (new HealthzService($repository))
            ->check();
    }

    /**
     * @phpstan-return MockObject&HealthzRepository
     */
    private function getRepository(): MockObject
    {
        return $this->getMockBuilder(HealthzRepository::class)->disableOriginalConstructor()->getMock();
    }
}
