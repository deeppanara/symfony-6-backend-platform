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
 * /tests/Unit/Entity/LogLoginTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Unit\Entity;

use DateTime;
use DateTimeZone;
use DeviceDetector\DeviceDetector;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Doctrine\DBAL\Types\EnumLogLoginType;
use Platform\Entity\LogLogin;
use Platform\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * Class LogLoginTest
 *
 * @package App\Tests\Unit\Entity
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class LogLoginTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatGetCreatedAtReturnsExpected')]
    #[TestDox('Test that `LogLogin::getCreatedAt` method returns expected with `$type` type')]
    public function testThatGetCreatedAtReturnsExpected(
        string $type,
        Request $request,
        DeviceDetector $deviceDetector
    ): void {
        $entity = new LogLogin($type, $request, $deviceDetector);
        $createdAt = $entity->getCreatedAt();

        self::assertNotNull($createdAt);
        self::assertEqualsWithDelta(
            (new DateTime('now', new DateTimeZone('utc')))->getTimestamp(),
            $createdAt->getTimestamp(),
            1
        );
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatGetUserReturnsNullIfUserNotGiven')]
    #[TestDox('Test that `LogLogin::getUser` method returns `null` if user is not provided with `$type` type')]
    public function testThatGetUserReturnsNullIfUserNotGiven(
        string $type,
        Request $request,
        DeviceDetector $deviceDetector
    ): void {
        $entity = new LogLogin($type, $request, $deviceDetector);

        self::assertNull($entity->getUser());
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatGetUserReturnsExpectedUser')]
    #[TestDox('Test that `LogLogin::getUser` method returns provided user with `$type` type')]
    public function testThatGetUserReturnsExpectedUser(
        string $type,
        Request $request,
        DeviceDetector $deviceDetector,
        User $user
    ): void {
        $entity = new LogLogin($type, $request, $deviceDetector, $user);

        self::assertSame($user, $entity->getUser());
    }

    /**
     * @return Generator<array{0: 'success'|'failure', 1: Request, 2: DeviceDetector}>
     */
    public static function dataProviderTestThatGetCreatedAtReturnsExpected(): Generator
    {
        yield [EnumLogLoginType::TYPE_SUCCESS, new Request(), new DeviceDetector('')];

        yield [EnumLogLoginType::TYPE_FAILURE, new Request(), new DeviceDetector('')];
    }

    /**
     * @return Generator<array{0: 'success'|'failure', 1: Request, 2: DeviceDetector}>
     */
    public static function dataProviderTestThatGetUserReturnsNullIfUserNotGiven(): Generator
    {
        yield [EnumLogLoginType::TYPE_SUCCESS, new Request(), new DeviceDetector('')];

        yield [EnumLogLoginType::TYPE_FAILURE, new Request(), new DeviceDetector('')];
    }

    /**
     * @return Generator<array{0: 'success'|'failure', 1: Request, 2: DeviceDetector, 3: User}>
     */
    public static function dataProviderTestThatGetUserReturnsExpectedUser(): Generator
    {
        yield [EnumLogLoginType::TYPE_SUCCESS, new Request(), new DeviceDetector(''), new User()];

        yield [EnumLogLoginType::TYPE_FAILURE, new Request(), new DeviceDetector(''), new User()];
    }
}
