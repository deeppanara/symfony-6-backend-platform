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
 * /tests/Integration/Controller/IndexControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Controller;

use PHPUnit\Framework\Attributes\TestDox;
use Platform\Controller\IndexController;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class IndexControllerTest
 *
 * @package App\Tests\Integration\Controller
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class IndexControllerTest extends KernelTestCase
{
    #[TestDox('Test that `__invoke` method returns proper response')]
    public function testThatInvokeMethodReturnsExpectedResponse(): void
    {
        $response = (new IndexController())();
        $content = $response->getContent();

        self::assertSame(200, $response->getStatusCode());
        self::assertNotFalse($content);
        self::assertJson($content);
        self::assertJsonStringEqualsJsonString('{}', $content);
    }
}
