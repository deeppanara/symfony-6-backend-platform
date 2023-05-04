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
 * /tests/Integration/Security/Handler/TranslatedAuthenticationFailureHandlerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Security\Handler;

use PHPUnit\Framework\Attributes\TestDox;
use Platform\Security\Handler\TranslatedAuthenticationFailureHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class TranslatedAuthenticationFailureHandlerTest
 *
 * @package App\Tests\Integration\Security\Handler
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class TranslatedAuthenticationFailureHandlerTest extends KernelTestCase
{
    #[TestDox('Test that `onAuthenticationFailure` method calls expected service methods')]
    public function testThatOnAuthenticationFailureMethodCallsExpectedServiceMethods(): void
    {
        $dispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();

        $translator
            ->expects(self::once())
            ->method('trans')
            ->with(
                'Invalid credentials.',
                [],
                'security',
            )
            ->willReturn('Invalid credentials.');

        $dispatcher
            ->expects(self::once())
            ->method('dispatch');

        $request = new Request();
        $exception = new AuthenticationException('Invalid credentials.');

        (new TranslatedAuthenticationFailureHandler($dispatcher, $translator))
            ->onAuthenticationFailure($request, $exception);
    }
}
