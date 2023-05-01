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
 * /tests/Functional/Repository/UserRepositoryTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Functional\Repository;

use App\Tests\Utils\PhpUnitUtil;
use PHPUnit\Framework\Attributes\Depends;
use Platform\Entity\User;
use Platform\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Throwable;
use function array_fill;
use function array_map;
use function count;

/**
 * Class UserRepositoryTest
 *
 * @package App\Tests\Functional\Repository
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class UserRepositoryTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    public static function tearDownAfterClass(): void
    {
        self::bootKernel();

        PhpUnitUtil::loadFixtures(self::$kernel);

        self::$kernel->shutdown();

        parent::tearDownAfterClass();
    }

    /**
     * @throws Throwable
     */
    public function testThatCountAdvancedReturnsExpected(): void
    {
        self::assertSame(6, $this->getRepository()->countAdvanced());
    }

    /**
     * @throws Throwable
     */
    public function testThatFindByAdvancedReturnsExpected(): void
    {
        $users = $this->getRepository()->findByAdvanced([
            'username' => 'john',
        ]);

        self::assertCount(1, $users);
    }

    public function testThatFindIdsReturnsExpected(): void
    {
        self::assertCount(
            5,
            $this->getRepository()->findIds(
                [],
                [
                    'or' => 'john-',
                ]
            )
        );
    }

    /**
     * @throws Throwable
     */
    public function testThatIsUsernameAvailableMethodReturnsExpected(): void
    {
        $iterator = static fn (User $user, bool $expected): array => [
            $expected,
            $user->getUsername(),
            $expected ? $user->getId() : null,
        ];

        $users = $this->getRepository()->findAll();

        $data = [
            ...array_map($iterator, $users, array_fill(0, count($users), true)),
            ...array_map($iterator, $users, array_fill(0, count($users), false)),
        ];

        foreach ($data as $set) {
            [$expected, $username, $id] = $set;

            self::assertSame($expected, $this->getRepository()->isUsernameAvailable($username, $id));
        }
    }

    /**
     * @throws Throwable
     */
    public function testThatIsEmailAvailableMethodReturnsExpected(): void
    {
        $iterator = static fn (User $user, bool $expected): array => [
            $expected,
            $user->getEmail(),
            $expected ? $user->getId() : null,
        ];

        $users = $this->getRepository()->findAll();

        $data = [
            ...array_map($iterator, $users, array_fill(0, count($users), true)),
            ...array_map($iterator, $users, array_fill(0, count($users), false)),
        ];

        foreach ($data as $set) {
            [$expected, $email, $id] = $set;

            self::assertSame($expected, $this->getRepository()->isEmailAvailable($email, $id));
        }
    }

    /**
     * @throws Throwable
     */
    #[Depends('testThatIsUsernameAvailableMethodReturnsExpected')]
    #[Depends('testThatIsEmailAvailableMethodReturnsExpected')]
    public function testThatResetMethodDeletesAllRecords(): void
    {
        self::assertSame(6, $this->getRepository()->countAdvanced());
        self::assertSame(6, $this->getRepository()->reset());
        self::assertSame(0, $this->getRepository()->countAdvanced());
    }

    /**
     * @throws Throwable
     */
    private function getRepository(): UserRepository
    {
        static $cache;

        if ($cache === null) {
            self::bootKernel();

            /** @psalm-var UserRepository $cache */
            $cache = self::getContainer()->get(UserRepository::class);
        }

        return $cache;
    }
}
