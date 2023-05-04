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
 * /src/EventSubscriber/AuthenticationFailureSubscriber.php
 *
 */

namespace Platform\EventSubscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Platform\Doctrine\DBAL\Types\EnumLogLoginType;
use Platform\Repository\UserRepository;
use Platform\Utils\LoginLogger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Throwable;


/**
 *
 */
class AuthenticationFailureSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LoginLogger $loginLogger,
        private readonly UserRepository $userRepository,
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AuthenticationFailureEvent::class => 'onAuthenticationFailure',
            Events::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
        ];
    }

    /**
     * Method to log login failures to database.
     *
     * This method is called when following event is broadcast;
     *  - \Lexik\Bundle\JWTAuthenticationBundle\Events::AUTHENTICATION_FAILURE
     *
     * @throws Throwable
     */
    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $token = $event->getException()->getToken();
        $user = $token?->getUser();

        // Fetch user entity
        if ($token !== null && $user !== null) {
            $identifier = $user->getUserIdentifier();

            $this->loginLogger->setUser($this->userRepository->loadUserByIdentifier($identifier, false));
        }

        $this->loginLogger->process(EnumLogLoginType::TYPE_FAILURE);
    }
}
