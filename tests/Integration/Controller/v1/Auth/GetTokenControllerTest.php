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
 * @date     01/05/23, 12:11 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /tests/Integration/Controller/v1/Auth/GetTokenControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Controller\v1\Auth;

use Platform\Controller\v1\Auth\GetTokenController;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/**
 * Class GetTokenControllerTest
 *
 * @package App\Tests\Integration\Controller\v1\Auth
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 *
 * @property GetTokenController $controller
 */
class GetTokenControllerTest extends KernelTestCase
{
    public function testThatGetTokenThrowsAnException(): void
    {
        try {
            (new GetTokenController())();
        } catch (Throwable $exception) {
            self::assertInstanceOf(HttpException::class, $exception);
            self::assertSame(
                'You need to send JSON body to obtain token eg. {"username":"username","password":"password"}',
                $exception->getMessage()
            );

            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            self::assertSame(400, $exception->getStatusCode());
        }
    }
}
