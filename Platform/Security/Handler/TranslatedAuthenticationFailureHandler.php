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
 * /src/Security/Handler/TranslatedAuthenticationFailureHandler.php
 *
 */

namespace Platform\Security\Handler;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationFailureHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 *
 */
class TranslatedAuthenticationFailureHandler extends AuthenticationFailureHandler
{
    public function __construct(
        EventDispatcherInterface $dispatcher,
        private readonly TranslatorInterface $translator,
    ) {
        parent::__construct($dispatcher);
    }

    /**
     * {@inheritdoc}
     *
     * @see https://github.com/lexik/LexikJWTAuthenticationBundle/issues/944
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        /**
         * @psalm-suppress MissingDependency, InvalidArgument
         */
        $event = new AuthenticationFailureEvent(
            $exception,
            new JWTAuthenticationFailureResponse($this->translator->trans('Invalid credentials.', [], 'security'))
        );

        $this->dispatcher->dispatch($event);

        return $event->getResponse();
    }
}
