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
 * /tests/Integration/AutoMapper/User/RequestMapperTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\AutoMapper\User;

use App\Tests\Integration\TestCase\RestRequestMapperTestCase;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use Platform\AutoMapper\RestRequestMapper;
use Platform\AutoMapper\User\RequestMapper;
use Platform\Entity\UserGroup;
use Platform\Enum\Language;
use Platform\Resource\UserGroupResource;
use Symfony\Component\HttpFoundation\Request;
use Throwable;
use function class_exists;

/**
 * Class RequestMapperTest
 *
 * @package App\Tests\Integration\AutoMapper\User
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class RequestMapperTest extends RestRequestMapperTestCase
{
    /**
     * @var array<int, class-string>
     */
    protected static array $restDtoClasses = [
        \Platform\DTO\User\User::class,
        \Platform\DTO\User\UserCreate::class,
        \Platform\DTO\User\UserUpdate::class,
        \Platform\DTO\User\UserPatch::class,
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
        $userGroup = new UserGroup();

        $resource
            ->expects(self::once())
            ->method('getReference')
            ->with($userGroup->getId())
            ->willReturn($userGroup);

        $request = new Request([], [
            'userGroups' => [$userGroup->getId()],
        ]);

        self::assertTrue(class_exists($dtoClass));

        $dto = $requestMapper->mapToObject($request, new $dtoClass());

        self::assertInstanceOf(\Platform\DTO\User\User::class, $dto);
        self::assertSame([$userGroup], $dto->getUserGroups());
    }

    /**
     * @param class-string $dtoClass
     *
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatTransformLanguageWorksAsExpected')]
    #[TestDox('Test that `transformLanguage` returns `$expected` when using `$dtoClass` DTO and `$input` as an input')]
    public function testThatTransformLanguageWorksAsExpected(Language $expected, string $dtoClass, string $input): void
    {
        $requestMapper = new RequestMapper($this->getResource());

        $request = new Request([], [
            'language' => $input,
        ]);

        $dto = new $dtoClass();
        $output = $requestMapper->mapToObject($request, $dto);

        self::assertInstanceOf(\Platform\DTO\User\User::class, $output);
        self::assertSame($expected, $output->getLanguage());
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
     * @return Generator<array{0: Language, 1: class-string, 2: string}>
     */
    public static function dataProviderTestThatTransformLanguageWorksAsExpected(): Generator
    {
        foreach (static::$restDtoClasses as $dtoClass) {
            yield [Language::EN, $dtoClass, 'en'];
            yield [Language::FI, $dtoClass, 'fi'];
        }
    }

    /**
     * @phpstan-return MockObject&UserGroupResource
     */
    protected function getResource(): MockObject
    {
        return $this->getMockBuilder(UserGroupResource::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function getRequestMapper(): RestRequestMapper
    {
        return new RequestMapper($this->getResource());
    }
}
