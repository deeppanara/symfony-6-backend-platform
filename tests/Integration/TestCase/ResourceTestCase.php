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
 * /tests/Integration/TestCase/ResourceTestCase.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\TestCase;

use Platform\Entity\Interfaces\EntityInterface;
use Platform\Repository\BaseRepository;
use Platform\Rest\Interfaces\RestResourceInterface;
use Platform\Rest\RestResource;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Throwable;
use function sprintf;

/**
 * Class ResourceTestCase
 *
 * @package App\Tests\Integration\TestCase
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
abstract class ResourceTestCase extends KernelTestCase
{
    /**
     * @var class-string<EntityInterface>
     */
    protected string $entityClass;

    /**
     * @var class-string<BaseRepository>
     */
    protected string $repositoryClass;

    /**
     * @var class-string<RestResource>
     */
    protected string $resourceClass;

    /**
     * @throws Throwable
     */
    public function testThatGetRepositoryReturnsExpected(): void
    {
        $message = sprintf(
            'getRepository() method did not return expected repository \'%s\'.',
            $this->repositoryClass
        );

        /** @noinspection UnnecessaryAssertionInspection */
        self::assertInstanceOf($this->repositoryClass, $this->getResource()->getRepository(), $message);
    }

    /**
     * @throws Throwable
     */
    public function testThatGetEntityNameReturnsExpected(): void
    {
        $message = sprintf(
            'getEntityName() method did not return expected entity \'%s\'.',
            $this->entityClass
        );

        self::assertSame($this->entityClass, $this->getResource()->getEntityName(), $message);
    }

    private function getResource(): RestResourceInterface
    {
        /** @var RestResourceInterface $resource */
        $resource = self::getContainer()->get($this->resourceClass);

        return $resource;
    }
}
