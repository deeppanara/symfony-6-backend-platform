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
 * /src/Validator/Constraints/UniqueEmailValidatorTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Validator\Constraints;

use PHPUnit\Framework\Attributes\TestDox;
use Platform\Entity\User;
use Platform\Repository\UserRepository;
use Platform\Validator\Constraints\UniqueEmail;
use Platform\Validator\Constraints\UniqueEmailValidator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use Throwable;

/**
 * Class UniqueEmailValidatorTest
 *
 * @package App\Validator\Constraints
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class UniqueEmailValidatorTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    #[TestDox('Test that `UniqueEmailValidator::validate` method calls expected service methods')]
    public function testThatValidateCallsExpectedMethods(): void
    {
        $repositoryMock = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $contextMock = $this->getMockBuilder(ExecutionContext::class)->disableOriginalConstructor()->getMock();
        $builderMock = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->getMock();

        // Create new user
        $user = (new User())
            ->setEmail('john.doe@test.com');

        $repositoryMock
            ->expects(self::once())
            ->method('isEmailAvailable')
            ->with($user->getEmail(), $user->getId())
            ->willReturn(false);

        $contextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with(UniqueEmail::MESSAGE)
            ->willReturn($builderMock);

        $builderMock
            ->expects(self::once())
            ->method('setCode')
            ->with(UniqueEmail::IS_UNIQUE_EMAIL_ERROR)
            ->willReturn($builderMock);

        $builderMock
            ->expects(self::once())
            ->method('addViolation');

        // Run validator
        $validator = new UniqueEmailValidator($repositoryMock);
        $validator->initialize($contextMock);
        $validator->validate($user, new UniqueEmail());
    }
}
