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
 * /src/EventSubscriber/AuthenticationSuccessSubscriber.php
 *
 */

namespace Platform\EventSubscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Platform\Doctrine\DBAL\Types\EnumLogLoginType;
use Platform\Repository\UserRepository;
use Platform\Utils\LoginLogger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Throwable;


/**
 *
 */
class AuthenticationSuccessSubscriber implements EventSubscriberInterface
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
            AuthenticationSuccessEvent::class => 'onAuthenticationSuccess',
            Events::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccess',
        ];
    }

    /**
     * Method to log user successfully login to database.
     *
     * This method is called when following event is broadcast
     *  - lexik_jwt_authentication.on_authentication_success
     *
     * @throws Throwable
     */
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $this->loginLogger
            ->setUser($this->userRepository->loadUserByIdentifier($event->getUser()->getUserIdentifier(), true))
            ->process(EnumLogLoginType::TYPE_SUCCESS);
    }
}
