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
 * /tests/Integration/EventSubscriber/JWTCreatedSubscriberTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\EventSubscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Platform\Entity\User;
use Platform\Enum\Language;
use Platform\Enum\Locale;
use Platform\EventSubscriber\JWTCreatedSubscriber;
use Platform\Security\SecurityUser;
use Platform\Service\Localization;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\InMemoryUser;

/**
 * Class JWTCreatedSubscriberTest
 *
 * @package App\Tests\Integration\EventSubscriber
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class JWTCreatedSubscriberTest extends KernelTestCase
{
    public function testThatPayloadContainsExpectedDataWhenRequestIsPresent(): void
    {
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        // Create new user for JWT
        $user = (new User())
            ->setFirstName('first name')
            ->setLastName('last name')
            ->setEmail('firstname.surname@test.com');

        // Create RequestStack and push pure Request to it
        $requestStack = new RequestStack();
        $requestStack->push(new Request());

        // Create JWTCreatedEvent
        $event = new JWTCreatedEvent([], new SecurityUser($user));

        (new JWTCreatedSubscriber($requestStack, $logger))
            ->onJWTCreated($event);

        $keys = ['exp', 'checksum'];

        foreach ($keys as $key) {
            self::assertArrayHasKey($key, $event->getData());
        }
    }

    public function testThatPayloadContainsExpectedDataWhenRequestIsNotPresent(): void
    {
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        // Create new user for JWT
        $user = (new User())
            ->setFirstName('first name')
            ->setLastName('last name')
            ->setEmail('firstname.surname@test.com');

        // Create JWTCreatedEvent
        $event = new JWTCreatedEvent([], new SecurityUser($user));

        (new JWTCreatedSubscriber(new RequestStack(), $logger))
            ->onJWTCreated($event);

        $keys = ['exp'];

        foreach ($keys as $key) {
            self::assertArrayHasKey($key, $event->getData());
        }

        self::assertArrayNotHasKey('checksum', $event->getData());
    }

    public function testThatLoggerAlertIsCalledIfRequestDoesNotExist(): void
    {
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $logger
            ->expects(self::once())
            ->method('alert')
            ->with('Request not available');

        // Create new user for JWT
        $user = (new User())
            ->setFirstName('first name')
            ->setLastName('last name')
            ->setEmail('firstname.surname@test.com');

        // Create JWTCreatedEvent
        $event = new JWTCreatedEvent([], new SecurityUser($user));

        (new JWTCreatedSubscriber(new RequestStack(), $logger))
            ->onJWTCreated($event);
    }

    public function testThatExpectedLocalizationDataIsSetWhenUsingSecurityUser(): void
    {
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        // Create new user for JWT
        $user = (new User())
            ->setFirstName('first name')
            ->setLastName('last name')
            ->setEmail('firstname.surname@test.com')
            ->setLanguage(Language::FI)
            ->setLocale(Locale::FI)
            ->setTimezone('UTC');

        // Create RequestStack and push pure Request to it
        $requestStack = new RequestStack();
        $requestStack->push(new Request());

        // Create JWTCreatedEvent
        $event = new JWTCreatedEvent([], new SecurityUser($user));

        (new JWTCreatedSubscriber($requestStack, $logger))
            ->onJWTCreated($event);

        $keys = ['language', 'locale', 'timezone'];

        foreach ($keys as $key) {
            self::assertArrayHasKey($key, $event->getData());
        }

        self::assertSame(Language::FI->value, $event->getData()['language']);
        self::assertSame(Locale::FI->value, $event->getData()['locale']);
        self::assertSame('UTC', $event->getData()['timezone']);
    }

    public function testThatDefaultLocalizationDataIsSetWhenNotUsingSecurityUser(): void
    {
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $user = new InMemoryUser('user', 'password');

        // Create RequestStack and push pure Request to it
        $requestStack = new RequestStack();
        $requestStack->push(new Request());

        // Create JWTCreatedEvent
        $event = new JWTCreatedEvent([], $user);

        (new JWTCreatedSubscriber($requestStack, $logger))
            ->onJWTCreated($event);

        $keys = ['language', 'locale', 'timezone'];

        foreach ($keys as $key) {
            self::assertArrayHasKey($key, $event->getData());
        }

        self::assertSame(Language::getDefault()->value, $event->getData()['language']);
        self::assertSame(Locale::getDefault()->value, $event->getData()['locale']);
        self::assertSame(Localization::DEFAULT_TIMEZONE, $event->getData()['timezone']);
    }
}
