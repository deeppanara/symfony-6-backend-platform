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
 * /tests/Integration/Form/Type/Console/UserGroupTypeTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Form\Type\Console;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use Platform\DTO\UserGroup\UserGroup as UserGroupDto;
use Platform\Entity\Role;
use Platform\Form\DataTransformer\RoleTransformer;
use Platform\Form\Type\Console\UserGroupType;
use Platform\Resource\RoleResource;
use Platform\Security\RolesService;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use function array_keys;

/**
 * Class UserGroupTypeTest
 *
 * @package App\Tests\Integration\Form\Type\Console
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class UserGroupTypeTest extends TypeTestCase
{
    #[TestDox('Test that form submit with valid input data works as expected')]
    public function testSubmitValidData(): void
    {
        $resource = $this->getRoleResource();
        $service = $this->getRolesService();

        // Create new role entity for testing
        $roleEntity = new Role('ROLE_ADMIN');

        $resource
            ->expects(self::once())
            ->method('find')
            ->willReturn([$roleEntity]);

        $resource
            ->expects(self::once())
            ->method('findOne')
            ->with($roleEntity->getId())
            ->willReturn($roleEntity);

        $service
            ->expects(self::once())
            ->method('getRoleLabel')
            ->willReturn('role name');

        // Create form
        $form = $this->factory->create(UserGroupType::class);

        // Create new DTO object
        $dto = (new UserGroupDto())
            ->setName('ROLE_ADMIN')
            ->setRole($roleEntity);

        // Specify used form data
        $formData = [
            'name' => 'ROLE_ADMIN',
            'role' => 'ROLE_ADMIN',
        ];

        // submit the data to the form directly
        $form->submit($formData);

        // Test that data transformers have not been failed
        self::assertTrue($form->isSynchronized());

        // Test that form data matches with the DTO mapping
        self::assertSame($dto->getId(), $form->getData()->getId());
        self::assertSame($dto->getName(), $form->getData()->getName());
        self::assertSame($dto->getRole(), $form->getData()->getRole());

        // Check that form renders correctly
        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            self::assertArrayHasKey($key, $children);
        }
    }

    /**
     * @return array<int, PreloadedExtension>
     */
    protected function getExtensions(): array
    {
        parent::getExtensions();

        $resource = $this->getRoleResource();
        $service = $this->getRolesService();

        // create a type instance with the mocked dependencies
        $type = new UserGroupType($service, $resource, new RoleTransformer($resource));

        return [
            // register the type instances with the PreloadedExtension
            new PreloadedExtension([$type], []),
        ];
    }

    /**
     * @phpstan-return MockObject&RolesService
     */
    private function getRolesService(): MockObject
    {
        static $cache;

        if ($cache === null) {
            $cache = $this->createMock(RolesService::class);
        }

        return $cache;
    }

    /**
     * @phpstan-return MockObject&RoleResource
     */
    private function getRoleResource(): MockObject
    {
        static $cache;

        if ($cache === null) {
            $cache = $this->createMock(RoleResource::class);
        }

        return $cache;
    }
}
