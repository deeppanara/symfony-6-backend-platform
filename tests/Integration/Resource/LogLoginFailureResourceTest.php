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
 * /tests/Integration/Resource/LogLoginFailureResourceTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Resource;

use App\Tests\Integration\TestCase\ResourceTestCase;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Entity\LogLoginFailure;
use Platform\Entity\User;
use Platform\Repository\BaseRepository;
use Platform\Repository\LogLoginFailureRepository;
use Platform\Resource\LogLoginFailureResource;
use Platform\Rest\RestResource;
use Throwable;

/**
 * Class LogLoginFailureResourceTest
 *
 * @package App\Tests\Integration\Resource
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class LogLoginFailureResourceTest extends ResourceTestCase
{
    /**
     * @var class-string<EntityInterface>
     */
    protected string $entityClass = LogLoginFailure::class;

    /**
     * @var class-string<BaseRepository>
     */
    protected string $repositoryClass = LogLoginFailureRepository::class;

    /**
     * @var class-string<RestResource>
     */
    protected string $resourceClass = LogLoginFailureResource::class;

    /**
     * @throws Throwable
     */
    public function testThatResetMethodCallsExpectedRepositoryMethod(): void
    {
        $repository = $this->getMockBuilder($this->repositoryClass)->disableOriginalConstructor()->getMock();

        $user = (new User())->setUsername('username');

        $repository
            ->expects(self::once())
            ->method('clear')
            ->with($user)
            ->willReturn(0);

        /**
         * @phpstan-ignore-next-line
         *
         * @var LogLoginFailureRepository $repository
         */
        (new LogLoginFailureResource($repository))->reset($user);
    }
}
