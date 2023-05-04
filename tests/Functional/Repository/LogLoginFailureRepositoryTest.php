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
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /tests/Functional/Repository/LogLoginFailureRepositoryTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Functional\Repository;

use PHPUnit\Framework\Attributes\TestDox;
use Platform\Entity\LogLoginFailure;
use Platform\Repository\LogLoginFailureRepository;
use Platform\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Throwable;

/**
 * Class LogLoginFailureRepositoryTest
 *
 * @package App\Tests\Functional\Repository
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class LogLoginFailureRepositoryTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    #[TestDox('Test that `clear` method works as expected')]
    public function testThatClearMethodWorksAssExpected(): void
    {
        self::bootKernel();

        /** @psalm-var UserRepository $userRepository */
        $userRepository = self::getContainer()->get(UserRepository::class);

        /** @psalm-var LogLoginFailureRepository $logLoginFailureRepository */
        $logLoginFailureRepository = self::getContainer()->get(LogLoginFailureRepository::class);

        $user = $userRepository->find('20000000-0000-1000-8000-000000000001');

        self::assertNotNull($user);

        $entity = new LogLoginFailure($user);

        $logLoginFailureRepository->save($entity);

        self::assertCount(1, $logLoginFailureRepository->findAll());
        self::assertSame(1, $logLoginFailureRepository->clear($user));
        self::assertCount(0, $logLoginFailureRepository->findAll());
    }
}
