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
 * /src/ValueResolver/LoggedInUserValueResolver.php
 *
 */

namespace Platform\ValueResolver;

use Generator;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\MissingTokenException;
use Platform\Entity\User;
use Platform\Security\UserTypeIdentification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Throwable;

/*
 *
 * Example how to use this within your controller;
 *
 *  #[Route(path: 'some-path')]
 *  #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
 *  public function someMethod(\Platform\Entity\User $loggedInUser): Response
 *  {
 *      ...
 *  }
 *
 * This will automatically convert your security user to actual User entity that
 * you can use within your controller as you like.
 */

/**
 *
 */
class LoggedInUserValueResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly UserTypeIdentification $userService,
    ) {
    }

    public function supports(ArgumentMetadata $argument): bool
    {
        $output = false;

        // only security user implementations are supported
        if ($argument->getName() === 'loggedInUser' && $argument->getType() === User::class) {
            $securityUser = $this->userService->getSecurityUser();

            if ($securityUser === null && $argument->isNullable() === false) {
                throw new MissingTokenException('JWT Token not found');
            }

            $output = true;
        }

        return $output;
    }

    /**
     * @throws Throwable
     *
     */
    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        if (!$this->supports($argument)) {
            return [];
        }

        yield $this->userService->getUser();
    }
}
