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
 * /tests/Integration/Controller/v1/Localization/LanguageControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Controller\v1\Localization;

use PHPUnit\Framework\Attributes\TestDox;
use Platform\Controller\v1\Localization\LanguageController;
use Platform\Service\Localization;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class LanguageControllerTest
 *
 * @package App\Tests\Integration\Controller\v1\Localization
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class LanguageControllerTest extends KernelTestCase
{
    #[TestDox('Test that controller calls expected service method(s) and returns expected response')]
    public function testThatInvokeMethodCallsExpectedServiceMethods(): void
    {
        $Localization = $this->getMockBuilder(Localization::class)->disableOriginalConstructor()->getMock();

        $Localization
            ->expects(static::once())
            ->method('getLanguages')
            ->willReturn(['fi', 'en']);

        $response = (new LanguageController($Localization))();
        $content = $response->getContent();

        self::assertSame(200, $response->getStatusCode());
        self::assertNotFalse($content);
        self::assertJson('["fi", "en"]', $content);
    }
}
