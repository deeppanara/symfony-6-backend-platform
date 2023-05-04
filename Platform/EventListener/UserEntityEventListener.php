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
 * /src/EventListener/UserEntityEventListener.php
 *
 */

namespace Platform\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use LengthException;
use Platform\Entity\User;
use Platform\Security\SecurityUser;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function strlen;


/**
 *
 */
class UserEntityEventListener
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public function prePersist(LifecycleEventArgs $event): void
    {
        $this->process($event);
    }

    public function preUpdate(LifecycleEventArgs $event): void
    {
        $this->process($event);
    }

    private function process(LifecycleEventArgs $event): void
    {
        // Get user entity object
        $user = $event->getObject();

        // Valid user so lets change password
        if ($user instanceof User) {
            $this->changePassword($user);
        }
    }

    private function changePassword(User $user): void
    {
        // Get plain password from user entity
        $plainPassword = $user->getPlainPassword();

        // Yeah, we have new plain password set, so we need to encode it
        if ($plainPassword !== '') {
            if (strlen($plainPassword) < 8) {
                throw new LengthException('Too short password');
            }

            // Password hash callback
            $callback = fn (string $plainPassword): string => $this->userPasswordHasher
                ->hashPassword(new SecurityUser($user, []), $plainPassword);

            // Set new password and encode it with user encoder
            $user->setPassword($callback, $plainPassword);

            // And clean up plain password from entity
            $user->eraseCredentials();
        }
    }
}
