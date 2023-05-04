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
 * /tests/Integration/Controller/v1/Localization/TimeZoneControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Controller\v1\Localization;

use PHPUnit\Framework\Attributes\TestDox;
use Platform\Controller\v1\Localization\TimeZoneController;
use Platform\Service\Localization;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class TimeZoneControllerTest
 *
 * @package App\Tests\Integration\Controller\v1\Localization
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class TimeZoneControllerTest extends KernelTestCase
{
    #[TestDox('Test that controller calls expected service method(s) and returns expected response')]
    public function testThatInvokeMethodCallsExpectedServiceMethods(): void
    {
        $Localization = $this->getMockBuilder(Localization::class)->disableOriginalConstructor()->getMock();

        $Localization
            ->expects(static::once())
            ->method('getTimeZones')
            ->willReturn([
                [
                    'timezone' => 'Europe',
                    'identifier' => 'Europe/Helsinki',
                    'offset' => 'GMT+2:00',
                    'value' => 'Europe/Helsinki',
                ],
            ]);

        $response = (new TimeZoneController($Localization))();
        $content = $response->getContent();

        self::assertSame(200, $response->getStatusCode());
        self::assertNotFalse($content);
        self::assertJson(
            '[{"timezone":"Europe","identifier":"Europe/Helsinki","offset":"GMT+2:00","value":"Europe/Helsinki"}]',
            $content,
        );
    }
}
