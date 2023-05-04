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
 * /src/EventSubscriber/LockedUserSubscriber.php
 *
 */

namespace Platform\EventSubscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Platform\Entity\LogLoginFailure;
use Platform\Entity\User;
use Platform\Repository\UserRepository;
use Platform\Resource\LogLoginFailureResource;
use Platform\Security\SecurityUser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Throwable;
use function assert;
use function count;
use function is_string;


/**
 *
 */
class LockedUserSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly LogLoginFailureResource $logLoginFailureResource,
        private readonly RequestStack $requestStack,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AuthenticationSuccessEvent::class => [
                'onAuthenticationSuccess',
                128,
            ],
            Events::AUTHENTICATION_SUCCESS => [
                'onAuthenticationSuccess',
                128,
            ],
            AuthenticationFailureEvent::class => 'onAuthenticationFailure',
            Events::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
        ];
    }

    /**
     * @throws Throwable
     */
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $user = $this->getUser($event->getUser()) ?? throw new UnsupportedUserException('Unsupported user.');

        if (count($user->getLogsLoginFailure()) > 10) {
            throw new LockedException('Locked account.');
        }

        $this->logLoginFailureResource->reset($user);
    }

    /**
     * @throws Throwable
     */
    public function onAuthenticationFailure(): void
    {
        $request = $this->requestStack->getCurrentRequest();

        assert($request instanceof Request);

        $user = $this->getUser(
            (string)($request->query->get('username') ?? $request->request->get('username', ''))
        );

        if ($user !== null) {
            $this->logLoginFailureResource->save(new LogLoginFailure($user), true);
        }
    }

    /**
     * @throws Throwable
     */
    private function getUser(string | object $user): ?User
    {
        return match (true) {
            is_string($user) => $this->userRepository->loadUserByIdentifier($user, false),
            $user instanceof SecurityUser =>
                $this->userRepository->loadUserByIdentifier($user->getUserIdentifier(), true),
            default => null,
        };
    }
}
