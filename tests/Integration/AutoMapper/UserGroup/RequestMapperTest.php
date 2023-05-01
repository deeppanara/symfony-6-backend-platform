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
 * @date     01/05/23, 12:17 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /tests/Integration/AutoMapper/UserGroup/RequestMapperTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\AutoMapper\UserGroup;

use App\Tests\Integration\TestCase\RestRequestMapperTestCase;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use Platform\AutoMapper\RestRequestMapper;
use Platform\AutoMapper\UserGroup\RequestMapper;
use Platform\Entity\Role;
use Platform\Resource\RoleResource;
use Symfony\Component\HttpFoundation\Request;
use Throwable;
use function class_exists;

/**
 * Class RequestMapperTest
 *
 * @package App\Tests\Integration\AutoMapper\UserGroup
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class RequestMapperTest extends RestRequestMapperTestCase
{
    /**
     * @var array<int, class-string>
     */
    protected static array $restDtoClasses = [
        \Platform\DTO\UserGroup\UserGroup::class,
        \Platform\DTO\UserGroup\UserGroupCreate::class,
        \Platform\DTO\UserGroup\UserGroupUpdate::class,
        \Platform\DTO\UserGroup\UserGroupPatch::class,
    ];

    /**
     * @param class-string $dtoClass
     *
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatTransformUserGroupsCallsExpectedResourceMethod')]
    #[TestDox('Test that `transformUserGroups` calls expected resource method when processing `$dtoClass` DTO object')]
    public function testThatTransformUserGroupsCallsExpectedResourceMethod(string $dtoClass): void
    {
        $resource = $this->getResource();
        $requestMapper = new RequestMapper($resource);
        $role = new Role('Some Role');

        $resource
            ->expects(self::once())
            ->method('getReference')
            ->with($role->getId())
            ->willReturn($role);

        $request = new Request(
            [],
            [
                'role' => $role->getId(),
            ]
        );

        self::assertTrue(class_exists($dtoClass));

        $dto = $requestMapper->mapToObject($request, new $dtoClass());

        self::assertInstanceOf(\Platform\DTO\UserGroup\UserGroup::class, $dto);
        self::assertSame($role, $dto->getRole());
    }

    /**
     * @return Generator<array{0: class-string}>
     */
    public static function dataProviderTestThatTransformUserGroupsCallsExpectedResourceMethod(): Generator
    {
        foreach (static::$restDtoClasses as $dtoClass) {
            yield [$dtoClass];
        }
    }

    /**
     * @phpstan-return MockObject&RoleResource
     */
    protected function getResource(): MockObject
    {
        return $this->getMockBuilder(RoleResource::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function getRequestMapper(): RestRequestMapper
    {
        return new RequestMapper($this->getResource());
    }
}
