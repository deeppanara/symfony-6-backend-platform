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
 * /tests/Integration/EventSubscriber/AcceptLanguageSubscriberTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\EventSubscriber;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\EventSubscriber\AcceptLanguageSubscriber;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class AcceptLanguageSubscriberTest
 *
 * @package App\Tests\Integration\EventSubscriber
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class AcceptLanguageSubscriberTest extends KernelTestCase
{
    #[TestDox('Test that specific default language is set')]
    public function testThatSpecifiedDefaultLanguageIsSet(): void
    {
        self::bootKernel();

        $request = new Request();
        $request->headers->set('Accept-Language', 'foo');

        $event = new RequestEvent(self::$kernel, $request, HttpKernelInterface::MAIN_REQUEST);

        $subscriber = new AcceptLanguageSubscriber('bar');
        $subscriber->onKernelRequest($event);

        self::assertSame('bar', $request->getLocale());
    }

    #[DataProvider('dataProviderTestThatLocaleIsSetAsExpected')]
    #[TestDox('Test that when default locale is `$default` and when asking `$asked` locale result is `$expected`.')]
    public function testThatLocaleIsSetAsExpected(string $expected, string $default, string $asked): void
    {
        self::bootKernel();

        $request = new Request();
        $request->headers->set('Accept-Language', $asked);

        $event = new RequestEvent(self::$kernel, $request, HttpKernelInterface::MAIN_REQUEST);

        (new AcceptLanguageSubscriber($default))
            ->onKernelRequest($event);

        self::assertSame($expected, $request->getLocale());
    }

    /**
     * @return Generator<array{0: string, 1: string, 2: string}>
     */
    public static function dataProviderTestThatLocaleIsSetAsExpected(): Generator
    {
        yield ['fi', 'fi', 'fi'];
        yield ['fi', 'fi', 'sv'];
        yield ['en', 'fi', 'en'];
    }
}
